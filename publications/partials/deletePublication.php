<?php

bibliographie_history_append_step('publications', 'Delete publication', false);
echo '<h3>Delete publication</h3>';
$publication = bibliographie_publications_get_data($_GET['pub_id']);
if(is_object($publication)){
    $notes = bibliographie_publications_get_notes($publication->pub_id);

    if(count($notes) == 0){
        if(bibliographie_publications_delete_publication($publication->pub_id))
            echo '<p class="success">Publication was deleted!</p>';
        else
            echo '<p class="error">(EE) Deletion of publication failed!</p>';
    }else{
        echo '<p class="error">Publication cannot be deleted since users have taken notes on this publication!</p><p class="notice">If you want to delete this publication anyway contact your administrator!</p>';
        echo 'This is a list of users that have taken notes on this publication.<ul>';
        foreach($notes->fetchAll(PDO::FETCH_OBJ) as $note)
            echo '<li><strong>'.bibliographie_user_get_name($note->user_id).'</strong></li>';
        echo '</ul>You can ask them to delete their notes and delete the publication afterwards.';
    }
}else {
    echo '<p class="error">Publication was not found!</p>';
}


