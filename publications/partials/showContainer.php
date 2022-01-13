<?php

if(in_array($_GET['type'], array('journal', 'book'))){
    bibliographie_history_append_step('publications', 'Showing '.$_GET['type'].' "'.htmlspecialchars($_GET['container']).'"');
    $fields = array (
        'journal',
        'volume'
    );
    if($_GET['type'] == 'book')
        $fields = array (
            'booktitle',
            'number'
        );

    $containers = DB::getInstance()->prepare('SELECT
	`year`,
	`journal`,
	`volume`,
	`booktitle`,
	`number`,
	COUNT(*) AS `count`
FROM
	`'.BIBLIOGRAPHIE_PREFIX.'publication`
WHERE
	`'.$fields[0].'` = :container
GROUP BY
	`'.$fields[1].'`
ORDER BY
	`year`,
	`'.$fields[1].'`');
    $containers->execute(array(
        'container' => $_GET['container']
    ));

    if($containers->rowCount() > 0){
        $result = $containers->fetchAll(PDO::FETCH_ASSOC);

        echo '<h3>Chronology of '.htmlspecialchars($_GET['container']).'</h3>';
        echo '<table class="dataContainer">';
        echo '<tr><th></th><th>'.htmlspecialchars($fields[0]).'</th><th>Year & '.htmlspecialchars($fields[1]).'</th><th># of articles</th></tr>';

        foreach($result as $container){
            echo '<tr>'
                .'<td><a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=showContainerPiece&amp;type='.htmlspecialchars($_GET['type']).'&amp;container='.htmlspecialchars($container[$fields[0]]).'&amp;year='.((int) $container['year']).'&amp;piece='.htmlspecialchars($container[$fields[1]]).'">'.bibliographie_icon_get('page-white-stack', 'Show publications').'</a></td>'
                .'<td>'.htmlspecialchars($container[$fields[0]]).'</td>'
                .'<td>'.$container['year'].' '.$container[$fields[1]].'</td>'
                .'<td>'.$container['count'].' article(s)</td>'
                .'</tr>';
        }
        echo '</table>';
    }
}

