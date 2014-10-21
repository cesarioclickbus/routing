<?php

$memcache_obj = memcache_connect('localhost', 11211);
$cities = memcache_get($memcache_obj, 'cities');

if ($_GET['q'] == null)
	echo json_encode($cities);
else
	echo json_encode(array_values(array_filter($cities,function ($item) {
		return strpos(strtolower($item),strtolower($_GET['q'])) !== false;
})));

?>
