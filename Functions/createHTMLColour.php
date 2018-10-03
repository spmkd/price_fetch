<?php

function CreateHTMLColour($numberOfChanges){

	switch ($numberOfChanges){
		case 0:
			return "#ffffff";
			break;
		case 1:
			return "#fff2f2";
			break;
		case 2:
			return "#efdcdc";
			break;
		case 3:
			return "#cebbbb";
			break;
	}

}

?>