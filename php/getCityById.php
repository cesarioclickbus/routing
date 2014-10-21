<?php

$cityFromId["a"] = "Sao Paulo";
$cityFromId["b"] = "Rio de Janeiro";
$cityFromId["c"] = "Sao Jose";
$cityFromId["d"] = "Santos";
$cityFromId["e"] = "Buzios";
$cityFromId["f"] = "Santa Fe";


if (isset($_GET['id']) && isset($cityFromId[$_GET['id']]))
	echo $cityFromId[$_GET['id']];

?>