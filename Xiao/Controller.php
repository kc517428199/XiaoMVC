<?php
    namespace Xiao;

    abstract class Controller {

//        protected $assign;
//        protected $display;
        private $view;

        public function __construct($controller)
        {
            $this->view = new View($controller);
        }

        public function assign($name, $value)
        {
            $this->view->assign($name, $value);
        }

        public function display($url)
        {
            $this->view->display($url);
        }

        public function __get($name)
        {
            echo "Getting '$name'\n";
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            }

            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
            return null;
        }
    }