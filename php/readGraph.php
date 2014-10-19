<?php

require_once('../vendor/parsecsv-0.3.2/parsecsv.lib.php');

$memcache_obj = memcache_connect('localhost', 11211);

//read vertices memcash

$vertices = memcache_get($memcache_obj, $key);

foreach ($vertices as $vertex) {
  print_r($vertex." ");
}
print_r("\n");

//read neighbours memcash
$neighbours = array();

foreach ($vertices as $vertex) {
  print_r("-> ".$vertex."\n");
  $neighbours[$vertex]] = memcache_get($memcache_obj, 'neighbours_'.$vertex);
  foreach ($neighbours[$vertex] as $arr) {
    print_r($vertex."->".$arr["end"].":".$arr["cost"]."\n");
  }
}

?>
