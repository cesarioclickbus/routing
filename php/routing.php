<?php

function dijkstra($origin, $dest, $maxDist) {

	$memcache_obj = memcache_connect('localhost', 11211);

    $vertices = memcache_get($memcache_obj, 'vertices');	
    $cityFromId = memcache_get($memcache_obj, 'cityFromId');

    foreach ($vertices as $v) {
        $cost[$v] = INF;
		$dist[$v] = INF;
        $previous[$v] = NULL;
		$addedToQ[$v] = 0;
    }

    $cost[$origin] = 0;
	$dist[$origin] = 0;
    $Q = array($origin);
	$addedToQ[$origin] = 1;
    while (count($Q) > 0) {

        // TODO - Find faster way to get minimum
        $min = INF;
        foreach ($Q as $v){
            if ($cost[$v] < $min && $dist[$v] < $maxDist) {
                $min = $cost[$v];
                $u = $v;
            }
        }

        $Q = array_diff($Q, array($u));
        if ($min == INF or $u == $dest) {
            break;
        }
		
		$neighbours_u = memcache_get($memcache_obj, 'neighbours_'.$u);

        if (isset($neighbours_u)) {
            foreach ($neighbours_u as $arr) {
                $alt = $cost[$u] + $arr["cost"];
                if ($alt < $cost[$arr["end"]]) {
                    $cost[$arr["end"]] = $alt;
					$dist[$arr["end"]] = $dist[$u] + 1;
                    $previous[$arr["end"]] = $u;
                }
				if ($addedToQ[$arr["end"]] == 0){
					array_push($Q, $arr["end"]);
					$addedToQ[$arr["end"]] = 1;
				}
            }
        }
    }
    $path = array();
    $u = $dest;
    while (isset($previous[$u])) {
        array_unshift($path, array( "oridinId" => $previous[$u],
									"originName" => $cityFromId[$previous[$u]],
									"destId" => $u,
									"destName" => $cityFromId[$u],
									"cost" =>memcache_get($memcache_obj,'cost_'.$previous[$u].'_'.$u)
									);
        $u = $previous[$u];
    }
    return $path;
}

$graph_array = array(
                    array("a", "b", 7),
                    array("a", "c", 9),
                    array("a", "f", 14),
                    array("b", "c", 10),
                    array("b", "d", 15),
                    array("c", "d", 11),
                    array("c", "f", 2),
                    array("d", "e", 6),
                    array("e", "f", 9),
					array("a", "e", 90)
               );

$path = dijkstra($_GET['originId'], $_GET['destId'],$_GET['maxDist']);

echo json_encode($path);
