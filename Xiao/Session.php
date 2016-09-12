<?php
    namespace Xiao;

    /**
     * Session类
     *
     * @author  xiaokc
     */
    class Session {

        public function get($name)
        {
            if(empty($name)) return false;
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        }

        public function set($name, $value = null)
        {
            if(empty($name)) return false;

            if($value == null) {
                unset($_SESSION[$name]);
            } else {
                $_SESSION[$name] = $value;
            }
            return true;
        }
    }