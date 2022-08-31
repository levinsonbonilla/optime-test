

import './styles/app.scss';
import './bootstrap';
// import 'select2';
// import 'select2/dist/css/select2.css';


//Bootstrap framework
window.bootstrap = require("bootstrap");


//JQuery
const $ = require('jquery');
global.$ = global.jQuery = require('jquery')

//select2
require("select2");

//Datatables
require( 'pdfmake' );
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;
require( 'jszip' );
require( 'datatables.net-bs5' );
require( 'datatables.net-buttons-bs5' );
require( 'datatables.net-buttons/js/buttons.html5.js' );
require( 'datatables.net-buttons/js/buttons.print.js' );
require( 'datatables.net-fixedheader-bs5' );
//require( 'datatables.net-responsive-bs5' );

//Form basic validation
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})()
