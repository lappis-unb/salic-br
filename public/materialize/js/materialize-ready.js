(function($){
    $(document).ready(function () {
        ready();
    });

    $( document ).ajaxSuccess(function() {
        ready();
    });

    function ready()
    {
        $('.tooltipped').tooltip();
        $('.container.fade').fadeIn(1500);
        Materialize.updateTextFields();
        elmFormsMaterialize = $('form.materialize');
        elmFormsMaterialize.find('select').material_select();
        var arrObj = elmFormsMaterialize.find('[required=required]').filter(function(){
            if ($(this).closest('.input-field').find('label').find('b.red-text').length > 0) {
                return false;
            } else {
                return true;
            }
        });
        $(arrObj).each(function (){
            var elm = $(this),
                elmLabel = elm.closest('.input-field').find('label');
            elmLabel.html(elmLabel.html() + ' <b class="red-text">*</b>')
        });
        // elmInputInvalid = $('input.invalid');
        // $(elmInputInvalid).each(function(){
        //     var elmInput = $(this),
        //         ;
        //
        // });
        // elmInputInvalid

        setTimeout(function () {
            // Materialize.updateTextFields();
            // elmFormsMaterialize.find('[required=required]').closest('.input-field').find('input.select-dropdown').addClass('invalid');
            // elmFormsMaterialize.find('[required=required]').addClass('invalid');
        }, 300);
    }
}($3));