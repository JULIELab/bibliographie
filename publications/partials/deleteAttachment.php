<?php

bibliographie_history_append_step('attachments', 'Delete attachment', false);
echo '<h3>Delete attachment</h3>';
$attachment = bibliographie_attachments_get_data($_GET['att_id']);
if(is_object($attachment)){
    if(bibliographie_attachments_delete($attachment->att_id))
        echo '<p class="success">Attachment was deleted!</p>';
    else
        echo '<p class="error">(EE) Deletion of attachment failed!</p>';
}else {
    echo '<p class="error">Attachment was not found!</p>';
}


