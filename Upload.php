<?php

namespace PureLib\Upload;

require_once 'FileStorer.php';

class Upload {

    protected static $errors = array();

    protected static $scenes;

    /**
     * 设置场景
     *
     * @param string $name 场景名称
     * @param array $config 场景配置,可设置项与upload方法$config参数一致
     */
    public static function setScene($name, array $config) {
        self::$scenes[$name] = $config;
    }

    /**
     * 获得场景配置信息
     *
     * @param string $name
     * @return Ambigous <boolean, array>
     */
    public static function getScene($name) {
        return isset(self::$scenes[$name]) ? new \ArrayObject(self::$scenes[$name], \ArrayObject::STD_PROP_LIST+\ArrayObject::ARRAY_AS_PROPS) : false;
    }

    /**
     * 根据错误码获得错误信息
     *
     * @param int $code
     * @return string|null
     */
    public static function error($code) {
        $result = null;
        switch ($code) {
            case UPLOAD_ERR_OK: //0
                $result = true;
                break;
            case UPLOAD_ERR_INI_SIZE: //1
                $result = '文件大小超出设置';
                break;
            case UPLOAD_ERR_FORM_SIZE: //2 $_POSE['MAX_FILE_SIZE']
                $result = '文件大小超出表单设置';
                break;
            case UPLOAD_ERR_PARTIAL: //3
                $result = '上传不完整';
                break;
            case UPLOAD_ERR_NO_FILE: //4
                $result = '没上传文件';
                break;
            case UPLOAD_ERR_NO_TMP_DIR: //6
                $result = '找不到临时目录';
                break;
            case UPLOAD_ERR_CANT_WRITE: //7
                $result = '文件写入失败';
                break;
        }
        return $result;
    }

    /**
     * 获得错误
     *
     * @param string $name,如果为空，则返回所有错误信息
     */
    public static function getError($name=null) {
        if ($name === null) {
            return self::$errors;
        } else {
            return isset(self::$errors[$name]) ? self::$errors[$name] : null;
        }
    }

    /** 上传文件
     * 原型 Upload::upload($name, array $config);
     *
     * @param $name 表单上传项的名称
     * @param $config 配置,有如下配置项:
     *   - base_path 基本路径
     *   - dir 文件保存路径（相对于base_path）
     *   - verify 验证，有如下可选规则:
     *       -# notempty 不可为空
     *       -# size 大小限制(以字节为单位)
     *       -# type 文件类型(仅实现图像类型的判断),可选值为:
     *       -# mime_type
     *       -# 回调
     *   - success 上传成功后执行的回调函数,传入参数为文件信息对象(SplFileInfo扩展, @see \SplFileInfo)
     *       除了SplFileInfo的方法:
     *       -# getPath() 获得文件的路径(不含文件名)
     *       -# getRealPath() 获得文件的路径(不含文件名)
     *       -# getFileName() 获得文件名
     *       -# getBaseName() 获得文件名(可以设置是否排除后缀)
     *       -# getExtension() 获得文件的扩展名
     *       -# getSize() 获得文件大小
     *       还有如下扩展方法:
     *       -# getMimeType() 获得文件MIME类型
     *       -# getDimensions() 如果文件是图像类型,获得图片的宽度和高度信息
     *   - error 上传失败后执行的回调函数,传入参数为错误信息
     * @return false | fileinfo文件信息对象
     */
    public static function upload($name=null, array $config=array()) {

        if (isset($config['scene']) && isset(self::$scenes[$config['scene']])) {
            $config = array_merge(self::$scenes[$config['scene']], $config);
        } elseif(isset(self::$scenes['global'])) {
            $config = array_merge(self::$scenes['global'], $config);
        }

        $error_handler = isset($config['error']) && is_callable($config['error'])
        ? $config['error'] : function (){
        };
        $success_handler = isset($config['success']) && is_callable($config['success'])
        ? $config['success'] : function (){
        };

        //判断有无上传域
        if (empty($_FILES) || !isset($_FILES[$name])) {
            self::$errors[$name] = '不存在上传字段';
            goto error;
        }

        $files = $_FILES[$name];
        //验证
        if (isset($config['verify'])) {
            $rules = $config['verify'];

            foreach ($rules as $rule=>$value) {
                if (is_callable($rule)) {
                    if(call_user_func($rule, $name, $value)=== false) {
                        self::$errors[$name] = "$name验证失败";
                        goto error;
                    }
                } elseif(is_string($rule) && in_array(strtolower($rule), array('notempty', 'size', 'mime_type', 'type'))) {
                    if(self::verify($name, $rule, $value)===false) {
                        self::$errors[$name] = "{$name} 验证失败:" . self::getVerifyMessage($rule);
                        goto error;
                    }
                }
            }
        }

        $storer = self::getStorer(array(
                        'base_path' => isset($config['base_path']) ? $config['base_path'] : null,
                        'dir' => isset($config['dir']) ? $config['dir'] : '',
        ));


        if (is_array($_FILES[$name]['error'])) { //组文件
            $len = count($_FILES[$name]['error']);
            $result = array();
            for ($i=0; $i<$len;$i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $n = strrpos($files['name'][$i], '.');
                    $ext = $n !== false ? substr($files['name'][$i], $n) : null;
                    //@todo 生成唯一标识
                    $dest = uniqid(time()) . $ext;
                    $result[$i] = $storer->copy($files['tmp_name'][$i], $config['dir'] . '/' .$dest, true);
                    if ($result === false) {
                        self::$errors[$name][$i] = $storer->getError();
                        goto error;
                    }
                } else {
                    self::$errors[$name][$i] = self::error($files['error'][$i]);
                    goto error;
                }
            }
            goto success;
        } else { //单文件
            //判断是否有文件上传
            if ($files['error'] !== UPLOAD_ERR_OK) {
                self::$errors[$name] = self::error($files['error']);
                goto error;
            }

            $n = strrpos($files['name'], '.');
            $ext = $n !== false ? substr($files['name'], $n) : null;
            //@todo 生成唯一标识
            $dest = uniqid(time()) . $ext;
            $result = $storer->copy($files['tmp_name'], $config['dir'] . '/' .$dest, true);

            if ($result == false) {
                self::$errors[$name] = $storer->getError();
                goto error;
            }
            goto success;
        }

