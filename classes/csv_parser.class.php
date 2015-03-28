<?php

class csv_parser {

    private $year;
    private $month;
    private $file;
    private $delimiter = ';';
    private $csv_format = [
        _FORMAT_DATA_DAY,
        _FORMAT_DATA_TIME,
        _FORMAT_DATA_TEMPERATURE,
        _FORMAT_DATA_WIND_DIRECTION,
        _FORMAT_DATA_WIND_SPEED,
        _FORMAT_DATA_WEATHER,
        _FORMAT_DATA_CLOUDS,
        _FORMAT_DATA_VISIBILITY,
        _FORMAT_DATA_HUMIDITY,
        _FORMAT_DATA_PRESSURE
    ];

    function set_file($file_path) {

        mb_internal_encoding('UTF-8');
        $this->file = fopen($file_path, 'r');

        $this->parse_file_data($file_path);

    }

    private function parse_file_data($file_path) {

        $filename = pathinfo($file_path, PATHINFO_FILENAME);
        $pos = strpos($filename, '_');
        if ($pos !== false) {

            $date_str = substr($filename, $pos+1);
            $date_array = explode('-', $date_str);
            if (sizeof($date_array) == 2)
                list($this->year, $this->month) = $date_array;

        }

    }

    function get_row() {

        $line = fgets($this->file);
        if ($line !== false) {

            $line = trim($line);
            $ret = explode($this->delimiter, $line);
            $ret = $this->_format_output($ret);

        } else 
            $ret = false;

        return $ret;

    }

    private function _format_output($params) {

        $ret = [];

        foreach ($params as $key => $value) {
            
            if (isset($this->csv_format[$key])) {

                $format_key = $this->csv_format[$key];
                $ret[$format_key] = trim($value);

            }

        }

        if ($ret) {

            $ret[_FORMAT_DATA_YEAR] = $this->year;
            $ret[_FORMAT_DATA_MONTH] = $this->month;

        }

        return $ret;

    }

}