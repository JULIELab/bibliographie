<?php

define('BIBLIOGRAPHIE_ROOT_PATH', '..');
define('BIBLIOGRAPHIE_OUTPUT_BODY', false);

require BIBLIOGRAPHIE_ROOT_PATH.'/init.php';

$text = (string) 'An error occured!';
$status = (string) 'error';

switch($_GET['task']){
	case 'createTag':
		$tag_id = (int) 0;
		$tag = (string) '';

		if(!empty($_GET['tag'])){
			$data = bibliographie_tags_create_tag($_GET['tag']);
			if(is_array($data) and is_numeric($data['tag_id'])){
				$text = 'Tag has been created!';
				$status = 'success';
				$tag_id = $data['tag_id'];
				$tag = $data['tag'];
			}
		}else
			$text = 'You have to fill a tag to create one!';

		echo json_encode(array(
			'text' => $text,
			'status' => $status,
			'tag_id' => $tag_id,
			'tag' => $tag
		));
	break;

	case 'searchTags':
		$result = array();
		if(mb_strlen($_GET['q']) >= BIBLIOGRAPHIE_SEARCH_MIN_CHARS){
			$tags = mysql_query("SELECT * FROM (SELECT `tag_id`, `tag`, (MATCH(`tag`) AGAINST ('".mysql_real_escape_string(stripslashes(bibliographie_search_expand_query($_GET['q'])))."')) AS `relevancy` FROM `a2tags`) fullTextSearch WHERE `relevancy` > 0 ORDER BY `relevancy` DESC");
			
			if(mysql_num_rows($tags))
				while($tag = mysql_fetch_object($tags)){
					$result[] = array (
						'id' => $tag->tag_id,
						'name' => $tag->tag
					);
				}
		}

		echo json_encode($result);
	break;
}

require BIBLIOGRAPHIE_ROOT_PATH.'/close.php';