<?php
    namespace Xiao;

    /**
     * Cookie类
     *
     * @author  xiaokc
     */
    class Cookie {

        static private $config = array(
            'expire' => 86400,
            'path' => '/',
            'domain' => null,
            'secure' => null,
            'httponly' => true
        );

        public function get($name)
        {
            if(empty($name)) return false;

            $value = base64_decode($_COOKIE[$name]);

            $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
            $value = mcrypt_decrypt(MCRYPT_CAST_256, Config::config()->key, substr($value, 0, -$size), MCRYPT_MODE_CBC, substr($value, -$size));

            return $value;
        }

        static public function set($name, $value, $config = array())
        {
            if(empty($name)) return false;

            extract(array_merge(self::$config, $config));

            $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $value = mcrypt_encrypt(MCRYPT_CAST_256, Config::config()->key, $value, MCRYPT_MODE_CBC, $iv).$iv;
            $value = base64_encode($value);

            setcookie($name, $value, time()+$expire, $path, $domain, $secure, $httponly);
        }
    }