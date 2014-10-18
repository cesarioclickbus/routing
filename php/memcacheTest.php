<?php

$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");

$key = md5('List 9lessons Demos'); // Unique Words
$cache_result = array();
$cache_result = $memcache->get($key); // Memcached object

if($cache_result)
{
// Second User Request
$demos_result=$cache_result;
}
else
{
$demos_result = array(
                    array("a", "b", 7),
                    array("a", "c", 9),
                    array("a", "f", 14),
                    array("b", "c", 10),
                    array("b", "d", 15),
                    array("c", "d", 11),
                    array("c", "f", 2),
                    array("d", "e", 6),
                    array("e", "f", 9)
               );

$memcache->set($key, $demos_result, 0, 1200);
// 1200 Seconds
}

// Result
foreach($demos_result as $row)
{
echo '<a href='.$row[0].'>'.$row[1].'</a>';
}

?>
