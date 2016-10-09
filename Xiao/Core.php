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
            register_shutdown_function('Xiao\Core::shutdown');
            set_error_handler('Xiao\Core::appError');
//            set_exception_handler('Xiao\Core::appException');

            $session_dir = new File();
            if(!is_dir(APP_SESSION) && !$session_dir->mkdirs(APP_SESSION)){
                Log::errorLog('error session dir', __FILE__);
            }
            @session_save_path(APP_SESSION);
            @session_start();

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

        static public function shutdown()
        {
//            var_dump('close');
        }

        static public function appError($errno, $errstr, $errfile, $errline)
        {
            $err = 'errno:'.$errno.', errstr:'.$errstr.', errfile:'.$errfile.', errline:'.$errline;
//            echo $err,"\n";
            Log::errorLog($err, __FILE__);
        }

        static public function appException($e)
        {
//            var_dump($e);
            Log::log('exception', json_decode($e), __FILE__, 'exception');
        }
    }