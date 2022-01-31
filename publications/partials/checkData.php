<?php
/**
 * Unset yet checked prefetched data.
 */
unset($_SESSION['publication_prefetchedData_checked']);
bibliographie_history_append_step('publications', 'Precheck data from external source');
?>

    <h3>Check fetched data</h3>
    <p class="notice">Please precheck all of the parsed authors now before moving to creating them in the publication editor!</p>
<?php
if(is_array($_SESSION['publication_prefetchedData_unchecked'])){
    $searchPersons = array();

    /**
     * Loop for entries...
     */
    foreach($_SESSION['publication_prefetchedData_unchecked'] as $entryID => $entry){
        ?>

        <div id="bibliographie_checkData_entry_<?php echo $entryID?>" class="bibliographie_checkData_entry">
            <em class="bibliographie_checkData_pubType"><?php echo $entry['pub_type']?></em>
            <strong><?php echo $entry['title']?></strong>

            <div id="bibliographie_checkData_approvalResult_<?php echo $entryID?>"></div>

            <div class="bibliographie_checkData_persons">
		<span style="float: right; font-size: 0.8em; text-align: right;">
			<a href="javascript:;" onclick="bibliographie_publications_check_data_approve_entry(<?php echo $entryID?>)">Approve entry <?php echo bibliographie_icon_get('tick')?> </a><br />
			<a href="javascript:;" onclick="bibliographie_publications_check_data_approve_all(<?php echo $entryID?>)">Approve all persons and entry <?php echo bibliographie_icon_get('tick')?></a><br />
			<a href="javascript:;" onclick="$('#bibliographie_checkData_entry_<?php echo $entryID?>').hide('slow', function() {$(this).remove()})">Remove entry <?php echo bibliographie_icon_get('cross')?></a>
		</span>
                <?php
                /**
                 * Loop for persons... Authors and editors...
                 */
                $persons = false;
                foreach(array('author', 'editor') as $role){
                    if (!empty($entry[$role])){
                        $persons = true;

                        foreach($entry[$role] as $personID => $person){
                            /**
                             * Put the person in the array that is needed for js functionality...
                             */
                            $searchPersons[$entryID][$role][$personID] = array (
                                'htmlID' => $entryID.'_'.$role.'_'.$personID,

                                'role' => $role,
                                'entryID' => $entryID,
                                'personID' => $personID,

                                'name' => $person['first'].' '.$person['von'].' '.$person['last'].' '.$person['jr'],

                                'first' => $person['first'],
                                'von' => $person['von'],
                                'last' => $person['last'],
                                'jr' => $person['jr'],

                                'approved' => false
                            );

                            if(!empty($person['jr']))
                                $person['jr'] = ' '.$person['jr'];
                            ?>

                            <div id="bibliographie_checkData_person_<?php echo $entryID.'_'.$role.'_'.$personID?>" style="margin-top: 10px;">
                                <?php echo $role?> #<?php echo ((string) $personID + 1)?>:
                                <?php echo $person['von'].' <strong>'.$person['last'].'</strong>'.$person['jr'].', '.$person['first']?>
                                <div id="bibliographie_checkData_personResult_<?php echo $entryID.'_'.$role.'_'.$personID?>"><img src="<?php echo BIBLIOGRAPHIE_ROOT_PATH?>/resources/images/loading.gif" alt="pending" /></div>
                            </div>
                            <?php
                        }
                    }
                }

                /**
                 * Tell if no persons were parsed...
                 */
                if(!$persons)
                    echo '<p class="error">No persons could be parsed for this entry!</p>';
                ?>

            </div>
        </div>
        <?php
    }
    ?>

    <div class="submit"><button onclick="window.location = bibliographie_web_root+'/publications/?task=publicationEditor&amp;useFetchedData=1';">Go to publication editor</button></div>

    <script type="text/javascript">
        /* <![CDATA[ */
        var bibliographie_checkData_searchPersons = <?php echo json_encode($searchPersons)?>;

        $(function () {
            $.each(bibliographie_checkData_searchPersons, function (entryID, entries) {
                $.each(entries, function (role, persons) {
                    $.each(persons, function (personID, person){
                        bibliographie_publications_search_author_for_approval(role, person);
                    })
                });
            });
        });
        /* ]]> */
    </script>

<?php } ?>


