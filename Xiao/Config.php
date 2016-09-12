<?php
    namespace Xiao;

    /**
     * Config类
     *
     * @author  xiaokc
     */
    class Config {

        static private $configMethod = array();
        static private $configProperties = array();

        static public function __callStatic($name, $arguments)
        {
            if(isset(self::$configMethod[$name])) {
                return self::$configMethod[$name];
            }

            $file = APP_CONFIG . $name . EXT;
            if(file_exists($file)) {
                self::$configMethod[$name] = (object)require $file;
                return self::$configMethod[$name];
            } else {
                return array();
            }
        }

        public function __set($name, $value)
        {
            return self::$configProperties[$name] = $value;
        }

        public function __get($name)
        {
            return self::$configProperties[$name];
        }
    }