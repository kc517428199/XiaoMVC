<?php
    namespace Xiao;

    /**
     * File类
     *
     * @author  xiaokc
     */
    class File {
        public $fileName;
        public $filePath;

        public function __construct($filePath)
        {
            $this->filePath = $filePath;
            $this->fileName = basename($this->filePath);
        }

        /**
         * 读取文件
         *
         * @access public
         * @return string 返回内容
         * @author xiaokc
         */
        public function read()
        {
            $file = fopen($this->filePath,"r");
            if(!flock($file, LOCK_SH)){
                fclose($file);
                return false;
            }
            $text = fread($file,filesize($this->filePath));
            flock($file, LOCK_UN);
            fclose($file);
            return $text;
        }

        /**
         * 写入文件
         *
         * @access public
         * @param string $text 内容
         * @param string $mode 类型
         * @return string 返回写入字符数
         * @author xiaokc
         */
        public function write($text, $mode = 'w')
        {
            if(!is_dir(dirname($this->filePath)) && !$this->mkdirs(dirname($this->filePath))){
                return false;
            }

            $file = fopen($this->filePath, $mode);
            // LOCK_NB Windows不支持
            if(!flock($file, LOCK_EX | LOCK_NB)){
                fclose($file);
                return false;
            }
            $number = fwrite($file, $text);
            flock($file, LOCK_UN);
            fclose($file);
            return $number;
        }

        /**
         * 创建多级目录
         *
         * @access public
         * @param string $dir 目录路径
         * @param string $mode 权限
         * @return bool
         * @author xiaokc
         */
        public function mkdirs($dir, $mode = 0777)
        {
            if (is_dir($dir) || @mkdir($dir, $mode)) return true;
            if (!$this->mkdirs(dirname($dir), $mode)) return false;
            return @mkdir($dir, $mode);
        }

        /**
         * 删除文件
         *
         * @access public
         * @return bool
         * @author xiaokc
         */
        public function delete(){
            if(!unlink($this->filePath)) {
                return false;
            }
            return true;
        }
    }