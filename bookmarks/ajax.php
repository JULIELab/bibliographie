<?php
define('BIBLIOGRAPHIE_OUTPUT_BODY', false);

require '../init.php';

switch($_GET['task']){
	case 'setBookmark':
		$text = 'bookmarks/ajax/setBookmark: An error occured!';
		if(bibliographie_bookmarks_set_bookmark($_GET['pub_id']))
			$text = bibliographie_bookmarks_print_html($_GET['pub_id']);
		echo $text;
	break;

	case 'unsetBookmark':
		$text = 'bookmarks/ajax/unsetBookmark: An error occured!';
		if(bibliographie_bookmarks_unset_bookmark($_GET['pub_id']))
			$text = bibliographie_bookmarks_print_html($_GET['pub_id']);

		echo $text;
	break;
}

require BIBLIOGRAPHIE_ROOT_PATH.'/close.php';
