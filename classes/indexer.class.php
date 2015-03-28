<?php

class indexer {

    private $root_dir;
    private $parsers = [];
    private $weather_model;

    function __construct($root_dir, $weather_model) {

        $this->root_dir = $root_dir;
        $this->weather_model = $weather_model;

    }

    function set_parser($ext, $obejct) {

        $ext = strtolower($ext);
        $this->parsers[$ext] = $obejct;

    }

    function run() {

        $skip_names = ['.', '..'];

        $d = dir($this->root_dir);
        while (false !== ($entry = $d->read())) {

            if (!in_array($entry, $skip_names)) {

                $file_path = $this->root_dir.$entry;
                if (is_file($file_path) && is_readable($file_path)) {

                    $ext = pathinfo($entry, PATHINFO_EXTENSION);
                    $allowed_ext = array_keys($this->parsers);

                    if (in_array($ext, $allowed_ext)) {

                        $parser = $this->parsers[$ext];
                        $parser->set_file($file_path);
                        while (false !== ($row = $parser->get_row())) {
                        
                            $this->weather_model->set_new_data($row);
                            $this->weather_model->insert();

                        }

                    }

                } 

            }

        }

        $d->close();

    }

}