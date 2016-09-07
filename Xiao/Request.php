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

        public static $controller;
        public static $method;
        public static $params;
        public static $rowBody = null;

        /**
         * Is this a GET request?
         * @return bool
         */
        static public function isGet()
        {
            return self::getMethod() === self::METHOD_GET;
        }

        /**
         * Is this a POST request?
         * @return bool
         */
        static public function isPost()
        {
            return self::getMethod() === self::METHOD_POST;
        }

        /**
         * Get HTTP method
         * @return string
         */
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

        static public function rawBody()
        {
            if(is_null(static::$rowBody))
                static::$rowBody = file_get_contents('php://input');

            return static::$rowBody;
        }

        // 当前请求的url
        public static function url()
        {

        }

        public function __set($key, $value)
        {
            self::$key = $value;
        }

}