        error: {
            call_user_func($error_handler, self::$errors[$name]);
            return false;
        }

        success: {
            call_user_func($success_handler, $result);
            //返回文件信息对象
            return $storer;
        }
    }

    /**
     * 上传流数据
     * @param resource $stream
     * @param array $config
     * @return boolean|FileInfo
     */
    public static function uploadStream($stream=null, $config=array()) {
        if (isset($config['scene']) && isset(self::$scenes[$config['scene']])) {
            $config = array_merge(self::$scenes[$config['scene']], $config);
        } elseif(isset(self::$scenes['public'])) {
            $config = array_merge(self::$scenes['public'], $config);
        }

        $error_handler = isset($config['error']) && is_callable($config['error'])
        ? $config['error'] : function (){
        };
        $success_handler = isset($config['success']) && is_callable($config['success'])
        ? $config['success'] : function (){
        };

        if ($stream === null) {
            $stream = fopen('php://input', 'rb');
        }

        $header = fread($stream, 15);

        $stream = $header . stream_get_contents($stream);

        $ext = \PureLib\Upload\MimeType::getExtensionByStreamHeader($header);

        if ($ext) {
            $ext = '.' . $ext;
        }

        $dest = uniqid(time()) . $ext;

        $dir = isset($config['dir']) ? $config['dir'] : '';
        $base_path = isset($config['base_path']) ? $config['base_path'] : '';
        $upload_path = (empty($base_path) ? $base_path : $base_path.'/').$dir;
        // e:/abc/test
        // test

        if (is_dir($upload_path)){
            //test/test.jpg
            $upload = fopen($upload_path . '/' .$dest, 'w+b');
            $check_upload = fwrite($upload, $stream);
            fclose($upload);

            if ($check_upload) {
                $result = new \SplFileInfo( $upload_path.'/'.$dest);
                // $result = new FileInfo($dest, $base_path, $dir);
                goto success;
            } else {
                // @todo lewis $storer
                self::$errors[$name] = $storer->getError();
                goto error;
            }
        }

        goto error;
        error: {
            call_user_func($error_handler, self::$errors[$name]);
            return false;
        }

        success: {
            call_user_func($success_handler, $result);
            //返回流
            return $stream;
            // return $result;
        }
    }

    /**
     * 判断是否有文件上传项
     */
    public static function hasUpload($name=null) {
        return !empty($_FILES);
    }

    protected static function getStorer($args=array()) {
        $class = isset($args['class']) ? $args['class'] : __NAMESPACE__.'\FileStorer';

        if (!isset($args['base_path']) || empty($args['base_path'])) {
            $args['base_path'] = dirname($_SERVER['SCRIPT_FILENAME']);
        }

        if (!isset($args['dir'])) {
            $args['dir'] = '';
        }

        $r = new \ReflectionClass($class);
        $instance = $r->newInstanceArgs($args);
        return $instance;
    }

    /**
     * 获得文件验证错误信息
     *
     * @param string $rule 验证规则名
     * @return string|null
     */
    public static function getVerifyMessage($rule) {
        $messages = array(
                        'notempty' => '没上传文件',
                        'size' => '文件大小超出限制',
                        'type' => '文件类型错误',
                        'mime_type' => 'mime类型错误',
        );
        if (isset($messages[$rule])) {
            return $messages[$rule];
        }
    }

    /**
     * 验证
     * @param string $name
     * @param array $condition 要验证的规则,规则如下
     *     - notempty: 不为空 e.g notempty=>true
     *     - size: 文件大小 e.g sieze=>3000 //单位为字节
     *     - type: 文件类型 e.g type=>'image' //仅实现image类型的判断
     *     - mime_type: 文件mime类型 e.g mime=> array('image/jpeg')
     */
    public static function verify($name, $rule, $value=null) {
        $result = true;
        if (!isset($_FILES[$name])) {
            return false;
        }
        switch (strtolower($rule)) {
            case 'notempty':
                if ($value) return self::verifyNotEmpty($_FILES[$name]);
                break;
            case 'size':
                return self::verifySize($_FILES[$name], $value);
                break;
            case 'type':
                return self::verifyType($_FILES[$name], $value);
                break;
            case 'mime_type': {
                return self::verifyMimeType($_FILES[$name], $value);
                break;
            }
        }
    }

    /**
     * 验证文件非空
     *
     * @param mixed $files
     * @return boolean
     */
    protected static function verifyNotEmpty($files) {
        if (is_array($files['name'])) {
            $len = count($files['name']);
            for ($i=0; $i<$len; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    return false;
                }
            }
            return true;
        } else {
            return $files['error'] === UPLOAD_ERR_NO_FILE ? false : true;
        }
    }
    /**
     * 验证文件大小
     *
     * @param mixed $files
     * @param int $size 文件大小
     * @return boolean
     */
    protected static function verifySize($files, $size) {
        if (is_array($files['name'])) {
            $len = count($files['name']);
            for ($i=0; $i<$len; $i++) {
                if (ceil($files['size'][$i]) > (int)$size) {
                    return false;
                }
            }
            return true;
        } else {
            return ceil($files['size']) > (int)$size ? false : true;
        }
    }

    /**
     * 验证文件类型
     *
     * @param mixed $files
     * @return boolean
     */
    protected static function verifyType($files, $type) {
        if ($type ==='image') {
            if (is_array($files['name'])) {
                $len = count($files['name']);
                for ($i=0; $i<$len; $i++) {
                    if (@getimagesize($files['tmp_name'][$i])===false) {
                        return false;
                    }
                }
                return true;
            } else {
                return @getimagesize($files['tmp_name'])===false ? false : true;
            }
        } else {
            return false;
        }
    }

    /**
     * 验证文件MIME类型
     *
     * @param  $files
     * @param  $type
     * @return boolean
     */
    protected static function verifyMimeType($files, $type) {
        //使用 finfo
        if (class_exists('\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME);
            if (is_array($files['name'])) {
                $len = count($files['name']);
                for ($i=0; $i<$len; $i++) {
                    $mimetype = $finfo->file($files['tmp_name'][$i]);
                    $mimetypeParts = preg_split('#\s*[;,]\s*#', $mimetype);
                    $mimetype = strtolower($mimetypeParts[0]);

                    if ($mimetype !== $type) {
                        return false;
                    }
                }
                return true;
            } else {
                $mimetype = $finfo->file($files['tmp_name']);
                $mimetypeParts = preg_split('#\s*[;,]\s*#', $mimetype);
                $mimetype = strtolower($mimetypeParts[0]);

                return $mimetype === $type;
            }
        }

        //使用 mime_content_type函数
        elseif(function_exists('\mime_content_type')) {
            if (is_array($files['name'])) {
                $len = count($files['name']);
                for ($i=0; $i<$len; $i++) {
                    $mimetype = \mime_content_type($files['tmp_name'][$i]);
                    if ($mimetype !== $type) {
                        return false;
                    }
                }
                return true;
            } else {
                $mimetype = \mime_content_type($files['tmp_name']);
                return $mimetype === $type;
            }
        }

        //使用 exec
        elseif(!in_array('exec', explode(',', ini_get('disable_functions')))) {
            if (is_array($files['name'])) {
                $len = count($files['name']);
                for ($i=0; $i<$len; $i++) {
                    $mimetype = exec(trim( 'file -bi ' . escapeshellarg ( $files['tmp_name'][$i])));
                    $mimetype = substr($mimetype, 0, strpos($mimetype, ';'));
                    if ($mimetype !== $type) {
                        return false;
                    }
                }
                return true;
            } else {
                $mimetype = exec(trim( 'file -bi ' . escapeshellarg ( $files['tmp_name'])));
                $mimetype = substr($mimetype, 0, strpos($mimetype, ';'));
                return $mimetype === $type;
            }
        }

        else {
            return false;
        }
    }
}