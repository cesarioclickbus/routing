<?php

$idFromCity["Sao Paulo"] = "a";
$idFromCity["Rio de Janeiro"] = "b";
$idFromCity["Sao Jose"] = "c";
$idFromCity["Santos"] = "d";
$idFromCity["Buzios"] = "e";
$idFromCity["Santa Fe"] = "f";


if (isset($_GET['city']) && isset($idFromCity[$_GET['city']]))
	echo $idFromCity[$_GET['city']];

?>