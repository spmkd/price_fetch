<?php

# Set the $gameTitle from the passed arguments

$singleGameTitle = $_GET["gameTitle"];

# Set the Start and End date for which we need to show data for

$date1 = new DateTime($toDate); 	// toDate 	is set in header_date_search.php
$date2 = new DateTime($fromDate);	// fromDate is set in header_date_search.php

# The dates that will be used in the SQL search statements

$searchFromDate = $date2->format('Y-m-d H:i');
$searchToDate = $date1->format('Y-m-d H:i');

# Define the Arrays in which we will hold all the results

$gameDate		= array();
$rank			= array();
$price			= array();
$merchantName	= array();

# Get the main result, all values between two dates

$sql1 = "SELECT Top25.Date ,AllGameNames.gameTitle , Top25.Rank, Top25.Price, AllMerchants.merchantName 
FROM Top25
INNER JOIN AllGameNames ON Top25.gameName = AllGameNames.id
INNER JOIN AllMerchants ON Top25.Merchant = AllMerchants.id
WHERE Date >= '$searchFromDate' AND Date <= '$searchToDate'
AND AllGameNames.gameTitle = '$singleGameTitle' order by Top25.Date , Top25.Rank ;";

$result1 = $conn->query($sql1);

# To count the number of records that are unique
$uniqueNumberOfRecords = 0;

if($result1->num_rows > 0) {
		
	while ($row = $result1->fetch_assoc()){

		if ($uniqueNumberOfRecords == 0){
			array_push($gameDate, $row["Date"]);
			array_push($rank, $row["Rank"]);
			array_push($price, $row["Price"]);
			array_push($merchantName, $row["merchantName"]);
			$uniqueNumberOfRecords++;
		}else{

			# We need to check if the previous record is the same as the current so that we can ignore it

			if ( ($rank[($uniqueNumberOfRecords-1)] != $row["Rank"]) or ($price[($uniqueNumberOfRecords-1)] != $row["Price"]) or ($merchantName[($uniqueNumberOfRecords-1)] != $row["merchantName"]) ){
					array_push($gameDate, $row["Date"]);
					array_push($rank, $row["Rank"]);
					array_push($price, $row["Price"]);
					array_push($merchantName, $row["merchantName"]);
					$uniqueNumberOfRecords++;
					}
		}
	}
}

?>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ['Date', 'Price', 'Rank',{type: 'string', role: 'tooltip'}],
         <?php 

         $uniqueNumberOfRecords--;

         for ($x=0; $x < $uniqueNumberOfRecords ; $x++){
         	echo "['$gameDate[$x]',	$price[$x],	$rank[$x],	'$merchantName[$x]'],";
         }

         echo "['$gameDate[$uniqueNumberOfRecords]',	$price[$uniqueNumberOfRecords],	$rank[$uniqueNumberOfRecords],	'$merchantName[$uniqueNumberOfRecords]']";

         ?>
         
      ]);

    var options = {
      title : 'Game Overview',
      vAxis: {title: 'Price / Rank'},
      hAxis: {title: 'Date'},
      seriesType: 'line'
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
    </script>


  <body>
    <div id="chart_div" style="width: 1600px; height: 500px;"></div>
  </body>