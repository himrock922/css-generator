jQuery(function() {
    $('#tag_add').on('click', function() {
        $('#table_tag').append("<tr><td><label for='block'>固定値</label><input type='text' name=css[block][] size='20'></td></tr>");
    });
    $('#tag_delete').on('click', function() {
        $('#table_tag tr:last-child').remove();
    });
});