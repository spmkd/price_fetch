<?php
 
$end_time = microtime(TRUE);
 
$time_taken = $end_time - $start_time;
 
$time_taken = round($time_taken,5);
 
echo 'Page generated in '.$time_taken.' seconds.';
 
?>