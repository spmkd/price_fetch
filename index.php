<?php

$start_time = microtime(TRUE);

include 'BaseModules/data_base_connect.php';

include 'BaseModules/header_date_search.php';

if(empty($_GET["gameTitle"])){
	include 'SpecificModules/show_main.php';
}else{
	include 'SpecificModules/show_single_game.php';
}

include 'BaseModules/data_base_close.php';

include 'BaseModules/page_generated_in_time.php'

?>