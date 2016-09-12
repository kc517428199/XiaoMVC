<?php
    namespace Xiao;

    /**
     * Log类
     *
     * @author  xiaokc
     */
    class Log {
//        public $log;
        static private $_ext = '.log';

        public function __construct()
        {

        }

        /**
         * 写入日志文件
         *
         * @access private
         * @param array $content 内容
         * @return bool
         * @author xiaokc
         */
        static private function _writeLog($type, $content, $path, $file = 'log')
        {
            $text = array(
                'type' => $type ,
                'datetime' => date("Y-m-d H:i:s") ,
                'content' => $content ,
                'path' => $path
            );

            $log = new \Xiao\File(APP_LOG . $file . self::$_ext);
            return $log->write(json_encode($text)."\n", 'a');
        }

        /**
         * 日志
         *
         * @access public
         * @return bool
         * @author xiaokc
         */
        static public function log($type, $content, $path = null, $file = 'log')
        {
            is_null($path)&&$path = debug_backtrace()[0]['file'];
            return self::_writeLog($type, $content, $path, $file);
        }

        static public function errorLog($content, $path = null)
        {
            is_null($path)&&$path = debug_backtrace()[0]['file'];
            return self::_writeLog('error', $content, $path, 'error');
        }

    }