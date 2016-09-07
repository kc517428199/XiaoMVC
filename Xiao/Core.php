<?php
    namespace Xiao;

    /**
     * Core类
     *
     * @author  xiaokc
     */
    class Core {
        static public function start()
        {
            // 注册AUTOLOAD方法
            spl_autoload_register('Xiao\Core::autoload');

            // 设定错误和异常处理
            register_shutdown_function('Xiao\Core::fatalError');
            set_error_handler('Xiao\Core::appError');
            set_exception_handler('Xiao\Core::appException');

            $dispatch = new Dispatcher;
            $dispatch->dispatch();
        }

        static public function autoload($class)
        {
            $className = $class;
            $temp = explode('\\', $className);
            if($temp[0] == 'Xiao'){
                $className = preg_replace('/^Xiao\\\/', '', $className);
            }

            if(file_exists(XIAO_PATH.$className.EXT)){
                require_once(XIAO_PATH.$className.EXT);
            }else{
                $fileDir = array('controller', 'model', 'view');
                foreach ($fileDir as $key=>$value){
                    if(file_exists(APP_PATH.$value.DIRECTORY_SEPARATOR.$className.EXT)) {
                        require_once(APP_PATH.$value.DIRECTORY_SEPARATOR.$className.EXT);
                        break;
                    }
                }
            }

        }

        static public function fatalError()
        {
//            var_dump('close');
        }

        static public function appError($errno, $errstr, $errfile, $errline)
        {
//            var_dump($errno, $errstr, $errfile, $errline);
            $err = 'errno:'.$errno.', errstr:'.$errstr.', errfile:'.$errfile.', errline:'.$errline;
            $logSystem = new \Xiao\Log('system', __FILE__, $err);
            $logSystem->log();
        }

        static public function appException($e)
        {
            var_dump($e);
            $logSystem = new \Xiao\Log('system', __FILE__, json_decode($e));
            $logSystem->log();
        }
    }