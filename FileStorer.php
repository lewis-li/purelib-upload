<?php
namespace PureLib\Upload;

/**
 * 文件存储
 *
 */
class FileStorer {

    ///基础路径
    protected $basePath;

    ///存储目录
    protected $dir;

    ///最后错误信息
    protected $error;

    ///文件路径
    protected $file;

    ///SplFileInfo对象
    protected $fileinfo;

    /**
     * @param string $basePath 基本路径
     */
    public function __construct($base_path, $dir) {
        $this->basePath = $base_path;
        $this->dir = $dir;
    }

    /**
     * 存储文件
     *
     * @param string $source 原文件
     * @param string $dest 目标文件
     * @param boolean $isupload 是否为上传文件
     * @return boolean
     */
    public function copy($source, $dest,$isupload=false) {
        $dest = rtrim($this->basePath, '\\/') . '/' . ltrim($dest, '\\/');
        if ($this->checkPath(dirname($dest)) === false) {
            $this->error = '无法创建目录';
            return false;
        }

        if ($isupload) {
            if (move_uploaded_file($source, $dest) === false) {
                $this->error = '无法移动文件';
                return false;
            }
        } else {
            if (!copy($source, $dest)) {
                $this->error = '无法移动文件';
                return false;
            }
        }

        $this->file = $dest;

        //return true;

        return $this->getFileInfo($dest);
    }

    /**
     * 检查目录是否存在，不存在时新建目录，失败时返回false
     *
     * @param string $path
     * @return boolean
     */
    protected function checkPath($path) {
        if (!is_dir($path)) {
            $oldumask = umask(0);
            if (mkdir($path) === false) {
                return false;
            }
            umask($oldumask);
        }
    }

    /**
     * 获取SplFileInfo对象
     *
     * @return \SplFileInfo
     */
    protected function getFileInfo($file) {
        return new FileInfo($file, $this->basePath, $this->dir);
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getError() {
        return $this->error;
    }
}