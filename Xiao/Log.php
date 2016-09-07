<?php
    namespace Xiao;

    /**
     * Log类
     *
     * @author  xiaokc
     */
    class Log {
        public $logDirPath;
        public $logName;
        public $filePath;
        public $content;
        public $log;

        private $_ext = '.log';

        public function __construct($logName, $filePath, $content)
        {
            $this->logDirPath = APP_PATH.'log'.DIRECTORY_SEPARATOR;
            $this->logName = $logName;
            $this->filePath = $filePath;
            $this->content = $content;

            $this->log = new \Xiao\File($this->logDirPath.$this->logName.$this->_ext);
        }

        /**
         * 写入日志文件
         *
         * @access private
         * @param array $content 内容
         * @return bool
         * @author xiaokc
         */
        private function _writeLog($content)
        {
            return $this->log->write(json_encode($content)."\n", 'a');
        }

        /**
         * 日志
         *
         * @access public
         * @return bool
         * @author xiaokc
         */
        public function log()
        {
            $content = array(
                'file' => $this->logName ,
                'datetime' => time() ,
                'content' => $this->content ,
                'filepath' => $this->filePath
            );
            return $this->_writeLog($content);
        }

    }