import "../css/emailAvailibilities.scss";

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

require("startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.min");
require("datatables.net-select-bs4");


$(document).ready(function () {
    $('#table_id').DataTable();
});


ClassicEditor
    .create(document.querySelector('#prepare_email_availibilities_body'))
    .catch(error => {
        console.error(error);
    });

