<?php

require_once('../../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$csv = new parseCSV();
$csv->auto('../../routes-br2.csv');

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
for($i = 0; $i < count($csv->data); $i++){
  
	$origin_place_id = trim($csv->data[$i]['origin_place_id']);
	$destination_place_id = trim($csv->data[$i]['destination_place_id']);
	$origin_place_name = trim($csv->data[$i]['origin_place_name']);
	$destination_place_name = trim($csv->data[$i]['destination_place_name']);
	
	if (!in_array($origin_place_id, $vertices)) array_push($vertices, $origin_place_id);
	if (!in_array($destination_place_id, $vertices)) array_push($vertices, $destination_place_id);
	if (!in_array($origin_place_name, $cities)) array_push($cities, $origin_place_name);
	if (!in_array($destination_place_name, $cities)) array_push($cities, $destination_place_name);
	
	
	if (!isset($csv->data[$i]['distance'])) $cost = 1;
		else $cost = $csv->data[$i]['distance'];
  
    $neighbours[$origin_place_id][] = array("end" => $destination_place_id, "cost" => $cost);  
	memcache_set($memcache_obj, 'cost_'.$origin_place_id.'_'.$destination_place_id, $cost, 0, 0);
	
	if (!isset($cityFromId[$origin_place_id]) || is_null($cityFromId[$origin_place_id])) $cityFromId[$origin_place_id] = $origin_place_name;
	if (!isset($cityFromId[$destination_place_id]) || is_null($cityFromId[$destination_place_id])) $cityFromId[$destination_place_id] = $destination_place_name;
	if (!isset($idFromCity[$origin_place_name])  || is_null($idFromCity[$origin_place_name])) $idFromCity[$origin_place_name] = $origin_place_id;
	if (!isset($idFromCity[$destination_place_name])  || is_null($idFromCity[$destination_place_name])) $idFromCity[$destination_place_name] = $destination_place_id;
}
$cityFromId = array_unique($cityFromId);
$idFromCity = array_unique($idFromCity);

//populate memcash
memcache_set($memcache_obj, 'vertices', $vertices, 0, 0);
memcache_set($memcache_obj, 'cities', $cities, 0, 0);
memcache_set($memcache_obj, 'cityFromId', $cityFromId, 0, 0);
memcache_set($memcache_obj, 'idFromCity', $idFromCity, 0, 0);

foreach ($vertices as $vertex) {
	memcache_set($memcache_obj, 'neighbours_'.trim($vertex), $neighbours[$vertex], 0, 0);
}

?>
