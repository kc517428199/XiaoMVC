<?php
    namespace Xiao;

    /**
     * View类
     *
     * @author  xiaokc
     */
    class View {

        protected $variables = array();
        protected $_controller;

        public function __construct($controller)
        {
            $this->_controller = $controller;
        }

        public function __destruct()
        {

        }

        public function assign($name, $value)
        {
            $this->variables[$name] = $value;
        }

        public function display($url)
        {
            extract($this->variables);

            $file_view = APP_PATH.'view'.DIRECTORY_SEPARATOR.$this->_controller.DIRECTORY_SEPARATOR.$url.EXT;
            if(file_exists($file_view)) {
                include $file_view;
            } else {
                echo '模板'.$url.EXT.'文件不存在';
            }
        }
    }