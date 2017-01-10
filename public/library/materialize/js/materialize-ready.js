(function($){
    $(document).ready(function () {
        ready();

        // Extension pour comptabilité avec materialize.css
        $.validator.setDefaults({
            errorClass: 'invalid',
            validClass: "valid",
            errorPlacement: function (error, element) {
                var elmForm = $(element).closest("form"),
                    elmIcon = $(element).closest('input-field').find('i'),
                    elmLabel = elmForm.find("label[for='" + $(element).attr("id") + "']");
                elmLabel.attr('data-error', error.text());
                elmLabel.removeClass('green-text');
                elmIcon.removeClass('green-text');
                elmLabel.addClass('red-text');
                elmIcon.addClass('red-text');
            },
            success: function (error, element) {
                var elmForm = $(element).closest("form"),
                    elmIcon = $(element).closest('div').find('i'),
                    elmLabel = elmForm.find("label[for='" + $(element).attr("id") + "']");
                elmLabel.removeClass('red-text');
                elmIcon.removeClass('red-text');
                elmLabel.addClass('green-text');
                elmIcon.addClass('green-text');
            }
            // submitHandler: function (form) {
            //     console.log('form ok');
            //     return false;
            // }
        });
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

        setTimeout(function () {
            Materialize.updateTextFields();
            // elmFormsMaterialize.find('[required=required]').closest('.input-field').find('input.select-dropdown').addClass('invalid');
            // elmFormsMaterialize.find('[required=required]').addClass('invalid');
        }, 300);
    }
}($3));