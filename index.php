<?php

require_once('configs.php');
require_once('db.php');
require_once('classes/indexer.class.php');
require_once('classes/csv_parser.class.php');

$mongo = new db();
$csv = new csv_parser();

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
            $indexer = new indexer(_DATA_DIR, $mongo);
            $indexer->set_parser('csv', $csv);
            $indexer->run();
            break;
    }

} while ($input != 'e');
