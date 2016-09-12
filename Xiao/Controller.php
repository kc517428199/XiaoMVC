<?php
    namespace Xiao;

    abstract class Controller {

        private $view;

        public function __construct()
        {
            $this->view = new View();
        }

        public function assign($name, $value)
        {
            $this->view->assign($name, $value);
        }

        public function display($url)
        {
            $this->view->display($url);
        }

    }