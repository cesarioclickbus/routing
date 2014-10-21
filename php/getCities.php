<?php

$cities = array("Sao Paulo","Rio de Janeiro", "Sao Jose", "Santos", "Buzios", "Santa Fe");

if ($_GET['q'] == null)
	echo json_encode($cities);
else
	echo json_encode(array_values(array_filter($cities,function ($item) {
		return strpos(strtolower($item),strtolower($_GET['q'])) !== false;
})));

?>
