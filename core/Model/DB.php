<?php

class DB {
    # Хранит в себе подключение к БД
    private $_connect = null;
    private $_where = [];
    private $_select = ['*'];

    private $_query = '';
    protected$_table = '';
    # Конструктор, который принимает параметры подключения к БД и получает имя таблицы
    public function __construct() {
      
        $this->_connect = new mysqli('192.168.199.14', 'test', 'test', 'test');

        $class = explode('\\', static::class);
        $this->_table = $class[count($class) - 1];
    }

    # data - массив, ключ - название столбца в таблице
    # Параметры передаются в виде массива, где ключ - название столбца в таблице, а значение - значение, которое нужно вставить
    public function insert($data) {
        $values = [];
        $columns =  [];
        foreach($data as $column => $value) {
            $columns[] = "`$column`";
            $values[] =  "'" . $this->_connect->real_escape_string($value) .   "'";
        }
        $this->_query =  'insert into ' . $this->_table .
            ' (' . implode(',', $columns) . ') values (' . implode(',', $values) . ')';
        $this->execute();
    }
    # Запрос на получение всех данных в виде ассоциативного массива
    # Возвращает массив всех строк из таблицы
    public function all() {
        $this->_query= 'select * from ' . $this->_table;
        $rows = $this->execute();
        return $rows->fetch_all(MYSQLI_ASSOC);
    }

    # Метод указывает какие столбцы выбрать, перезаписыванием изначальной * на переданные значения
    public function select($columns) {
        if (is_array($columns)) {
            $this->_select = $columns;
        } else if (is_string($columns)) {
            $this->_select = explode(',', $columns);
        }

        return $this;
    }

    public function where($where)
    {
        # Проверка на то, что передан массив
        # Если передан массив, то проверяем его на наличие вложенных массивов
        if(is_array($where)) {
            if(is_array($where[0])){

                foreach($where as $w){
                    # Если вложенный массив, то проверяем его на наличие трех элементов и присваиваем их переменным
                    list($column, $operator, $val) = $w;
                    # В зависимости от оператора, который передан в массив, выполняем разные действия
                    # Если оператор IN, то проверяем, что передан массив и если это так, то перебираем его и экранируем каждый элемент
                    switch ($operator) {
                        case 'IN':
                            if(is_array($val)) {
                                foreach($val as $k => $v){
                                    $val[$k] = $this->injection($v);
                                }
                                # Если передали строку, то разбиваем ее на массив
                            } else {
                                $val = explode(',', $val);
                                foreach($val as $k => $v){
                                    $val[$k] = $this->injection($v);
                                }
                                # Собираем запрос в виде id IN (1,2,3)
                                $this->_where[] = [$column, 'IN', "('" . implode('\', \'', $val) . "')"];
                            }
                            break;
                        # Во всех остальных операторах экранируем значения и собираем запрос в виде id = '1'
                        default:
                            $this->_where[]  = [$column, $operator, "'"  . $this->injection($val) . "'"];
                            break;
                    }
                }
                # Переменовываем массив _where, чтобы он содержал только строки запроса
                $this->_where = $where;
            } else {
                # Если передан массив без вложенных массивов, то перебираем его и экранируем значения
                foreach($where as $key => $value) {
                    $this->_where[] = [$key, '=', "'"  . $this->injection($value) . "'"] ;
                }
            }
        }

        return $this;

    }

    public function injection($value)
    {
        return  $this->_connect->real_escape_string($value);
    }

    public function get() {
        # Формируем запрос на выборку данных
        $this->_query = 'select ' . implode(', ', $this->_select);
        $this->_query .= ' from ' . $this->_table;
        # Если есть условия where, то добавляем их к запросу
        if(count($this->_where)>0) {
            $this->_query .= ' where ';
            foreach($this->_where as $k => $where)
                $this->_where[$k] = implode(' ', $where);
            $this->_query .= implode(' and ', $this->_where);
        }
        # Выполняем запрос и получаем результат
        $rows = $this->execute();
        return $rows->fetch_all(MYSQLI_ASSOC);
    }
    # Дёргаем метод query у mysqli, который выполняет запрос к БД
    public function execute() {
        return $this->_connect->query($this->_query);
    }
    # Деструктор, который закрывает соединение с БД при уничтожении объекта
    public function __destruct() {
        $this->_connect->close();
    }

}