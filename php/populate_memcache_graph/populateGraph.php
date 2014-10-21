<?php

require_once('../../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$csv = new parseCSV();
$csv->auto('../..//routes-br.csv');

$memcache_obj = memcache_connect('localhost', 11211);

//flush
memcache_flush($memcache_obj);

//blank arrays
$vertices = array();
$neighbours = array();

//populate arrays
for($i = 0; $i < count($csv->data); $i++)
  if ($csv->data[$i]['google_api'] == 1 && $csv->data[$i]['distance'] > 0) {
    array_push($vertices, $csv->data[$i]['origin_place_id'], $csv->data[$i]['destination_place_id']);
    $neighbours[$csv->data[$i]['origin_place_id']][] = array("end" => $csv->data[$i]['destination_place_id'], "cost" => $csv->data[$i]['distance']);          //print_r($csv->data[$i]['origin_place_id']."->".$csv->data[$i]['destination_place_id'].":".$csv->data[$i]['distance']);
}
$vertices = array_unique($vertices);

//populate memcash
memcache_set($memcache_obj, 'vertices', $vertices, 0, 0);

foreach ($vertices as $vertex) {
  memcache_set($memcache_obj, 'neighbours_'.$vertex, $neighbours[$vertex], 0, 0);
}

?>
