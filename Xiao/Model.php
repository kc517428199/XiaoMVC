<?php
    namespace Xiao;
    use Xiao;

    /**
     * Model类
     *
     * @author  xiaokc
     */
    class Model {
        static public $db; //db对象
        private $_dataInfo = array(); //连接信息
        protected $options; //操作对象

        private $methods = array('field', 'table', 'where', 'group', 'having', 'order', 'limit'); // 链式操作方法

        private $selectSql = 'SELECT %FIELD% FROM %TABLE% %JOIN% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';


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
            //pdo driver_options暂不考虑
            $this->db = new \PDO($this->_dataInfo['dns'], $this->_dataInfo['username'], $this->_dataInfo['password'], array());
            //未知 抛出错误和异常
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        public function find()
        {
            $select_field = '*';

            foreach(array_merge($this->methods, array('join')) as $key=>$value) {
                if(!isset($this->options[$value])) continue;
                $temp = array();

                switch ($value) {
                    case 'field':
                        $select_field = implode(',', $this->options[$value][0]);
                        break;
                    case 'where':
                        $select_where = ' WHERE '.$this->options[$value][0];
                        break;
                    case 'join':

                        foreach ($this->options[$value] as $key=>$value) {
                            $temp[] = $value;
                        }
                        $select_join = implode(' ', $temp);
                        break;
                    case 'group':
                        $select_group = ' GROUP BY '.implode(',', $this->options[$value][0]);
                        break;
                    case 'having':
                        $select_having = ' HAVING '.$this->options[$value][0];
                        break;
                    case 'order':
                        foreach ($this->options[$value][0] as $key=>$value) {
                            $temp[] = $key.' '.$value;
                        }
                        $select_order = ' ORDER BY '.implode(',' ,$temp);
                        break;
                    case 'limit':
                        $select_limit = ' LIMIT '.implode(',', $this->options[$value][0]);
                        break;
                }
            }

            $sql = str_replace(array('%FIELD%', '%TABLE%', '%WHERE%', '%JOIN%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%'),
                    array($select_field, $this->getTableName(), $select_where, $select_join, $select_group, $select_having, $select_order, $select_limit),
                    $this->selectSql);
            try {
                foreach ($this->db->query($sql) as $row) {
                    $result[] = $row;
                }
                return $result;
            } catch (\Exception $e) {
                return false;
            }
        }

        public function save($data)
        {
            if(!is_array($data)) {
                return false;
            }

            $columns = array_keys($data);
            $value = array_values($data);
            $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->getTableName(), implode(',', $columns), implode(',', $value));
            try {
                return $this->db->exec($sql) ? $this->db->lastInsertId() : false;
            }catch (\Exception $e) {
                return false;
            }
        }

        public function update($data)
        {
            if(!is_array($data)) {
                return false;
            }
            if(empty($this->options['where']) || !is_array($this->options['where'])) {
                return false;
            }

            foreach ($data as $key=>$value) {
                $columns[] = $key."='".$value."'";
            }
            foreach ($this->options['where'][0] as $key=>$value) {
                $v[] = $key."='".$value."'";
            }

            $sql = sprintf("UPDATE %s SET %s WHERE %s", $this->getTableName(), implode(',', $columns), implode(',', $v));
            try {
                return $this->db->exec($sql);
            }catch (\Exception $e) {
                return false;
            }
        }

        public function delete($id = NULL)
        {
            if(!is_null($id)) {
                $columns[] = "id='".(int)$id."'";
            } else {
                if(empty($this->options['where']) || !is_array($this->options['where'])) {
                    return false;
                }
                foreach ($this->options['where'][0] as $key=>$value) {
                    $columns[] = $key."='".$value."'";
                }
            }

            $sql = sprintf("DELETE FROM %s WHERE %s", $this->getTableName(), implode(',', $columns));
            try {
                return $this->db->exec($sql);
            }catch (\Exception $e) {
                return false;
            }
        }

        public function getTableName()
        {
            return isset($this->options['table'][0]) ? '`' . (string)$this->options['table'][0] . '`' : '`' . $this->_dataInfo['table'] . '`';
        }


    }