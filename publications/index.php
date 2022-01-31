<?php
require '../init.php';
?>

<h2>Publications</h2>
<?php

switch($_GET['task']){
	case 'deleteAttachment':
        include "partials/deleteAttachment.php";
		break;
	case 'deletePublication':
        include "partials/deletePublication.php";
		break;
	case 'batchOperations':
		include "partials/batchOperations.php";
	    break;
	case 'showContainer':
        include "partials/showContainer.php";
	    break;
	case 'showContainerPiece':
		include "partials/showContainerPiece.php";
	    break;
	case 'checkData':
		include "partials/checkData.php";
        break;


       // echo '<p class="error">You did not fetch any data yet! You may want to do so now!</p>';

	case 'fetchData':
		include "partials/fetchData.php";
	    break;
	case 'publicationEditor':
        include "partials/publicationEditor.php";
	    break;
	case 'showPublication':
        include "partials/showPublication.php";
	    break;
}

require BIBLIOGRAPHIE_ROOT_PATH.'/close.php';
