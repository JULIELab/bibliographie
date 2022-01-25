<?php


?>


<div id="bibliographie_booleansearch">
    <div style="margin-bottom: 10px;">
        <h3 style="float: left; margin: 0;">MySQL Boolean full-text search operators</h3>
        <em style="float: right;">Click to toggle!</em>
    </div>
    <table class="booleansearch_operators" style="border: 1px solid black; clear: both; display: none;">
        <thead>
            <tr>
                <th>Operator</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>+</td>
                <td>Include, the word must be present.</td>
            </tr>
            <tr>
                <td>–</td>
                <td>Exclude, the word must not be present.</td>
            </tr>
            <tr>
                <td>&gt;</td>
                <td>Include, and increase ranking value.</td>
            </tr>
            <tr>
                <td>&lt;</td>
                <td>Include, and decrease the ranking value.</td>
            </tr>
            <tr>
                <td>()</td>
                <td>Group words into subexpressions (allowing them to be included, excluded, ranked, and so forth as a group).</td>
            </tr>
            <tr>
                <td>~</td>
                <td>Negate a word’s ranking value.</td>
            </tr>
            <tr>
                <td>*</td>
                <td>Wildcard at the end of the word.</td>
            </tr>
            <tr>
                <td>“”</td>
                <td>Defines a phrase (as opposed to a list of individual words, the entire phrase is matched for inclusion or exclusion).</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Other (not related to MySQL boolean full-text search)</strong></td>
            </tr>
            <tr>
                <td>from:$year</td>
                <td>From year X</td>
            </tr>
            <tr>
                <td>to:$year</td>
                <td>To year X</td>
            </tr>
            <tr>
                <td>in:$year</td>
                <td>In year X</td>
            </tr>
        </tbody>
    </table>
</div>
<p>Important: While using boolean full-text search the query expansion (QE) function is NOT active</p>
<div>
    <form action="<?php echo BIBLIOGRAPHIE_WEB_ROOT?>/search/" method="get" id="search" style="float: none; width: 100%;">
        <div>
            <a href="#" id="toggleFilter">Toggle all checkboxes</a> |
            <a href="#" id="resetFilter">Reset all checkboxes</a>
            <br />
            <table class="filter" style="border: 1px solid;">
                <tr>
                    <td>
                        <span>Puplic.:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="pub_title" name="pub_title" value="1" checked>
                            <label for="pub_title">Title</label>
                            <input type="checkbox" id="pub_abstract" name="pub_abstract" value="1" checked>
                            <label for="pub_abstract">Abstract</label>
                            <input type="checkbox" id="pub_note" name="pub_note" value="1" checked>
                            <label for="pub_note">Note</label>
                        <?php } else { ?>
                            <input type="checkbox" id="pub_title" name="pub_title" value="1" <?php if($_GET['pub_title']) { echo 'checked'; } ?>>
                            <label for="pub_title">Title</label>
                            <input type="checkbox" id="pub_abstract" name="pub_abstract" value="1" <?php if($_GET['pub_abstract']) { echo 'checked'; } ?>>
                            <label for="pub_abstract">Abstract</label>
                            <input type="checkbox" id="pub_note" name="pub_note" value="1" <?php if($_GET['pub_note']) { echo 'checked'; } ?>>
                            <label for="pub_note">Note</label>
                        <?php } ?>
                    </td>
                    <td>
                        <span>Topics:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="topic_id" name="topic_id" value="1" checked>
                            <label for="topic_id">ID</label>
                            <input type="checkbox" id="topic_name" name="topic_name" value="1" checked>
                            <label for="topic_name">Name</label>
                        <?php } else { ?>
                            <input type="checkbox" id="topic_id" name="topic_id" value="1" <?php if($_GET['topic_id']) { echo 'checked'; } ?>>
                            <label for="topic_id">ID</label>
                            <input type="checkbox" id="topic_name" name="topic_name" value="1" <?php if($_GET['topic_name']) { echo 'checked'; } ?>>
                            <label for="topic_name">Name</label>
                        <?php } ?>
                    </td>
                    <td>
                        <span>Tags:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="tag_id" name="tag_id" value="1" checked>
                            <label for="tag_id">ID</label>
                            <input type="checkbox" id="tag_name" name="tag_name" value="1" checked>
                            <label for="tag_name">Name</label>
                        <?php } else { ?>
                            <input type="checkbox" id="tag_id" name="tag_id" value="1" <?php if($_GET['tag_id']) { echo 'checked'; } ?>>
                            <label for="tag_id">ID</label>
                            <input type="checkbox" id="tag_name" name="tag_name" value="1" <?php if($_GET['tag_name']) { echo 'checked'; } ?>>
                            <label for="tag_name">Name</label>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Authors:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="author_id" name="author_id" value="1" checked>
                            <label for="author_id">ID</label>
                            <input type="checkbox" id="author_surname" name="author_surname" value="1" checked>
                            <label for="author_surname">Nachname</label>
                            <input type="checkbox" id="author_firstname" name="author_firstname" value="1" checked>
                            <label for="author_firstname">Vorname</label>
                        <?php } else { ?>
                            <input type="checkbox" id="author_id" name="author_id" value="1" <?php if($_GET['author_id']) { echo 'checked'; } ?>>
                            <label for="author_id">ID</label>
                            <input type="checkbox" id="author_surname" name="author_surname" value="1" <?php if($_GET['author_surname']) { echo 'checked'; } ?>>
                            <label for="author_surname">Nachname</label>
                            <input type="checkbox" id="author_firstname" name="author_firstname" value="1" <?php if($_GET['author_firstname']) { echo 'checked'; } ?>>
                            <label for="author_firstname">Vorname</label>
                        <?php } ?>
                    </td>
                    <td>
                        <span>Books:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="book_title" name="book_title" value="1" checked>
                            <label for="book_title">Title</label>
                        <?php } else { ?>
                            <input type="checkbox" id="book_title" name="book_title" value="1" <?php if($_GET['book_title']) { echo 'checked'; } ?>>
                            <label for="book_title">Title</label>
                        <?php } ?>
                    </td>
                    <td>
                        <span>Journals:</span>
                        <?php if (!$_GET['q']) { ?>
                            <input type="checkbox" id="journal_title" name="journal_title" value="1" checked>
                            <label for="journal_title">Title</label>
                        <?php } else { ?>
                            <input type="checkbox" id="journal_title" name="journal_title" value="1" <?php if($_GET['journal_title']) { echo 'checked'; } ?>>
                            <label for="journal_title">Title</label>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <br />
        <br />
        <div>
            <input type="hidden" name="task" value="booleanSearch" />
            <input type="text" id="q" name="q" style="width: 50%" value="<?php echo htmlspecialchars($_GET['q'])?>" />
            <button id="searchSubmit"><?php echo bibliographie_icon_get('find')?></button>
        </div>
    </form>

    <div id="mouse_movement"></div>
</div>