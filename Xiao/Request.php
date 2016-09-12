<?php
    namespace Xiao;

    /**
     * Request类
     *
     * @author  xiaokc
     */
    class Request
    {
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';

        public static $rowBody = null;

        static public function isGet()
        {
            return self::getMethod() === self::METHOD_GET;
        }

        static public function isPost()
        {
            return self::getMethod() === self::METHOD_POST;
        }

        static public function getMethod()
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        static public function get($key = null)
        {
            if (is_null($key)) return $_GET;
            else return isset($_GET[$key]) ? (is_array($_GET[$key]) ? $_GET[$key] : trim($_GET[$key])) : NULL;
        }

        static public function post($key = null)
        {
            if (is_null($key)) return $_POST;
            else return isset($_POST[$key]) ? (is_array($_POST[$key]) ? $_POST[$key] : trim($_POST[$key])) : NULL;
        }

        static public function server($key = null)
        {
            if (is_null($key)) return $_SERVER;
            else return isset($_SERVER[$key]) ? (is_array($_SERVER[$key]) ? $_SERVER[$key] : trim($_SERVER[$key])) : NULL;
        }
        
    }