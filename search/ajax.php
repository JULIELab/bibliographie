<?php

define('BIBLIOGRAPHIE_ROOT_PATH', '..');
define('BIBLIOGRAPHIE_OUTPUT_BODY', false);

require BIBLIOGRAPHIE_ROOT_PATH.'/functions.php';

switch($_GET['task']){
	case 'simpleSearch':
		if(mb_strlen($_GET['q']) >= BIBLIOGRAPHIE_SEARCH_MIN_CHARS){
			$searchResults = null;
			$expandedeQuery = (string) '';

			$searchTimer = microtime(true);
			$options = array('suffixes' => true, 'plurals' => true, 'umlauts' => true, 'repeat' => 3);
			switch($_GET['category']){
				case 'topics':
					$expandedQuery = bibliographie_search_expand_query($_GET['q'], $options);
					$searchResults = mysql_query("SELECT * FROM (SELECT `topic_id`, `name`, `description`, `url`, (MATCH(`name`, `description`) AGAINST ('".mysql_real_escape_string(stripslashes($expandedQuery))."')) AS `relevancy` FROM `a2topics`) fullTextSearch WHERE `relevancy` > 0 ORDER BY `relevancy` DESC");
				break;

				case 'authors':
					$options['plurals'] = false;
					$expandedQuery = bibliographie_search_expand_query($_GET['q'], $options);
					$searchResults = mysql_query("SELECT * FROM (SELECT `author_id`, (MATCH(`surname`, `firstname`) AGAINST ('".mysql_real_escape_string(stripslashes($expandedQuery))."')) AS `relevancy` FROM `a2author`) fullTextSearch WHERE `relevancy` > 0 ORDER BY `relevancy` DESC");
				break;

				case 'publications':
					$expandedQuery = bibliographie_search_expand_query($_GET['q'], $options);
					$searchResults = mysql_query("SELECT * FROM (SELECT `pub_id`, (MATCH(`title`, `abstract`) AGAINST ('".mysql_real_escape_string(stripslashes($expandedQuery))."')) AS `relevancy` FROM `a2publication`) fullTextSearch WHERE `relevancy` > 0 ORDER BY `relevancy` DESC");
				break;

				case 'tags':
					$expandedQuery = bibliographie_search_expand_query($_GET['q'], $options);
					$searchResults = mysql_query("SELECT * FROM (SELECT `tag_id`, `tag`, (MATCH(`tag`) AGAINST ('".mysql_real_escape_string(stripslashes($expandedQuery))."')) AS `relevancy` FROM `a2tags`) fullTextSearch WHERE `relevancy` > 0 ORDER BY `relevancy` DESC");
				break;
			}
			echo '<em style="float: right; font-size: 0.8em;">query took '.round(microtime(true) - $searchTimer, 5).'s, '.count(explode(' ', $expandedQuery)).' words</em>';

			if(mysql_num_rows($searchResults) > 0){
				$i = (int) 0;
				$limit = -1;
				if($_GET['limit'] == 1)
					$limit = ceil(log(mysql_num_rows($searchResults), 2) + 1) * 2;
				$text = (string) '';

				while($row = mysql_fetch_object($searchResults) and ($i < $limit or $limit == -1)){
					switch($_GET['category']){
						case 'topics':
							$text .= '<div class="searchResult">';
							if(!empty($row->url))
								$text .= '<em style="float: right">'.htmlspecialchars($row->url).'</em>';
							$text .= '<a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/topics/?task=showTopic&amp;topic_id='.$row->topic_id.'" style="display: block">'.htmlspecialchars($row->name).'</a>';
							if(!empty($row->description))
								$text .= '<em>'.htmlspecialchars($row->description).'</em>';
							$text .= '</div>';
						break;

						case 'authors':
							$text .= '<div class="searchResult">';
							$text .= '<a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/authors/?task=showAuthor&amp;author_id='.$row->author_id.'" style="display: block">'.bibliographie_authors_parse_data($row->author_id).'</a>';
							$text .= '</div>';
						break;

						case 'publications':
							$text .= '<div id="publication_container_'.((int) $row->pub_id).'" class="bibliographie_publication';
							if(bibliographie_bookmarks_check_publication($row->pub_id))
								$text .= ' bibliographie_publication_bookmarked';
							$text .= '">'.bibliographie_bookmarks_print_html($row->pub_id).bibliographie_publications_parse_data($row->pub_id).'</div>';
						break;

						case 'tags':
							$text .= '<div class="searchResult"><a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/tags/?task=showTag&amp;tag_id='.$row->tag_id.'" style="display: block">'.$row->tag.'</a></div>';
						break;
					}

					$i++;
				}

				echo '<div>Showing ';
				echo '<strong>'.$i.' result</strong>(s) of ';
				echo '<strong>'.mysql_num_rows($searchResults).' found '.htmlspecialchars($_GET['category']).'</strong> for query ';
				echo '<strong>'.htmlspecialchars($_GET['q']).'</strong>. ';
				if($limit != -1 and $i < mysql_num_rows($searchResults))
					echo '<a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/search/?task=simpleSearch&amp;category='.htmlspecialchars($_GET['category']).'&amp;q='.htmlspecialchars($_GET['q']).'">Show all results!</a>';
				echo '</div>';
				echo PHP_EOL.$text;

			}else
				echo '<div>There were no results for your search with query <strong>'.htmlspecialchars($_GET['q']).'</strong>.</div>';

		}else
			echo '<p class="error">Your search query was too short! You have to input at least '.BIBLIOGRAPHIE_SEARCH_MIN_CHARS.' chars.</p>';
	break;
}

require BIBLIOGRAPHIE_ROOT_PATH.'/close.php';