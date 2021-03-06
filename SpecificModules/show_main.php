<?php

# Including the function which returns the HTML colour for tables with number of changes
include 'Functions/createHTMLColour.php';

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
$isThisNewGame	= array();
$priceChange	= array();
$merchantChange	= array();
$rankChange		= array();
$comeBack		= array();

# Get the main result, all values between two dates
		
$sql2 = "SELECT Top25.Date ,AllGameNames.gameTitle , Top25.Rank, Top25.Price, AllMerchants.merchantName, Top25.isThisNewGame, Top25.priceChange, Top25.merchantChange, Top25.rankChange, Top25.comeBack 
FROM Top25
INNER JOIN AllGameNames ON Top25.gameName = AllGameNames.id
INNER JOIN AllMerchants ON Top25.Merchant = AllMerchants.id
WHERE Date >= '$searchFromDate' AND Date <= '$searchToDate' order by Top25.Date , Top25.Rank ;";

$result2 = $conn->query($sql2);

if($result2->num_rows > 0) {
		
	while ($row = $result2->fetch_assoc()){
		array_push($gameDate, 		$row["Date"]);
		array_push($gameTitle, 		$row["gameTitle"]);
		array_push($rank, 			$row["Rank"]);
		array_push($price, 			$row["Price"]);
		array_push($merchantName, 	$row["merchantName"]);
		array_push($isThisNewGame, 	$row["isThisNewGame"]);
		array_push($priceChange, 	$row["priceChange"]);
		array_push($merchantChange, $row["merchantChange"]);
		array_push($rankChange, 	$row["rankChange"]);
		array_push($comeBack, 		$row["comeBack"]);
	}
}

// Verification that all rows have 25 entries is required
// When a row has less than 25 rows, we need to fill it up for this date to get to 25

$counter=0;

for ($x = 0; $x < $numberOfUniqueDates; $x++){

	for ($y = 0; $y < 25; $y++){

		$theDate= $counter;

		if ( strcmp($gameDate[$theDate], $uniqueDates[$x]) ){

			error_log("Filling in for missing entries - $theDate - $gameDate[$theDate] and " . " $uniqueDates[$x]");

			$toInsertGameDate		= array($uniqueDates[$x]);
			$toInsertGameTitle 		= array("N/A");
			$toInsertRank			= array($y + 1);
			$toInsertPrice			= array("");
			$toInsertMerchantName	= array("");
			$toInsertisThisNewGame	= array("");
			$toInsertpriceChange	= array("");
			$toInsertmerchantChange	= array("");
			$toInsertrankChange		= array("");
			$toInsertcomeBack		= array("");

			array_splice($gameDate, $counter, 0, $toInsertGameDate);
			array_splice($gameTitle, $counter, 0, $toInsertGameTitle);
			array_splice($rank, $counter, 0, $toInsertRank);
			array_splice($price, $counter, 0, $toInsertPrice);
			array_splice($merchantName, $counter, 0, $toInsertMerchantName);
			array_splice($isThisNewGame, $counter, 0, $toInsertisThisNewGame);
			array_splice($priceChange, $counter, 0, $toInsertpriceChange);
			array_splice($merchantChange, $counter, 0, $toInsertmerchantChange);
			array_splice($rankChange, $counter, 0, $toInsertrankChange);
			array_splice($comeBack, $counter, 0, $toInsertcomeBack);
		}

		$counter = $counter +1;

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
	
	// here we go to print all of the columns
	for ($x = 0; $x < 25; $x++){
		
		$rankToDisplay = $x + 1;
		
		echo "<tr>";
			echo "<td> $rankToDisplay </td>";
		
		// here we go through EACH row EACH date EACH rank
		for ($y = 0; $y < $numberOfUniqueDates; $y++){
			
			$locationAtStack = $x + ( $y * 25);
			
			# We need to check if the games Date equals the Unique date ($Y) for that field in the column
			# Also, we need to check if the rank of the date equals the $X
			
			if ( ($gameDate[$locationAtStack] == $uniqueDates[$y]) and ( $rank[$locationAtStack] == ($x+1) ) ) {

				$linkToSingleGame = $_SERVER['PHP_SELF'] . "?gameTitle=" . $gameTitle[$locationAtStack] . "&fromDate=" . $fromDate . "&toDate=" . $toDate;

				# This variable will hold the number of changes for this game so that we can colour the background
				$numberOfChanges		= 0;

				$isThisNewGameToPrint	= "";
				$priceChangeToPrint		= "";
				$merchantChangeToPrint	= "";
				$rankChangeToPrint		= "";
				$comeBackToPrint		= "";

				if ($isThisNewGame[$locationAtStack] == "1") { $isThisNewGameToPrint = "<font color=\"red\"> NEW! </font>"; $numberOfChanges++; }
				if ($priceChange[$locationAtStack] == "1") { $priceChangeToPrint = " &uarr; "; $numberOfChanges++;} elseif ($priceChange[$locationAtStack] == "-1") {$priceChangeToPrint = " &darr; "; $numberOfChanges++;}
				if ($merchantChange[$locationAtStack] == "1") {$merchantChangeToPrint = " &harr; "; $numberOfChanges++;}
				if ($rankChange[$locationAtStack] == "1") {$rankChangeToPrint = " &darr; "; $numberOfChanges++;} elseif ($rankChange[$locationAtStack] == "-1") {$rankChangeToPrint = " &uarr; "; $numberOfChanges++;}
				if ($comeBack[$locationAtStack] == "1") {$comeBackToPrint = " &#8635; "; $numberOfChanges++;}

				$colourBasedOnNumberOfChanges = CreateHTMLColour($numberOfChanges);

				echo "<td><table bgcolor=$colourBasedOnNumberOfChanges>";
				echo "	<tr>";
				echo "		<td colspan=\"2\"><a href=\"$linkToSingleGame\"> $gameTitle[$locationAtStack] </a> $isThisNewGameToPrint $rankChangeToPrint $comeBackToPrint</td>";
				echo "	</tr>";
				echo "	<tr>";
				echo "		<td> $price[$locationAtStack] $priceChangeToPrint</td>";
				echo "		<td> $merchantName[$locationAtStack] $merchantChangeToPrint</td>";
				echo "	</tr>";
				echo "</table></td>";
				
			} else {
				error_log("$gameTitle[$locationAtStack] and date is");
				error_log("Probalble issue, this should not appear!",0);
				echo "This should not appear!";
			}
		}
		
		echo "</tr>";
			
	}
?>

</table>