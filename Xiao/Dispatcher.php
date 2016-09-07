<?php

    namespace Xiao;

    /**
     * Dispatchar类
     *
     * @author  xiaokc
     */
    class Dispatcher
    {
        private static $controllerName = 'Xiao\\IndexController';
        private static $methodName = 'index';
        private static $paramsArray = array();

        static public function dispatch()
        {
    //        var_dump($_SERVER['REQUEST_URI']);
            $path = explode('/', $_SERVER['REQUEST_URI']);

            $i = 0;
            foreach ($path as $key => $value) {
                if ($i != 0 && $key != 0 && (!isset($path[$i]) || empty($path[$i]))) {
                    break;
                }

                if ($key != $i) {
                    self::$paramsArray[$path[$i]] = $path[$i + 1];
                    $i++;
                }
                if ($value == APP_NAME) {
                    if(isset($path[$i + 1]) && !empty($path[$i + 1])){
                        self::$controllerName = 'Xiao\\' . ucfirst($path[$i + 1]) . 'Controller';
                    }
                    if(isset($path[$i + 2]) && !empty($path[$i + 2])){
                        self::$methodName = $path[$i + 2];
                    }
                    $i += 2;
                }
                $i++;
            }

            self::_paramsProcess();

    //        var_dump(self::$controllerName);
            $methodName = self::getMethodName();
            $controllerInstance = new self::$controllerName();
            call_user_func_array(array($controllerInstance, $methodName), self::getParamsArray());
//            $controllerInstance->$methodName();

        }

        static public function getControllerName()
        {
            return self::$controllerName;
        }

        static public function getMethodName()
        {
            return self::$methodName;
        }

        static public function getParamsArray()
        {
            return self::$paramsArray;
        }

        //get参数处理
        private function _paramsProcess()
        {
            if(empty(self::$paramsArray)){
                return;
            }
            foreach (self::$paramsArray as $key=>$value){
                $_GET[$key] = $value;
            }
        }
    }
