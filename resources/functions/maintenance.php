<?php
/**
 *
 * @return type
 */
function bibliographie_maintenance_get_unsimilar_groups () {
	$return = array();

	$groups = DB::getInstance()->prepare('SELECT `group` FROM `'.BIBLIOGRAPHIE_PREFIX.'unsimilar_groups_of_authors`');
	$groups->execute();

	if($groups->rowCount() > 0){
		$groups = $groups->fetchAll(PDO::FETCH_COLUMN, 0);
		foreach($groups as $group)
			$return[] = csv2array($group, 'int');
	}

	return $return;
}

/**
 *
 * @param type $author_id
 * @param type $group_id
 */
function bibliographie_maintenance_print_author_profile ($author_id, $group_id = null) {

	$person = bibliographie_authors_get_data($author_id);
	if(is_object($person)){
		echo '<em class="person_id" style="float: right; font-size: 0.8em;">'.((int) $person->author_id).'</em>';
		echo bibliographie_authors_parse_data($person->author_id, array('linkProfile' => true)).'<br />';
		if(is_numeric($group_id))
			echo '<em class="group_id" style="float: right; font-size: 0.8em;">'.((int) $group_id).'</em>';

		if(!empty($person->email))
			echo '<strong>Mail</strong>: '.htmlspecialchars($person->email).'<br />';
		if(!empty($person->url))
			echo '<strong>URL</strong>: '.htmlspecialchars($person->url).'<br />';
		if(!empty($person->institute))
			echo '<strong>Institute</strong>: '.htmlspecialchars($person->institute).'<br />';

		echo '<ul><li><a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/authors/?task=showPublications&amp;author_id='.((int) $person->author_id).'&amp;asEditor=0">Publications as author ('.count(bibliographie_authors_get_publications($person->author_id)).')</a></li>';
		echo '<li><a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/authors/?task=showPublications&amp;author_id='.((int) $person->author_id).'&amp;asEditor=1">Publications as editor ('.count(bibliographie_authors_get_publications($person->author_id, true)).')</a></li></ul>';
	}
}

/**
 *
 * @staticvar string $linkPublications
 * @param type $into
 * @param type $delete
 * @return type
 */
function bibliographie_maintenance_merge_authors ($into, $delete) {
	static $linkPublications = null;

	$return = false;

	$into = bibliographie_authors_get_data($into);
	$delete = bibliographie_authors_get_data($delete);

	if(is_object($into) and is_object($delete) and $into->author_id != $delete->author_id){
		$publications = array_merge(
			array_diff(bibliographie_authors_get_publications($delete->author_id), bibliographie_authors_get_publications($into->author_id)),
			array_diff(bibliographie_authors_get_publications($delete->author_id, true), bibliographie_authors_get_publications($into->author_id, true))
		);

		bibliographie_cache_purge();

		if(count($publications) > 0){
			if($linkPublications === null)
				$linkPublications = DB::getInstance()->prepare('UPDATE
`'.BIBLIOGRAPHIE_PREFIX.'publicationauthorlink`
SET
`author_id` = :into_id
WHERE
FIND_IN_SET(`pub_id`, :publications) AND
`author_id` = :delelete_id');

			$linkPublications->execute(array(
				'into_id' => (int) $into->author_id,
				'delelete_id' => (int) $delete->author_id,
				'publications' => array2csv($publications)
			));

			$return = array (
				'into' => $into->author_id,
				'delete' => $delete->author_id,
				'publications' => array2csv($publications),
				'publicationsAffected' => $linkPublications->rowCount()
			);

			bibliographie_log('maintenance', 'mergeAuthors', json_encode($return));
		}
	}

	return $return;
}