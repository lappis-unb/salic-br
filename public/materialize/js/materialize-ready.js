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
        elmFormsMaterialize = $('form.materialize');
        elmFormsMaterialize.find('select').material_select();
        setTimeout(function () {
            elmFormsMaterialize.find('[required=required]').closest('.input-field').find('input.select-dropdown').addClass('invalid');
            elmFormsMaterialize.find('[required=required]').addClass('invalid');
        }, 300);
    }
}($3));