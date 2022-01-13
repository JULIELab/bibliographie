<?php

$publications = bibliographie_publications_get_cached_list($_GET['list']);

if(is_array($publications) and count($publications) > 0){
    if($_GET['category'] == 'topics'){
        if(!empty($_POST['topics']) and is_csv($_POST['topics'], 'int')){
            $topics = csv2array($_POST['topics'], 'int');

            if($_POST['addTopics'] == 'Add topics'){
                echo '<p class="notice">Adding topics to publications...</p>';
                echo '<ul>';
                foreach($topics as $topic){
                    $topic = bibliographie_topics_get_data($topic);

                    if(!is_object($topic))
                        continue;

                    $topicFamily = bibliographie_topics_get_parent_topics($topic->topic_id);
                    $topicFamily[] = $topic->topic_id;
                    if(count(array_intersect($topicFamily, bibliographie_topics_get_locked_topics()))){
                        echo '<li>'.bibliographie_icon_get('error').' '.bibliographie_topics_parse_name($topic->topic_id, array('linkProfile' => true)).' is in the list of locked topics. No changes were committed to this topic!</li>';
                        continue;
                    }

                    echo '<li>';
                    echo 'Adding topic '.bibliographie_topics_parse_name($topic->topic_id, array('linkProfile' => true)).' ... ';
                    $result = bibliographie_publications_add_topic($publications, $topic->topic_id);
                    if(is_array($result)){
                        echo bibliographie_icon_get('tick').' Success!<br />'
                            .'<em>'.count($result['publicationsAdded']).' publications were added. '.count(array_diff($publications, $result['publicationsToAdd'])).' had this topic already.</em>';

                        if(count($result['publicationsAdded']) != count($result['publicationsToAdd']))
                            echo '<br /><span class="error">'.(count($result['publicationsToAdd']) - count($result['publicationsAdded'])).' could not be added.</span>';
                    }else
                        echo bibliographie_icon_get('cross').'(EE) Adding publication failed!';

                    echo '</li>';
                }
                echo '</ul>';
            }elseif($_POST['removeTopics'] == 'Remove topics'){
                echo '<p class="notice">Removing topics from publications...</p>';
                echo '<ul>';
                foreach($topics as $topic){
                    $topic = bibliographie_topics_get_data($topic);

                    if(!is_object($topic))
                        continue;

                    $topicFamily = bibliographie_topics_get_parent_topics($topic->topic_id);
                    $topicFamily[] = $topic->topic_id;
                    if(count(array_intersect($topicFamily, bibliographie_topics_get_locked_topics()))){
                        echo '<li>'.bibliographie_icon_get('error').' '.bibliographie_topics_parse_name($topic->topic_id, array('linkProfile' => true)).' is in the list of locked topics. No changes were committed to this topic!</li>';
                        continue;
                    }

                    echo '<li>';
                    echo 'Removing topic '.bibliographie_topics_parse_name($topic->topic_id, array('linkProfile' => true)).' ... ';
                    $result = bibliographie_publications_remove_topic($publications, $topic->topic_id);
                    if(is_array($result)){
                        echo bibliographie_icon_get('tick').' Success!<br />'
                            .'<em>Topic was removed from the publications.</em>';
                    }else
                        echo bibliographie_icon_get('cross').'(EE) Removal of topic failed!';

                    echo '</li>';
                }
                echo '</ul>';
            }
        }else
            echo '<p class="error">You did not supply a list of topics to work with!</p>';
    }elseif($_GET['category'] == 'tags'){
        if(!empty($_POST['tags']) and is_csv($_POST['tags'], 'int')){
            $tags = csv2array($_POST['tags'], 'int');

            if($_POST['addTags'] == 'Add tags'){
                echo '<p class="notice">Adding tags to publications...</p>';
                echo '<ul>';
                foreach($tags as $tag){
                    $tag = bibliographie_tags_get_data($tag);

                    if(!is_object($tag))
                        continue;

                    echo 'Adding tag '.bibliographie_tags_parse_tag($tag->tag_id, array('linkProfile' => true)).' ... ';
                    $result = bibliographie_publications_add_tag($publications, $tag->tag_id);
                    if(is_array($result)){
                        echo bibliographie_icon_get('tick').' Success!<br />'
                            .'<em>'.count($result['publicationsAdded']).' publications were added. '.count(array_diff($publications, $result['publicationsToAdd'])).' had this tag already.</em>';

                        if(count($result['publicationsAdded']) != count($result['publicationsToAdd']))
                            echo '<br /><span class="error">'.(count($result['publicationsToAdd']) - count($result['publicationsAdded'])).' could not be added.</span>';
                    }else
                        echo bibliographie_icon_get('cross').'(EE) Addition of publication failed!!';
                }
                echo '</ul>';
            }elseif($_POST['removeTags'] == 'Remove tags'){
                echo '<p class="notice">Removing tags from publications...</p>';
                echo '<ul>';
                foreach($tags as $tag){
                    $tag = bibliographie_tags_get_data($tag);

                    if(!is_object($tag))
                        continue;

                    echo '<li>';
                    echo 'Removing tag '.bibliographie_tags_parse_tag($tag->tag_id, array('linkProfile' => true)).' ... ';
                    $result = bibliographie_publications_remove_tag($publications, $tag->tag_id);
                    if(is_array($result)){
                        echo bibliographie_icon_get('tick').' Success!<br />'
                            .'<em>Tag was removed from the publications.</em>';
                    }else
                        echo bibliographie_icon_get('cross').'(EE) Removal of tag failed!';

                    echo '</li>';
                }
                echo '</ul>';
            }
        }
    }

    ?>

    <form action="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/publications/?task=batchOperations&amp;list=<?php echo $_GET['list']?>&amp;category=topics" method="post">
        <h3><?php echo bibliographie_icon_get('folder')?> Topics</h3>
        <div class="unit">
            <label for="topics" class="block">Topics</label>
            <div id="topicsContainer" style="background: #fff; border: 1px solid #aaa; color: #000; float: right; font-size: 0.8em; max-height: 200px; overflow-y: scroll; padding: 5px; width: 45%;"><em>Search for a topic in the left container!</em></div>

            <input type="text" id="topics" name="topics" style="width: 100%" value="<?php echo htmlspecialchars($_POST['topics'])?>" />

            <br style="clear: both" />

            <em>Please select the topics that you want to add to or remove from the publications.</em>
        </div>

        <div class="submit">
            <input type="submit" name="addTopics" value="Add topics" />
            <input type="submit" name="removeTopics" value="Remove topics" />
        </div>
    </form>

    <form action="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/publications/?task=batchOperations&amp;list=<?php echo $_GET['list']?>&amp;category=tags" method="post">
        <h3><?php echo bibliographie_icon_get('tag-blue')?> Tags</h3>
        <div class="unit">
            <label for="tags" class="block">Tags</label>
            <em style="float: right; text-align: right;">
                <a href="javascript:;" onclick="bibliographie_tags_create_tag()"><span class="silk-icon silk-icon-tag-blue-add"></span> Add new tag</a><br />
                <span id="tags_tagNotExisting"></em>
            </em>
            <input type="text" id="tags" name="tags" style="width: 100%" value="<?php echo htmlspecialchars($_POST['tags'])?>" />
            <br style="clear: both;" />
            <em>Please select tags that you want to tag the publications with or remove from the publications.</em>
        </div>

        <div class="submit">
            <input type="submit" name="addTags" value="Add tags" />
            <input type="submit" name="removeTags" value="Remove tags" />
        </div>
    </form>

    <h3><?php echo bibliographie_icon_get('page-white-stack')?> Publications that will be affected</h3>
    <?php
    echo bibliographie_publications_print_list(
        $publications,
        BIBLIOGRAPHIE_WEB_ROOT.'/publications/?task=batchOperations&amp;list='.$_GET['list'],
        array(
            'orderBy' => 'title'
        )
    );
    bibliographie_charmap_print_charmap();
    ?>

    <script type="text/javascript">
        /* <![CDATA[ */
        $(function () {
            bibliographie_topics_input_tokenized('topics', 'topicsContainer', <?php echo json_encode(bibliographie_topics_populate_input($_POST['topics']))?>);
            bibliographie_tags_input_tokenized('tags', <?php echo json_encode(bibliographie_tags_populate_input($_POST['tags']))?>);

            $('#topics').charmap();
        })
        /* ]]> */
    </script>
    <?php
    bibliographie_history_append_step('publications', 'Batch operations ('.count($publications).' publications)');
}else
    echo '<h3 class="error">List was empty</h3><p>Sorry, but the list you provided was empty!</p>';

