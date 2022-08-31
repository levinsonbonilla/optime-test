// SELECT2
var idSelect2 = $(idSelect).select2({
    theme: "bootstrap-5"
});

//Price
$(idPrice).on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        // .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
});

//replace characters
$(idInputReplace).on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/[^a-zA-Z0-9]/g, "")
                        // .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(" ", "");
        });
    }
});
