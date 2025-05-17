<?php

class DB
{
    // Хранить подключение к бд
    private $_connect = null;
    private $_where = [];
    private $_select = ['*'];
    private $_query = '';
    protected $_table = '';

    public function __construct()
    {
        $this->_connect = new mysqli('192.168.199.14', 'test', 'test', 'test');
        $class = explode('//', static::class);
        $this->_table = $class[count($class) - 1];

    }

    public function insert($data)
    {
        $values = [];
        $columns = [];
        foreach ($data as $column => $value) {
            $columns[] = "`$column`";
            $values[] = "'" . $this->_connect->real_escape_string($value) . "'";
        }
        $this->_query = 'INSERT INTO' . $this->_table .
            ' (' . implode(', ', $columns) . ') VALUES (' . implode(',', $values) . ')';
        $this->execute();
    }
    public function all()
    {
        $this->_query = 'SELECT * FROM' . $this->_table;
        $rows = $this->execute();
        return $rows->fetch_all(MYSQL_ASSOC);
    }

    public function execute()
    {
        return $this->_connect->query($this->_query);
    }

    public function __destruct()
    {
        $this->_connect->close();
    }

    public function select($columns)
    {
        if (is_array($columns)) {
            $this->_select = $columns;
        } elseif (is_string($columns)) {
            $this->_select = explode(', ', $columns);
        }
        return $this;

    }

    public function where($where)
    {
        if (is_array($where)) {
            if (is_array($where[0])) {
                foreach ($where as $w) {
                    list($column, $operator, $val) = $w;
                    switch ($operator) {
                        case 'IN':
                            if (is_array($val)) {
                                foreach ($val as $k => $v) {
                                    $val[$k] = $this->injection($v);
                                }
                                $this->_where[] = [$column, 'IN', "('" . implode('\', \'', $val) . "')"];
                            } else {
                                $val = explode(',', $val);
                                foreach ($val as $k => $v) {
                                    $val[$k] = $this->injection($v);
                                }
                                $this->_where[] = [$column, 'IN', "('" . implode('\', \'', $val) . "')"];
                            }
                            break;

                        default:
                            $this->_where = [$column, '=', "'" . $this->insert($val) . "'"];
                            break;
                    }
                }
            } else {
                foreach ($where as $key => $value) {
                    $this->_where[] = [$key, '=', "'" . $this->injection($value) . "'"];
                }
            }
        } elseif (is_string($where)) {
            $this->_where = $where;
        }

        return $this;
    }

    public function injection($value)
    {
        return $this->_connect->real_escape_string($value);
    }


    public function get()
    {
        $this->_query = 'SELECT ' . implode(', ', $this->_select);
        $this->_query .= ' FROM ' . $this->_table;
        if (count($this->_where) > 0) {
            $this->_query .= ' WHERE ';
            foreach ($this->_where as $k => $where) 
                $this->_where[$k] = implode(' ', $where);
            
            $this->_query .= implode(' AND ', $this->_where);
        }
        $rows = $this->execute();
        return $rows->fetch_all(MYSQLI_ASSOC);
    }

}