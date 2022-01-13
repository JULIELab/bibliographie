<?php

if(in_array($_GET['type'], array('journal', 'book'))){
    bibliographie_history_append_step('publications', 'Showing '.$_GET['type'].' "'.htmlspecialchars($_GET['container']).'"/'.((int) $_GET['year']).'/'.((int) $_GET['piece']));
    $fields = array (
        'journal',
        'volume'
    );
    if($_GET['type'] == 'book')
        $fields = array (
            'booktitle',
            'number'
        );

    $publications = DB::getInstance()->prepare('SELECT
	`pub_id`
FROM
	`'.BIBLIOGRAPHIE_PREFIX.'publication`
WHERE
	`'.$fields[0].'` = :container AND
	`year` = :year AND
	`'.$fields[1].'` = :piece');

    $publications->execute(array(
        'container' => $_GET['container'],
        'year' => (int) $_GET['year'],
        'piece' => $_GET['piece']
    ));

    $publications = $publications->fetchAll(PDO::FETCH_COLUMN, 0);

    if(count($publications) > 0){
        ?>

        <h3>Publications in <a href="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/publications/?task=showContainer&amp;type=<?php echo htmlspecialchars($_GET['type'])?>&amp;container=<?php echo htmlspecialchars($_GET['container'])?>"><?php echo htmlspecialchars($_GET['container'])?></a>, <?php echo ((int) $_GET['year']).' '.htmlspecialchars($_GET[$field[1]])?></h3>
        <?php
        echo bibliographie_publications_print_list(
            $publications,
            BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=showContainerPiece&amp;type='.htmlspecialchars($_GET['type']).'&amp;container='.htmlspecialchars($_GET['container']).'&amp;year='.((int) $_GET['year']).'&amp;piece='.htmlspecialchars($_GET['piece']),
            array(
                'orderBy' => 'title'
            )
        );
    }
}

