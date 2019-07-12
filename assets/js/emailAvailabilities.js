import "../css/emailAvailibilities.scss";

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

require("startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.min");

$(document).ready(function () {

    //init ckeditor
    ClassicEditor
        .create(document.querySelector('#prepare_email_availabilities_body'), {

        })
        .catch(error => {
            console.error(error);
        });

    //init dataTable
    $('#volunteers_table').DataTable();

    var ignoredUsers = [];

    //prevent unchecked box after refresh
    $(".volunteers-checkbox").prop("checked", true);

    //click on users checkbox
    $(".volunteers-checkbox").click(function () {
        if ($(this).is(':checked')) {
            remove(ignoredUsers, $(this).val());
            $('[name=ignoredUsers]').val(ignoredUsers);
        } else {
            ignoredUsers.push($(this).val());
            $('[name=ignoredUsers]').val(ignoredUsers);
        }
    });
});


//remove value into array
function remove(array, element) {
    const index = array.indexOf(element);
    array.splice(index, 1);
}