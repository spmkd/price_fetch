<?php

$singleGameIncluded = "";
if(!empty($_GET["gameTitle"])){
	$singleGameIncluded="&gameTitle=" . $_GET["gameTitle"];
}

include 'Functions/createDateLink.php';

$last24HoursLink	= CreateDateLink("-1 Day") . $singleGameIncluded;
$lastWeekLink		= CreateDateLink("-7 Day") . $singleGameIncluded;
$last30Days 		= CreateDateLink("-30 Day") . $singleGameIncluded;

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
<a href="<?php echo $lastWeekLink ?>"> Last 7 Days </a> &nbsp; &nbsp; &nbsp;
<a href="<?php echo $last30Days ?>"> Last 30 Days </a>
<br><br>