<?php

$memcache_obj = memcache_connect('localhost', 11211);
$idFromCity = memcache_get($memcache_obj, 'idFromCity');

if (isset($_GET['city']) && isset($idFromCity[$_GET['city']]))
	echo $idFromCity[$_GET['city']];

?>