/*
* Adiciona metodo para validar se textarea com TinyMice contem valor
*/
jQuery.validator.addMethod("requiredTinyMice", function(value, element) {

    length = tinymce.trim(tinymce.activeEditor.getBody().innerText).length;
    return  (length > 0);
}, "");
