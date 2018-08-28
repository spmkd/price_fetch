<?php

$singleGameIncluded = "";
if(!empty($_GET["gameTitle"])){
	$singleGameIncluded="&gameTitle=" . $_GET["gameTitle"];
}

include 'Functions/createDateLink.php';


$last24HoursTo	= date('Y-m-d H:i',time());
$last24HoursTo	= str_replace(" ", "T", $last24HoursTo);
$last24HoursFrom= date('Y-m-d H:i', strtotime('-1 Day',time()));
$last24HoursFrom= str_replace(" ", "T", $last24HoursFrom);
$last24HoursLink= $_SERVER['PHP_SELF'] . "?fromDate=" . $last24HoursFrom . "&toDate=" . $last24HoursTo . $singleGameIncluded;

$lastWeekTo	= date('Y-m-d H:i',time());
$lastWeekTo	= str_replace(" ", "T", $lastWeekTo);
$lastWeekFrom= date('Y-m-d H:i', strtotime('-7 Day',time()));
$lastWeekFrom= str_replace(" ", "T", $lastWeekFrom);
$lastWeekLink= $_SERVER['PHP_SELF'] . "?fromDate=" . $lastWeekFrom . "&toDate=" . $lastWeekTo . $singleGameIncluded;

$fromDate=$_GET["fromDate"];

if(empty($fromDate)){
    $fromDate = date('Y-m-d H:i', strtotime('-1 Day',time()));
    $fromDate = str_replace(" ", "T", $fromDate);
}

$toDate=$_GET["toDate"];

if(empty($toDate)){
    $toDate = date('Y-m-d H:i',time());
    $toDate = str_replace(" ", "T", $toDate);
}

?>

<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
  From Date (date and time):
  <input type="datetime-local" name="fromDate" value="<?php echo $fromDate; ?>">
  To Date (date and time):
  <input type="datetime-local" name="toDate" value="<?php echo $toDate; ?>">
  <input type="submit" value="Display">
</form>

<a href="<?php echo $last24HoursLink ?>"> Last 24 Hours </a> &nbsp; &nbsp; &nbsp;
<a href="<?php echo $lastWeekLink ?>"> Last 7 Days </a>
<br><br>