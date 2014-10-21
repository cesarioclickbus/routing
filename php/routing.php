<?php
function cost($graph_array, $origin, $dest) {
	foreach ($graph_array as $edge) 
		if ($edge[0] == $origin && $edge[1] == $dest) 
			return $edge[2];
	return 0;
}

function dijkstra($graph_array, $origin, $dest, $maxDist) {

	$cityFromId["a"] = "Sao Paulo";
	$cityFromId["b"] = "Rio de Janeiro";
	$cityFromId["c"] = "Sao Jose";
	$cityFromId["d"] = "Santos";
	$cityFromId["e"] = "Buzios";
	$cityFromId["f"] = "Santa Fe";


    $vertices = array();
    $neighbours = array();
    foreach ($graph_array as $edge) {
        array_push($vertices, $edge[0], $edge[1]);
        $neighbours[$edge[0]][] = array("end" => $edge[1], "cost" => $edge[2]);
    }
    $vertices = array_unique($vertices);

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

        if (isset($neighbours[$u])) {
            foreach ($neighbours[$u] as $arr) {
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
									"cost" =>cost($graph_array,$previous[$u],$u))
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

$path = dijkstra($graph_array, $_GET['originId'], $_GET['destId'],$_GET['maxDist']);

echo json_encode($path);
