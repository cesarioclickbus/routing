<?php

require_once('../../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$memcache_obj = memcache_connect('localhost', 11211);

//read vertices memcash

print_r("Vertices------------------------------------\n");
$vertices = memcache_get($memcache_obj, 'vertices');

foreach ($vertices as $vertex) {
  print_r($vertex." ");
}
echo "\n";
//read cities memcash

print_r("cities------------------------------------\n");
$cities = memcache_get($memcache_obj, 'cities');

foreach ($cities as $c) {
  print_r($c." ");
}
 echo "\n";
//read cities memcash

print_r("cityFromId------------------------------------\n");
$cityFromId = memcache_get($memcache_obj, 'cityFromId');

print_r($cityFromId);

 echo "\n";

//read neighbours memcash
$neighbours = array();

foreach ($vertices as $vertex) {
  print_r("Vertice ".$vertex);
  echo "\n";
  $neighbours[$vertex] = memcache_get($memcache_obj, 'neighbours_'.trim($vertex));
  print_r('neighbours_'.trim($vertex).':'.$neighbours[$vertex]);
  foreach ($neighbours[$vertex] as $arr) {
    print_r($vertex."->".$arr["end"].":".$arr["cost"]." == ".memcache_get($memcache_obj,'cost_'.$vertex.'_'.$arr["end"])."\n");
  }
  echo "\n";
}

?>
