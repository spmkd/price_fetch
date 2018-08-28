<?php

function CreateDateLink($numberOfDays){

	$timeTo	= date('Y-m-d H:i',time());
	$timeTo	= str_replace(" ", "T", $timeTo);
	$timeFrom= date('Y-m-d H:i', strtotime($numberOfDays,time()));
	$timeFrom= str_replace(" ", "T", $timeFrom);

	$varToReturn = $_SERVER['PHP_SELF'] . "?fromDate=" . $timeFrom . "&toDate=" . $timeTo;

	return $varToReturn;
}

?>