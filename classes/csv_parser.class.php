<?php

class csv_parser {

    private $file;
    private $delimiter = ';';
    private $format = [
        'day',
        'time',
        'temperature',
        'wind_direction',
        'wind_speed',
        'weather',
        'clouds',
        'visibility',
        'humidity',
        'pressure'
    ];

    function set_file($file_path) {

        mb_internal_encoding('UTF-8');
        $this->file = fopen($file_path, 'r');

    }

    function get_row() {

        $line = fgets($this->file);
        $params = explode($this->delimiter, $line);

        return $this->_format_output($params);

    }

    private function _format_output($params) {

        $ret = [];

        foreach ($params as $key => $value) {
            
            if (isset($this->format[$key])) {

                $format_key = $this->format[$key];
                $ret[$format_key] = $value;

            }

        }

        return $ret;

    }

}