<?php

class db {

    protected $data;
    protected $db;
    protected $collection;

    function __construct() {

        $url = 'mongodb://'._DB_USERNAME.':'._DB_PASSWORD.'@'._DB_HOST.':'._DB_PORT.'/'._DB_NAME;
        $db = new MongoClient($url);

        $this->db = $db->{_DB_NAME};

    }

    function insert() {
        return $this->_insert($this->data);
    }

    function select($where = []) {

        $ret = [];

        if ($where) {

            $prepared_where = $where;
            $cursor = $this->db->{$this->collection}->find($prepared_where);

        } else 
            $cursor = $this->db->{$this->collection}->find();

        while($data = $cursor->getNext())
            $ret[] = $data;

        return $ret;

    }

    function avg($calcilate_field, $group_fields, $where = []) {

        $results = $this->_aggregate('avg', $calcilate_field, $group_fields, $where);
        return $results;

    }

    function sum($calcilate_field, $group_fields, $where = []) {

        $results = $this->_aggregate('sum', $calcilate_field, $group_fields, $where);
        return $results;

    }

    function min($calcilate_field, $group_fields, $where = []) {

        $results = $this->_aggregate('min', $calcilate_field, $group_fields, $where);
        return $results;

    }

    function max($calcilate_field, $group_fields, $where = []) {

        $results = $this->_aggregate('max', $calcilate_field, $group_fields, $where);
        return $results;

    }

    protected function _aggregate($method, $calcilate_field, $group_fields, $where) {

        $group_fields_formated = [];
        foreach ($group_fields as $value)
            $group_fields_formated[$value] = '$'.$value;

        $opt = [];
        if ($where)
            $opt[] = ['$match' => $where];

        $opt[] = ['$group' => [
            "_id" => $group_fields_formated,
            $method => [
                '$'.$method => '$'.$calcilate_field
            ]
        ]];

        $result = $this->db->{$this->collection}->aggregate($opt);

        $ret = [];
        foreach ($result['result'] as $data) {
            
            $row = $data['_id'];
            $row[$method] = $data[$method];

            $ret[] = $row;

        }

        return $ret;

    }

    protected function _insert($data) {

        $id = $this->db->{$this->collection}->save($data);
        return $id; 

    }

}