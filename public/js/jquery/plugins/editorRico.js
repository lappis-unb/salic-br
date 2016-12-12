jQuery.fn.editorRico = function (options) {

    if(!options) {
        options = {};
    }

    var minchar = (options.minchar) ? options.minchar : -1;
    var maxchar = (options.maxchar) ? options.maxchar : 1000;
    var isLimitarCarateres = (typeof  options.isLimitarCarateres  !== 'undefined') ? options.isLimitarCarateres : true;
    var isDesabilitarEdicao = (typeof  options.isDesabilitarEdicao  !== 'undefined') ? options.isDesabilitarEdicao : false;
    var idElemento = $(this).attr('id');
    var altura = (options.altura) ? options.altura : 500;

    function contarCharacteres() {
        var body = tinymce.get(idElemento).getBody();
        var content = tinymce.trim(body.innerText || body.textContent);
        return content.length;
    }

    function execucaoDaFuncaoLimiterPag() {
        var countChars = contarCharacteres(idElemento);
        $("#contadorRico" + idElemento).html("Caracteres: " + countChars + "/" + maxchar);
        $("#contadorRico" + idElemento).css('color', 'black');
        if ((countChars > maxchar) || (countChars <= minchar)) {
            $("#contadorRico" + idElemento).css('color', 'red');
        }
    }

    tinymce.init({
        plugins: "paste,textcolor",
        language: "pt_BR",
        paste_as_text: true,
        selector: '#' + idElemento,
        height: altura,
        toolbar: "bold,italic,underline,color,forecolor backcolor,fontsizeselect",
        menubar: "",
        readonly : isDesabilitarEdicao,
        setup: function (ed) {
            if (isLimitarCarateres) {
                ed.on('init', function (e) {
                    $("#" + idElemento).after("<div id='contadorRico" + idElemento + "'></div>");
                    execucaoDaFuncaoLimiterPag('observacao');
                }).on('keyup', function (e) {
                    execucaoDaFuncaoLimiterPag('observacao');
                });
            }
        }
    });
};