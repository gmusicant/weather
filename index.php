<?php

require_once('configs.php');
require_once('db.php');
require_once('classes/indexer.class.php');
require_once('classes/csv_parser.class.php');
require_once('classes/weather_model.class.php');


$csv = new csv_parser();
$weather_model = new weather_model();

do {
    //passthru('clear');
    echo "\nPlease choos a command:\n";
    echo "i - index wherer files from data folder\n".
         "a - define average variables\n".
         "c - calculate prediction %\n".
         "r - run all in one command\n".
         "e - exit\n";

    $input = strtolower(trim(fgets(STDIN)));

    switch ($input) {
        case 'i':
            $indexer = new indexer(_DATA_DIR, $weather_model);
            $indexer->set_parser('csv', $csv);
            $indexer->run();
            break;
        case 'a':
            //$weather_model->select(['year' => 2015, 'month' => 1]);
            break;
    }

} while ($input != 'e');
