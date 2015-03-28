<?php

class weather_model extends db {

    protected $collection = 'weacher';

    private $format = [

        _FORMAT_DATA_YEAR => _DATA_TYPE_INT,
        _FORMAT_DATA_MONTH => _DATA_TYPE_INT,
        _FORMAT_DATA_DAY => _DATA_TYPE_INT,
        _FORMAT_DATA_TIME => _DATA_TYPE_INT,
        _FORMAT_DATA_TEMPERATURE => _DATA_TYPE_INT,
        //_FORMAT_DATA_WIND_DIRECTION => _DATA_TYPE_SRT,
        _FORMAT_DATA_WIND_SPEED => _DATA_TYPE_INT,
        _FORMAT_DATA_WEATHER => _DATA_TYPE_SRT,
        _FORMAT_DATA_CLOUDS => _DATA_TYPE_INT,
        _FORMAT_DATA_VISIBILITY => _DATA_TYPE_INT,
        _FORMAT_DATA_HUMIDITY => _DATA_TYPE_INT,
        _FORMAT_DATA_PRESSURE => _DATA_TYPE_INT

    ];

    function set_new_data($data) {

        return $this->set_data($data, true);

    }

    function set_data($data, $flush_old_data = false) {

        foreach ($this->format as $key => $format) {
            
            if (isset($data[$key]))
                $value = $data[$key];
            else if (isset($this->data[$key]) && !$flush_old_data)
                $value = $this->data[$key];
            else
                $value = null;

            switch ($format) {
                case _DATA_TYPE_INT:
                    $this->data[$key] = (int)$value;
                    break;
                case _DATA_TYPE_SRT:
                    $this->data[$key] = (string)$value;
                    break;
            }


        }

    }

    function insert() {

        $data = $this->data;

        $data['_id'] = $data[_FORMAT_DATA_YEAR].'-'.$data[_FORMAT_DATA_MONTH].'-'.$data[_FORMAT_DATA_DAY].' '.$data[_FORMAT_DATA_TIME];

        $this->_insert($data);

    }

}