<?php
    namespace Xiao;

    /**
     * Cache类
     *
     * @author  xiaokc
     */
    class Cache {

        static public function get($name)
        {
            $filePath = APP_CACHE . self::_nameToFilename($name);
            $file = new \Xiao\File($filePath);
            $data = $file->read();

            if(!$data) return false;

            $content = json_decode($data, true);
            if(time() > $content['mktime']) {
                $file->delete();
                return false;
            }
            return isset($content['content']) ? unserialize($content['content']) : false;
        }

        static public function set($name, $value, $mktime = 300)
        {
            $filePath = APP_CACHE . self::_nameToFilename($name);
            $content = array(
                'mktime' => time() + $mktime,
                'content' => serialize($value)
            );

            $file = new \Xiao\File($filePath);
            $result = $file->write(json_encode($content), 'w');
            return $result ? true : false;
        }

        static public function delete($name)
        {
            $filePath = APP_CACHE . self::_nameToFilename($name);
            $file = new \Xiao\File($filePath);
            $result = $file->delete();
            return $result ? true : false;
        }

        static public function flush()
        {
            foreach (glob(APP_CACHE."*") as $fliename) {
                @unlink($fliename);
            }
            return true;
        }

        static public function flushMktime()
        {
            $dtime = time();
            foreach (glob(APP_CACHE."*") as $fliename) {
                $file = new \Xiao\File($fliename);
                $data = $file->read();
                if(!$data) continue;

                $content = json_decode($data, true);
                if($dtime > $content['mktime']) {
                    $file->delete();
                }
            }
            return true;
        }

        static private function _nameToFilename($name)
        {
            return hash('sha256', $name);
        }
    }