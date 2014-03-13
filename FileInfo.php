<?php
namespace PureLib\Upload;

class FileInfo extends \SplFileInfo {
    ///基础路径
    protected $basePath;
    ///存储目录
    protected $dir;
    ///存储文件路径
    protected $file;

    public function __construct($file, $base_path, $dir) {
        $this->file = $file;
        $this->basePath = $base_path;
        $this->dir = $dir;
        parent::__construct($file);
    }

    /**
     * 获得基础路径
     */
    public function getBasePath() {
        return $this->basePath;
    }

    /**
     * 获得存储目录
     */
    public function getDir() {
        return $this->dir;
    }

    /**
     * 获得相对路径
     *
     * @return string
     */
    public function getRelativePath() {
        $path = trim(substr($this->file, strlen($this->basePath)), '\//');
        return $path;
    }

    /**
     * 获得文件Mime类型
     * 
     * @return string
     */
    public function getMimeType() {
        $file = $this->file;
        if (class_exists('\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME);
            $mimetype = $finfo->file($file);
            $mimetypeParts = preg_split('#\s*[;,]\s*#', $mimetype);
            $mimetype = strtolower($mimetypeParts[0]);
            return $mimetype;
        } elseif(function_exists('\mime_content_type')) {
            $mimetype = \mime_content_type($file);
            return $mimetype;
        } else {
            $disabled = explode(',', ini_get('disable_functions'));
            if (!in_array('exec', $disabled)) {
                $mimetype = exec(trim('file -bi ' . escapeshellarg ($file)));
                return substr($mimetype, 0, strpos($mimetype, ';'));
            }
        }
    }

    /**
     * 如果文件为图像格式，获得图像的尺寸
     *
     * @return array 尺寸数组
     */
    public function getDimensions() {
        list($width, $height) = getimagesize($this->file);
        return array('width'=>$width, 'height'=>$height);
    }
}