<?php
    namespace Xiao;

    /**
     * Mongo类
     *
     * @author  xiaokc
     */
    class Mongo  {
        static public $db; //db对象
        static public $dbname;
        static public $collection;
        private $_dataInfo = array(); //连接信息
        protected $options; //操作对象

        private $methods = array('field', 'table', 'where', 'group', 'order', 'limit'); // 链式操作方法

//        private $selectSql = 'SELECT %FIELD% FROM %TABLE% %JOIN% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';


        public function __construct($data)
        {
            $this->_dataInfo = $data;
            $this->loadDatabase();
        }
        
        public function __call($name, $arguments)
        {
            if(in_array($name, $this->methods)) {
                $this->options[$name] = $arguments;
                return $this;
            }elseif(in_array($name, array('join'))){
                $this->options[$name][] = $arguments[0];
                return $this;
            }
            return false;
        }

        public function loadDataBase()
        {
            $this->db = new \MongoClient($this->_dataInfo['dns']);

            $db = $this->getDbName();
            $table = $this->getTableName();
            $this->collection = $this->db->$db->$table;
        }

        public function find()
        {
            foreach($this->methods as $key=>$value) {
                if(!isset($this->options[$value])) continue;
                $select_field = array();$select_where = array();

                switch ($value) {
                    case 'field':
                        $select_field = $this->options[$value][0];
                        break;
                    case 'where':
                        $select_where = $this->options[$value][0];
                        break;
                    case 'table':
                        $db = $this->getDbName();
                        $table = $this->getTableName();
                        $this->collection = $this->db->$db->$table;
                        break;
                }
            }

            if(empty($select_where)) {
                return false;
            }

            try {
                $data = $this->collection->find($select_where, $select_field);
                foreach ($data as $value) {
                    $result[] = $value;
                }
                return $result;
            }catch (\Exception $e) {
                return false;
            }
        }

        public function findAll()
        {
            if(!empty($this->options['table'][0])) {
                $db = $this->getDbName();
                $table = $this->getTableName();
                $this->collection = $this->db->$db->$table;
            }

            try {
                $data = $this->collection->find();
                foreach ($data as $value) {
                    $result[] = $value;
                }
                return $result;
            }catch (\Exception $e) {
                return false;
            }
        }

        public function save($data)
        {
            if(!is_array($data)) {
                return false;
            }

            try {
                return $this->collection->insert($data);
            }catch (\Exception $e) {
                return false;
            }
        }

        public function update($data)
        {
            if(!is_array($data)) {
                return false;
            }
            if(empty($this->options['where'][0]) || !is_array($this->options['where'][0])) {
                return false;
            }

            try {
                return $this->collection->update($this->options['where'][0], array('$set'=>$data));
            }catch (\Exception $e) {
                return false;
            }
        }

        public function delete($options = array())
        {
            try {
                return $this->collection->remove($this->options['where'][0], $options);
            }catch (\Exception $e) {
                return false;
            }
        }

        public function getTableName()
        {
            if(!isset($this->options['table'][0])) {
                $name = get_class($this);
                if ($pos = strrpos($name, '\\')) { //有命名空间
                    $table = $this->_dataInfo['prefix'] . substr($name, $pos + 1);
                } else {
                    $table = $this->_dataInfo['prefix'] . $name ;
                }
                $table = substr($table, 0, strrpos($table, 'Model'));
                $table = trim(preg_replace('/[A-Z]/', '_\\0', $table), '_');
            } else {
                $table = $this->_dataInfo['prefix'] . (string)$this->options['table'][0];
            }
            return strtolower($table);
        }

        public function getDbName()
        {
            $this->dbname = substr($this->_dataInfo['dns'], strrpos($this->_dataInfo['dns'], '/')+1);
            return $this->dbname;
        }
    }