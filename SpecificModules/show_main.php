<?php

# Set the Start and End date for which we need to show data for

$date1 = new DateTime($toDate); 	// toDate 	is set in header_date_search.php
$date2 = new DateTime($fromDate);	// fromDate is set in header_date_search.php

# The dates that will be used in the SQL search statements

$searchFromDate = $date2->format('Y-m-d H:i');
$searchToDate = $date1->format('Y-m-d H:i');

# Get the number of columns required based on the unique dates

$numberOfUniqueDates = 0;
$uniqueDates = array();

$sql = "SELECT distinct(Date) FROM Top25 where Date >= '$searchFromDate' and Date <= '$searchToDate' order by Date;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

	while ($row = $result->fetch_assoc()) {
    
		$uniqueDates[$numberOfUniqueDates] = $row["Date"];
		$numberOfUniqueDates++;
    }
	
} 
else
{
	echo "<br> No Results found between the given dates <br>";
}

# Define the Arrays in which we will hold all the results

$gameDate		= array();
$gameTitle 		= array();
$rank			= array();
$price			= array();
$merchantName	= array();

# Get the main result, all values between two dates
		
$sql2 = "SELECT Top25.Date ,AllGameNames.gameTitle , Top25.Rank, Top25.Price, AllMerchants.merchantName 
FROM Top25
INNER JOIN AllGameNames ON Top25.gameName = AllGameNames.id
INNER JOIN AllMerchants ON Top25.Merchant = AllMerchants.id
WHERE Date >= '$searchFromDate' AND Date <= '$searchToDate' order by Top25.Date , Top25.Rank ;";

$result2 = $conn->query($sql2);

if($result2->num_rows > 0) {
		
	while ($row = $result2->fetch_assoc()){
		array_push($gameDate, $row["Date"]);
		array_push($gameTitle, $row["gameTitle"]);
		array_push($rank, $row["Rank"]);
		array_push($price, $row["Price"]);
		array_push($merchantName, $row["merchantName"]);	
	}
	
}
		
?>

<table border="1">
	<tr>
		<th></th>
	
<?php

	for ($x = 0; $x < $numberOfUniqueDates; $x++)
	{
		echo "<th> $uniqueDates[$x] </th>";
	}
	
	echo "</tr>";
	
	for ($x = 0; $x < 25; $x++){
		
		$rankToDisplay = $x + 1;
		
		echo "<tr>";
			echo "<td> $rankToDisplay </td>";
		
		for ($y = 0; $y < $numberOfUniqueDates; $y++){
			
			$locationAtStack = $x + ( $y * 25);
			
			# We need to check if the games Date equals the Unique date ($Y) for that field in the column
			# Also, we need to check if the rank of the date equals the $X
			
			if ( ($gameDate[$locationAtStack] == $uniqueDates[$y]) and ( $rank[$locationAtStack] == ($x+1) ) ) {

				$linkToSingleGame = $_SERVER['PHP_SELF'] . "?gameTitle=" . $gameTitle[$locationAtStack];
				
				echo "<td><table>";
				echo "	<tr>";
				echo "		<td colspan=\"2\"><a href=\"$linkToSingleGame\"> $gameTitle[$locationAtStack] </a></td>";
				echo "	</tr>";
				echo "	<tr>";
				echo "		<td> $price[$locationAtStack] </td>";
				echo "		<td> $merchantName[$locationAtStack] </td>";
				echo "	</tr>";
				echo "</table></td>";
				
			} else {
				error_log("Probalble issue, this should not appear!",0);
				echo "This should not appear!";
			}
		}
		
		echo "</tr>";
			
	}
?>

</table>