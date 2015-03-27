<?php

class indexer {

    private $root_dir;
    private $parsers = [];
    private $data_processor;

    function __construct($root_dir, $data_processor) {

        $this->root_dir = $root_dir;
        $this->data_processor = $data_processor;

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
                        $row = $parser->get_row();
                        
                        $this->data_processor->insert($row);

                    }

                } 

            }

        }

        $d->close();

    }

}