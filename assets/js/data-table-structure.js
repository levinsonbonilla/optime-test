var numberKeyup = 0;
var filterApplied = false;
var actionDocument = "";
var eT, dtT, nodeT, configT, T;

var debounce = function (func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

function filterColumn (column) {
    let filterJson = JSON.stringify(filtersDataTable());
    $(tableName).DataTable().search(
        filterJson
    ).draw();
}

function getDate(){
    let d = new Date(); 
    let now = d.getFullYear() + "-" + (((d.getMonth()+1).length > 1)?d.getMonth()+1:'0'+(d.getMonth()+1))  + "-" + ((d.getDate().lenght > 1)?d.getDate():d.getDate());
    return now;
}

jQuery(function() {
    var table = $(tableName).DataTable({
        dom: 'Brtip',
        searchDelay: 350,
        language: {
            paginate: {
            next: '&#8594;',
            previous: '&#8592;'
            }
        },
        "ordering": true,
        "processing": false,
        buttons: [{extend:'excel',
        exportOptions: {
            columns:':not(.notexport)'}
        },
        {extend:'pdf',
        action: function ( e, dt, node, config ) {
            table.page.len(-1).draw();
            actionDocument = "pdf";
            eT = e;
            dtT = dt;
            nodeT = node;
            configT = config;
            T = this;
        },
        exportOptions: {
            columns:':not(.notexport)'}
        },
        {extend:'print',
        action: function ( e, dt, node, config ) {
            table.page.len(-1).draw();
            actionDocument = "print";
            eT = e;
            dtT = dt;
            nodeT = node;
            configT = config;
            T = this;
        },
        exportOptions: {
            columns:':not(.notexport)'}
        },
        {extend:'csv',
        action: function ( e, dt, node, config ) {
            table.page.len(-1).draw();
            actionDocument = "csv";
            eT = e;
            dtT = dt;
            nodeT = node;
            configT = config;
            T = this;
        },
        exportOptions: {
            columns:':not(.notexport)'}
        },            
        ],
        "columnDefs": columnDefs,
        "serverSide": true,
        "paging" : true,
        "info" : true,
        "searching": true,
        "ajax": ajaxPath,
        "language": {
            "lengthMenu": lengthMenu,
            "zeroRecords": zeroRecords,
            "info": info,
            "infoEmpty": infoEmpty,
            "emptyTable": emptyTable,
            "loadingRecords": loadingRecords, 
            "processing": processing,
            "search": search+":",
            "infoFiltered": infoFiltered,
            "paginate": {
                next: next,
                previous: previous
                },
        },
        initComplete: function() {
            $('.dt-buttons').appendTo($('.list-buttons'))
            $(tableName+'_paginate').appendTo($('.worklist-paginate'))
        }
    });
    
   $('input.column_filter').keyup(debounce(function(){
        if ('date' != this.type) {
            numberKeyup ++;
            if( this.value.length < 4 && (numberKeyup - 1) <= this.value.length ) return;
            numberKeyup = this.value.length;
            filterColumn(this.id);
        }
    },500));

    $('input.column_filter').on( 'change click', function () {
        if ('date' == this.type) {
            filterColumn(this.id);
        }
    } );           

    $('select.column_filter').on( 'change', function () {
        filterColumn(this.id);
    } );

    $(tableName+' tbody').on('click', 'button.buttonEdit', function() {
        let row = table.row($(this).parents('tr')).data();
        redirect(pathEdit,row);
    });

    $(tableName+' tbody').on('click', 'button.buttonDetails', function() {
        let row = table.row($(this).parents('tr')).data();
        redirect(pathDetails,row);
    });

    function redirect(pathPartner,row) {
        $(location).prop('href', pathPartner.replace('change',row[rowId]));
    }

    $.fn.dataTable.ext.errMode = 'none'; 
    
    $(tableName).DataTable().on("draw", function(){
        switch (actionDocument) {
            case "pdf":
                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(T, eT, dtT, nodeT, configT);
                table.page.len(10).draw();
                break;
            case "print":
                $.fn.dataTable.ext.buttons.print.action.call(T, eT, dtT, nodeT, configT);
                table.page.len(10).draw();
                break;
            case "csv":
                $.fn.dataTable.ext.buttons.csvHtml5.action.call(T, eT, dtT, nodeT, configT);
                table.page.len(10).draw();
                break;
            default:
                break;
        }
        actionDocument = "";
        eT = "";
        dtT = "";
        nodeT = "";
        configT = "";
        T = "";
    })
    
} );
