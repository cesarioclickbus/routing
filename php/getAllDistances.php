<?php

require_once('../vendor/parsecsv-0.3.2/parsecsv.lib.php');

function getDistance($origin, $destination, $i) {
  $q = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=".str_replace(" ", "+", $origin)."&destinations=".str_replace(" ", "+",$destination)."&mode=driving&sensor=false";
  $json = file_get_contents($q);
  $details = json_decode($json, TRUE);
  if ($details[status] == "OVER_QUERY_LIMIT") exit("terminou na linha ".$i);
  return $details[rows][0][elements][0][distance][value]/1000;
}

$csv = new parseCSV();
$csv->auto('../routes_br.csv');

//for($i =0; $i < count($csv->data); $i++){
for($i = 0; $i < 30; $i++)
  if ($csv->data[$i][google_api] == 1) {
    $csv->data[$i][distance] = getDistance($csv->data[$i][origin_place_name],$csv->data[$i][destination_place_name],$i);
    $csv->data[$i][google_api] = 1;
    $csv->save();
    print_r("saved ".$i."\n");
    if ($i%10 == 0) sleep(3);
  }
?>
done
