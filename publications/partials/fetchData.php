<?php

bibliographie_history_append_step('publications', 'Fetch data from external source');
unset($_SESSION['publication_prefetchedData_checked']);
unset($_SESSION['publication_prefetchedData_unchecked']);
?>

    <h3>Fetch data for publication creation</h3>
    <div id="fetchData_sourceSelect">
        <label for="source" class="block"><?php echo bibliographie_icon_get('page-white-get')?> Select the source of which you want to import from!</label>
        <select id="source" name="source" style="width: 50%;">
            <option value="direct">Direct input</option>
            <option value="remote">Remote file</option>
            <option value="pubmed">PubMed</option>
            <?php
            if(BIBLIOGRAPHIE_ISBNDB_KEY != '')
                echo '<option value="isbndb">ISBNDB.com</option>';
            ?>

        </select>
        <button onclick="bibliographie_publications_fetch_data_proceed({'source': $('#source').val(), 'step': '1'})">Select!</button>
    </div>
    <p><hr /></p>
    <div id="fetchData_container"></div>
    <script type="text/javascript">
        /* <![CDATA[ */
        $('#source').on('change select', function () {
            bibliographie_publications_fetch_data_proceed({'source': $('#source').val(), 'step': '1'})
        });
        $(function () {
            bibliographie_publications_fetch_data_proceed({'source': 'direct', 'step': '1'});
        })
        /* ]]> */
    </script>
<?php