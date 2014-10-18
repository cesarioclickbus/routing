<?php

require_once('../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$csv = new parseCSV();
$csv->auto('../routes_br.csv');


print_r($csv->data[0]);
print_r($csv->data[1]);
print_r($csv->data[2]);
print_r(count($csv->data));


?>
