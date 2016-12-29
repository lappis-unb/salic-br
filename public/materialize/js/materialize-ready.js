(function($){
    $('.tooltipped').tooltip();
    $( document ).ajaxComplete(function() {
       $('.tooltipped').tooltip();
    });

    $3('.modal').find('select').material_select();
    setTimeout(function(){
        $3('.modal').find('[required=required]').closest('.input-field').find('input.select-dropdown').addClass('invalid');
        $3('.modal').find('[required=required]').addClass('invalid');
    }, 300);
}($3));