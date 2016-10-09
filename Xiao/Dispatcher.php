<?php

    namespace Xiao;

    /**
     * Dispatchar类
     *
     * @author  xiaokc
     */
    class Dispatcher
    {
        static private $controllerName = 'IndexController';
        static private $methodName = 'index';
        static private $paramsArray = array();

        public function dispatch()
        {
            if(php_sapi_name() == 'cli') {
                $this->_routeCli();
            } else {
                $this->_route();
            }

            $methodName = self::getMethodName();
            $controllerInstance = new self::$controllerName();
            call_user_func_array(array($controllerInstance, $methodName), self::getParamsArray());

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

        private function _controllerProcess($name)
        {
            return preg_replace_callback(array("/^([a-z])/", "/(-)([a-z])/"), array($this,'_urlProcess'), $name);
        }

        private function _route()
        {
            switch (DISPATCH_TYPE) {
                case '0':
                    isset($_GET['c'])&&self::$controllerName = self::_controllerProcess($_GET['c']) . 'Controller';
                    isset($_GET['m'])&&self::$methodName = $_GET['m'];
                    break;
                case '1':
                    //根域名识别
                    $isUrlRoot = true;
                    if(preg_match("/\/".APP_NAME."\//", urldecode($_SERVER['SCRIPT_NAME']))) {
                        $isUrlRoot = false;
                    }

                    $path = explode('/', urldecode($_SERVER['REQUEST_URI']));

                    $i = 0;
                    foreach ($path as $key => $value) {
                        if ($i != 0 && $key != 0 && (!isset($path[$i]) || empty($path[$i]))) {
                            break;
                        }

                        //参数
                        if ($key != $i) {
                            self::$paramsArray[$path[$i]] = $path[$i + 1];
                            $i++;
                        }
                        // controller method
                        if ($value == APP_NAME || $isUrlRoot === true) {
                            if (isset($path[$i + 1]) && !empty($path[$i + 1])) {
                                self::$controllerName = self::_controllerProcess($path[$i + 1]) . 'Controller';
                            }
                            if (isset($path[$i + 2]) && !empty($path[$i + 2])) {
                                self::$methodName = $path[$i + 2];
                            }
                            $i += 2;
                            //关闭一级识别
                            $isUrlRoot = false;
                        }
                        $i++;
                    }
//                    self::_paramsProcess(); //get参数处理
                    $this->_paramsProcess();
                    break;
            }

        }

        private function _routeCli()
        {
            global $argv;
            unset($argv[0]);

            if(isset($argv[1])) {
                self::$controllerName = self::_controllerProcess($argv[1]) . 'Controller';;
                unset($argv[1]);
            }
            if(isset($argv[2])) {
                self::$methodName = $argv[2];
                unset($argv[2]);
            }
            if(isset($argv[3])) {
                self::$paramsArray = $argv;
            }
        }

        private function _urlProcess($url)
        {
            return isset($url[2]) ? strtoupper($url[2]) : strtoupper($url[0]);
        }
    }
