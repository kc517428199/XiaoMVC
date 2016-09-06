<?php
    namespace Xiao;

    class Core {
//    require_once ('../vendor/xiaokc/xiaomvc/Xiao/Dispatcher.php');

        static public function start(){
            // 注册AUTOLOAD方法
            spl_autoload_register('Xiao\Core::autoload');

            // 设定错误和异常处理
            register_shutdown_function('Xiao\Core::fatalError');
            set_error_handler('Xiao\Core::appError');
            set_exception_handler('Xiao\Core::appException');

            $dispatch = new Dispatcher;
            $dispatch->dispatch();
        }

        static public function autoload($class){

            $className = preg_replace('/^Xiao\\\/', '', $class);
            require_once(XIAO_PATH.$className.EXT);

        }

        static public function fatalError(){
            var_dump('close');
        }

        static public function appError($errno, $errstr, $errfile, $errline){
            var_dump($errno, $errstr, $errfile, $errline);
        }

        static public function appException($e){
            var_dump($e);
        }
    }