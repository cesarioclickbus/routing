<?php

require_once('../../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$csv = new parseCSV();
$csv->auto('../../routes-br.csv');

$memcache_obj = memcache_connect('localhost', 11211);

//flush
memcache_flush($memcache_obj);

//blank arrays
$vertices = array();
$neighbours = array();
$cityFromId  = array();
$idFromCity  = array();
$cities = array();

//populate arrays
for($i = 0; $i < count($csv->data); $i++)
  if ($csv->data[$i]['google_api'] == 1 && $csv->data[$i]['distance'] > 0) {
	
	if (!in_array($csv->data[$i]['origin_place_id'], $vertices)) array_push($vertices, $csv->data[$i]['origin_place_id']);
	if (!in_array($csv->data[$i]['destination_place_id'], $vertices)) array_push($vertices, $csv->data[$i]['destination_place_id']);
	if (!in_array($csv->data[$i]['origin_place_name'], $cities)) array_push($cities, $csv->data[$i]['origin_place_name']);
	if (!in_array($csv->data[$i]['destination_place_name'], $cities)) array_push($cities, $csv->data[$i]['destination_place_name']);
	
    $neighbours[$csv->data[$i]['origin_place_id']][] = array("end" => $csv->data[$i]['destination_place_id'], "cost" => $csv->data[$i]['distance']);  
	memcache_set($memcache_obj, 'cost_'.$csv->data[$i]['origin_place_id'].'_'.$csv->data[$i]['destination_place_id'], $csv->data[$i]['distance'], 0, 0);
	
	if (!isset($cityFromId[$csv->data[$i]['origin_place_id']])) $cityFromId[$csv->data[$i]['origin_place_id']] = $csv->data[$i]['origin_place_name'];
	if (!isset($cityFromId[$csv->data[$i]['destination_place_id']])) $cityFromId[$csv->data[$i]['destination_place_id']] = $csv->data[$i]['destination_place_name'];
	if (!isset($idFromCity[$csv->data[$i]['origin_place_name']])) $idFromCity[$csv->data[$i]['origin_place_name']] = $csv->data[$i]['origin_place_id'];
	if (!isset($idFromCity[$csv->data[$i]['destination_place_name']])) $idFromCity[$csv->data[$i]['destination_place_name']] = $csv->data[$i]['destination_place_id'];
}
$cityFromId = array_unique($cityFromId);
$idFromCity = array_unique($idFromCity);

//populate memcash
memcache_set($memcache_obj, 'vertices', $vertices, 0, 0);
memcache_set($memcache_obj, 'cities', $cities, 0, 0);
memcache_set($memcache_obj, 'cityFromId', $cityFromId, 0, 0);
memcache_set($memcache_obj, 'idFromCity', $idFromCity, 0, 0);

foreach ($vertices as $vertex) {
  memcache_set($memcache_obj, 'neighbours_'.$vertex, $neighbours[$vertex], 0, 0);
}

?>
