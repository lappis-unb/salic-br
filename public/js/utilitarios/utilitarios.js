utilitarios = {
    marcarDesmarcarCheckBoxes: function (seletorCheckBoxes) {
        $(this).prop('checked', !$(this).prop('checked'));
        $(seletorCheckBoxes).prop("checked", $(this).prop("checked"));
    }
}