<?php

$publication = bibliographie_publications_get_data($_GET['pub_id']);

if(is_object($publication)){
    $publication = (array) $publication;
    bibliographie_history_append_step('publications', 'Showing publication '.htmlspecialchars($publication['title']));
    ?>

    <em style="float: right">
        <a href="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/notes/?task=noteEditor&amp;pub_id=<?php echo (int) $publication['pub_id']?>"><?php echo bibliographie_icon_get('note-add')?> Add note</a>
        <a href="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/publications/?task=publicationEditor&amp;pub_id=<?php echo ((int) $publication['pub_id'])?>"><?php echo bibliographie_icon_get('page-white-edit')?> Edit</a>
        <a href="javascript:;" onclick="bibliographie_publications_confirm_delete(<?php echo (int) $publication['pub_id']?>)"><?php echo bibliographie_icon_get('page-white-delete')?> Delete</a>
    </em>
    <h3><?php echo htmlspecialchars($publication['title'])?></h3>
    <?php
    echo bibliographie_publications_print_list(
        array($publication['pub_id']),
        BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=publicationEditor&amp;pub_id='.((int) $publication['pub_id']),
        array(
            'onlyPublications' => true
        )
    );
    ?>

    <table class="dataContainer">
        <thead>
        <tr>
            <th colspan="2">
                <a href="javascript:;" onclick="$('tbody').toggle('blind');" style="float: right;">Toggle information</a>
                Extended information
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($bibliographie_publication_data as $dataKey => $dataLabel){
            if(!empty($publication[$dataKey])){
                if($dataKey == 'url')
                    $publication['url'] = '<a href="'.$publication['url'].'">'.$publication['url'].'</a>';

                elseif($dataKey == 'booktitle')
                    $publication['booktitle'] = '<a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=showContainer&amp;type=book&amp;container='.htmlspecialchars($publication['booktitle']).'">'.htmlspecialchars($publication['booktitle']).'</a>';

                elseif($dataKey == 'journal')
                    $publication['journal'] = '<a href="'.BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=showContainer&amp;type=journal&amp;container='.htmlspecialchars($publication['journal']).'">'.htmlspecialchars($publication['journal']).'</a>';

                elseif($dataKey == 'user_id')
                    $publication['user_id'] = bibliographie_user_get_name($publication['user_id']);

                else
                    $publication[$dataKey] = htmlspecialchars($publication[$dataKey]);

                echo '<tr><td><strong>'.$dataLabel.'</strong></td><td>'.$publication[$dataKey].'</td></tr>';
            }elseif(in_array($dataKey, array('authors', 'editors', 'topics', 'tags'))){
                $notEmpty = false;
                if($dataKey == 'authors'){
                    $authors = bibliographie_publications_get_authors($publication['pub_id'], 'name');
                    if(is_array($authors) and !empty($authors)){
                        $notEmpty = true;

                        foreach($authors as $author)
                            $publication['authors'] .= bibliographie_authors_parse_data($author, array('linkProfile' => true)).'<br />';
                    }
                }elseif($dataKey == 'editors'){
                    $editors = bibliographie_publications_get_editors($publication['pub_id'], 'name');
                    if(is_array($editors) and !empty($editors)){
                        $notEmpty = true;

                        foreach($editors as $editor)
                            $publication['editors'] .= bibliographie_authors_parse_data($editor, array('linkProfile' => true)).'<br />';
                    }
                }elseif($dataKey == 'topics'){
                    $topics = bibliographie_publications_get_topics($publication['pub_id']);
                    if(is_array($topics) and !empty($topics)){
                        $notEmpty = true;

                        foreach($topics as $topic)
                            $publication['topics'] .= bibliographie_topics_parse_name($topic, array('linkProfile' => true)).'<br />';
                    }
                }elseif($dataKey == 'tags'){
                    $tags = bibliographie_publications_get_tags($publication['pub_id']);
                    if(is_array($tags) and !empty($tags)){
                        $notEmpty = true;

                        foreach($tags as $tag)
                            $publication['tags'] .= bibliographie_tags_parse_tag($tag, array('linkProfile' => true)).'<br />';
                    }
                }

                if($notEmpty)
                    echo '<tr><td><strong>'.$dataLabel.'</strong></td><td>'.$publication[$dataKey].'</td></tr>';
            }
        }
        ?>

        </tbody>
    </table>

    <?php
    $notes = bibliographie_publications_get_notes($publication['pub_id']);
    if(!empty($notes)){
        echo '<h3>Notes</h3>';
        foreach($notes as $note)
            echo bibliographie_notes_print_note($note->note_id);
    }
    ?>

    <h3>Attachments</h3>
    <div style="background: #9d9; border: 1px solid #0a0; color: #fff; float: right; margin: 0 0 10px 10px; padding: 5px;">
        <label for="fileupload">Add files</label>
        <input id="fileupload" type="file" name="files[]" multiple="multiple" />
    </div>
    This is a list of attached files. You can add new files by using the form on the right side or simply dropping them into the dropzone.
    <div id="attachments">
        <?php
        if(is_array(bibliographie_publications_get_attachments($publication['pub_id']))){
            if(!empty(bibliographie_publications_get_attachments($publication['pub_id'])))
                foreach(bibliographie_publications_get_attachments($publication['pub_id']) as $att_id)
                    echo bibliographie_attachments_parse($att_id);
            else
                echo '<p class="notice">No files are attached.</p>';
        }
        ?>

    </div>
    <div id="dropzone" class="fade well">
        Drop files here to attach them to the publication!
        <div id="fileupload-progress"></div>
    </div>

    <script type="text/javascript">
        /* <![CDATA[ */
        $(function () {
            $('tbody').hide();

            $('#fileupload').fileupload({
                                            'dataType': 'json',
                                            'url': bibliographie_web_root+'/publications/ajax.php?task=uploadAttachment',
                                            'done': function (e, data) {
                                                bibliographie_publications_register_attachment(data.result[0].original_name, data.result[0].name, data.result[0].type);
                                            }
                                        }).on('fileuploadstart', function () {
                var widget = $(this),
                    progressElement = $('#fileupload-progress').fadeIn(),
                    interval = 500,
                    total = 0,
                    loaded = 0,
                    loadedBefore = 0,
                    progressTimer,
                    progressHandler = function (e, data) {
                        loaded = data.loaded;
                        total = data.total;
                    },
                    stopHandler = function () {
                        widget
                            .unbind('fileuploadprogressall', progressHandler)
                            .unbind('fileuploadstop', stopHandler);
                        window.clearInterval(progressTimer);
                        progressElement.fadeOut(function () {
                            progressElement.html('');
                        });
                    },
                    formatTime = function (seconds) {
                        var date = new Date(seconds * 1000);
                        return ('0' + date.getUTCHours()).slice(-2) + ':' +
                               ('0' + date.getUTCMinutes()).slice(-2) + ':' +
                               ('0' + date.getUTCSeconds()).slice(-2);
                    },
                    formatBytes = function (bytes) {
                        if (bytes >= 1000000000) {
                            return (bytes / 1000000000).toFixed(2) + ' GB';
                        }
                        if (bytes >= 1000000) {
                            return (bytes / 1000000).toFixed(2) + ' MB';
                        }
                        if (bytes >= 1000) {
                            return (bytes / 1000).toFixed(2) + ' KB';
                        }
                        return bytes + ' B';
                    },
                    formatPercentage = function (floatValue) {
                        return (floatValue * 100).toFixed(2) + ' %';
                    },
                    updateProgressElement = function (loaded, total, bps) {
                        progressElement.html(
                            formatBytes(bps) + 'ps | ' +
                            formatTime((total - loaded) / bps) + ' | ' +
                            formatPercentage(loaded / total) + ' | ' +
                            formatBytes(loaded) + ' / ' + formatBytes(total)
                        );
                    },
                    intervalHandler = function () {
                        var diff = loaded - loadedBefore;
                        if (!diff) {
                            return;
                        }
                        loadedBefore = loaded;
                        updateProgressElement(
                            loaded,
                            total,
                            diff * (1000 / interval)
                        );
                    };
                widget
                    .on('fileuploadprogressall', progressHandler)
                    .on('fileuploadstop', stopHandler);
                progressTimer = window.setInterval(intervalHandler, interval);
            });
        });

        $(document).on('dragover', function (e) {
            var dropZone = $('#dropzone'),
                timeout = window.dropZoneTimeout;
            if (!timeout) {
                dropZone.addClass('in');
            } else {
                clearTimeout(timeout);
            }
            if (e.target === dropZone[0]) {
                dropZone.addClass('hover');
            } else {
                dropZone.removeClass('hover');
            }
            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null;
                dropZone.removeClass('in hover');
            }, 100);
        }).on('drop dragover', function (e) {
            e.preventDefault();
        });

        function bibliographie_publications_register_attachment (name, location, type) {
            if($('#attachments div.bibliographie_attachment').length == 0)
                $('#attachments').empty();

            $.ajax({
                       'url': bibliographie_web_root+'/publications/ajax.php',
                       'data': {
                           'task': 'registerAttachment',
                           'name': name,
                           'location': location,
                           'type': type,
                           'pub_id': <?php echo $publication['pub_id']?>
                       },
                       'success': function (html) {
                           $('#attachments').append(html);
                       }
                   });
        }
        /* ]]> */
    </script>
    <?php
}else{
    bibliographie_history_append_step('publications', 'Publication does not exist', false);
    echo '<p class="error">Publication was not found!</p>';
}

