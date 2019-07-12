import "../css/emailAvailibilities.scss";

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

require("startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.min");

$(document).ready(function () {
    $('#volunteers_table').DataTable();
});

ClassicEditor
    .create(document.querySelector('#prepare_email_availibilities_body'))
    .catch(error => {
        console.error(error);
    });

var ignoredUsers = [];

//prevent unchecked box after refresh
$(".volunteers-checkbox").prop( "checked", true );

$(".volunteers-checkbox").click(function () {
    if ($(this).is(':checked')) {
        remove(ignoredUsers, $(this).val());
        $('[name=volunteers]').val(ignoredUsers);
    } else {
        ignoredUsers.push($(this).val());
        $('[name=volunteers]').val(ignoredUsers);
    }
});

function remove(array, element) {
    const index = array.indexOf(element);
    array.splice(index, 1);
}