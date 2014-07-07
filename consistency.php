<?php
function rehash($string) {
    return crc32($string);
}

function findPos($key,$keys) {
    global $pos2target;
    $v = rehash($key);
    $pos = search($keys,$v);
    return $pos2target[$keys[$pos]];
}

function search($result,$row) {
    $low = 0;
    $high = count($result)-1;
    while($low <= $high) {
	   $mid = floor(($high +  $low)/2);
       if($result[$mid] == $row) {
          return $mid;
       }elseif($result[$mid]>$row) {
          $high = $mid-1;
       }elseif($result[$mid]<$row) {
          if($mid + 1 < count($result) && $result[$mid+1] > $row) {
             return $mid + 1;
          }
          $low = $mid+1;
       }
    }
    return 0;

}

$servers = array(
    '127.1:80',
    '127.1:81',
    '127.1:82',
    '127.1:83',
    '127.1:84',
);

$v_nodes = pow(2,10);
$pos2target = array();
$target2pos = array();

foreach($servers as $server) {
   for($i=0;$i<$v_nodes;$i++) {
      $pos = rehash($server.$i);
      $pos2target[$pos] = $server;
      $target2pos[$server][] = $pos;
   }
}

$keys = array_keys($pos2target);
asort($keys);

foreach(range(1,1000) as $v) {
   echo "sky_$v => ".findPos("sky_$v",$keys)."\n";
}
