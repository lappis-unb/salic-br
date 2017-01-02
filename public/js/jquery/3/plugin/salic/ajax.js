/**
 * Plugins jQuery
 */
(function ($) {
    $(document).ready(function ($) {
        var elmBody = $('body');
        elmBody.on('click', '[data-ajax-modal]', function () {

            var objConfig = {};
            objConfig.strUrl = $(this).attr('data-ajax-modal');
            if ($(this).attr('data-ajax-modal-type') !== '') {
                objConfig.strType = $(this).attr('data-ajax-modal-type');
            }
            if ($(this).attr('data-ajax-modal-height') !== '') {
                objConfig.strHeight = $(this).attr('data-ajax-modal-height');
            }

            $.ajaxModal(objConfig);

        });
        // $('body').on('change', '[data-ajax-render]:not(select)', function() {
        //     console.info('aaa');
        //     $.ajaxModal({strUrl: $(this).attr('data-ajax-render'), strTarget: $(this).attr('data-ajax-target')});
        // });
        elmBody.on('change', 'select[data-ajax-render]', function () {
            var elm = $(this),
                strUrl = elm.attr('data-ajax-render'),
                objTarget = $(elm.attr('data-ajax-target')),
                strVal = elm.val(),
                strValChecked = '';
            var strHtml = '';
            $3.ajax({
                method: "POST",
                url: strUrl,
                data: {id: strVal}
            }).done(function (result) {
                json = $3.parseJSON(result);
                strHtml = '<option value="" selected>Selecione...</option>';
                $3.each(json, function(key, value){
                    strHtml += '<optgroup label="' + key + '">';
                    $3.each(value, function(key2, value2){
                        if (strValChecked && strValChecked == key2) {
                            strHtml += '<option value="' + key2 + '" selected="selected">' + value2 + '</option>';
                        } else {
                            strHtml += '<option value="' + key2 + '">' + value2 + '</option>';
                        }
                    });
                    strHtml += '</optgroup>';
                });
                objTarget.html(strHtml);
                objTarget.material_select();
            });
        });

        // Pegando as div com ajax-render para renderizar automaticamente o ajax no elemento.
        $.each($('div[data-ajax-render]'), function () {
            $.ajaxRender({strUrl: $(this).attr('data-ajax-render'), strTarget: '#' + $(this).attr('id')});
        });

        $('form[data-ajax-form]').on('click', 'button[type=submit]', function(){
            var elmForm = $(this).closest('form[data-ajax-form]'),
                strRedirect = elmForm.attr('data-ajax-form-redirect');

            if ($('#dsMensagem').length > 0) {
                $('#dsMensagem').val(tinyMCE.get('dsMensagem').getContent());
            }
            if ($('#dsResposta').length > 0) {
                $('#dsResposta').val(tinyMCE.get('dsResposta').getContent());
            }
            strSerialize = elmForm.serialize();

            if (elmForm.valid()) {
                $3.post(elmForm.attr('action'), strSerialize, function (result) {
                    result = $3.parseJSON(result);
                    if (result.status == '1') {
//                    $3('#form-mensagem')[0].reset();
                        Materialize.toast(result.msg, 4000, 'green light-green accent-1 black-text');
                        setTimeout(function(){
                            window.location.href = strRedirect;
                        }, 500);
                    } else {
                        if (typeof result.msg != 'string') {
                            $3.each(result.msg, function(strNameElement, strMsg){
                                var elm = $3('[name=' + strNameElement + ']'),
                                    elmLabel = elm.closest('div.input-field').find('label'),
                                    strLabel = elm.closest('div.input-field').find('label').html();
                                if (elm.length > 0) {
//                                    elmLabel.attr('data-error', 'Este campo e obrigatorio!');
//                                    elm.addClass('invalid');
                                    elm.focus();
                                    Materialize.toast('Campo ' + strLabel + ' &eacute; obrigat&oacute;rio!', 8000);
                                }
                            });
                        } else {
                            Materialize.toast(result.msg, 4000, 'red accent-1');

                            $('.card').removeClass('fadeInUp');
                            $('.card').removeClass('animated');
                            strAnimate = 'jello';
//            strAnimate = 'wobble';
//            strAnimate = 'pulse';
                            $('.card').addClass(strAnimate);
                            $('.card').addClass('animated');
                            $('.card').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                                $('.card').removeClass(strAnimate);
                                $('.card').removeClass('animated');
                            });
                        }
                    }
                });
            } else {
                $('.card').removeClass('fadeInUp');
                $('.card').removeClass('animated');
                strAnimate = 'jello';
//            strAnimate = 'wobble';
//            strAnimate = 'pulse';
                $('.card').addClass(strAnimate);
                $('.card').addClass('animated');
                $('.card').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    $('.card').removeClass(strAnimate);
                    $('.card').removeClass('animated');
                });
            }
            return false;
        });


    });

    $(document).ajaxStart(function () {
        $('#container-progress').fadeIn('slow');
    });
    $(document).ajaxComplete(function () {
        $('.container').fadeIn(1500);
        // setTimeout(function(){
            $('#container-progress').fadeOut('slow');
        // }, 4000);
    });

    //    function isValid(strElement)
    //    {
    //        arrElemento = $3(strElement).find('[required]').filter(function() {
    //            return !this.value;
    //        });
    //
    //        $3.each(arrElemento, function(key, value){
    //            console.info(value);
    //        });
    //
    //        if (arrElemento) {
    //            return false;
    //        }
    //
    //        console.info(arrElemento);
    //    }
// function ajaxRender(strUrl, objTarget, strVal, strValChecked) {
//     var strHtml = '';
//     $3.ajax({
//         method: "POST",
//         url: strUrl,
//         data: {id: strVal}
//     }).done(function (result) {
//         json = $3.parseJSON(result);
//         strHtml = '<option value="" selected>Selecione...</option>';
//         console.info(json)
//         $3.each(json, function(key, value){
//             strHtml += '<optgroup label="' + key + '">';
//             console.info(strValChecked)
//             $3.each(value, function(key2, value2){
//                 if (strValChecked && strValChecked == key2) {
//                     strHtml += '<option value="' + key2 + '" selected="selected">' + value2 + '</option>';
//                 } else {
//                     strHtml += '<option value="' + key2 + '">' + value2 + '</option>';
//                 }
//             });
//             strHtml += '</optgroup>';
//         });
//         objTarget.html(strHtml);
//         objTarget.material_select();
//     });
// }

//    function isValid(strElement)
//    {
//        arrElemento = $3(strElement).find('[required]').filter(function() {
//            return !this.value;
//        });
//
//        $3.each(arrElemento, function(key, value){
//            console.info(value);
//        });
//
//        if (arrElemento) {
//            return false;
//        }
//
//        console.info(arrElemento);
//    }

    /**
     * Cria uma div modal, executa um ajax renderizando o retorno dentro da modal e no final abre a modal com o conteudo renderizado.
     *
     * @param objOption - Objeto json de configuracoes.
     * @param objOption.strUrl - Url da tela que sera renderizado pelo ajax.
     * @param objOption.strIdModal - Titulo da modal.
     * @param callback - Funcao callback onde e executado apos terminar o ajax.
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 28/12/2016
     */
    $.ajaxModal = function (objOption, callback) {
        var objDefaults = {strUrl: '', strIdModal: 'modal', strType: 'modal-fixed-footer', strHeight: ''},
            objSettings = $.extend({}, objDefaults, objOption),
            strIdModal = '#' + objSettings.strIdModal;
console.info(objSettings.strHeight)
        // Removendo e criando elemento div para o modal.
        $(strIdModal).remove();
        $('body').append('<div id="' + objSettings.strIdModal + '" class="modal ' + objSettings.strType + '" style="height: '+ objSettings.strHeight +'"></div>');
        $(strIdModal).modal();

        // Renderizando ajax e abrindo a modal por callback.
        objSettings.strTarget = strIdModal;
        $.ajaxRender(objSettings, function () {
            if (typeof callback == 'function') {
                callback.call(this);
            }
            $(strIdModal).modal('open');
        });
    };

    /**
     * Cria uma div modal, executa um ajax renderizando o retorno dentro da modal e no final abre a modal com o conteudo renderizado.
     *
     * @param objOption - Objeto json de configuracoes.
     * @param objOption.strUrl - Url da tela que sera renderizado pelo ajax.
     * @param objOption.strTarget - String da tag html (id, class, entre outros) para ser pego com jquery.
     * @param callback - Funcao callback onde e executado apos terminar o ajax.
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 28/12/2016
     */
    $.ajaxRender = function (objOption, callback) {
        var objDefaults = {strUrl: '', strTarget: ''},
            objSettings = $.extend({}, objDefaults, objOption),
            elmRender = $(objSettings.strTarget),
            elmProgress = $('#container-progress-part').clone();

        var time = true;
        setTimeout(function(){
            if (time) {
                elmRender.hide().html('<div class="center-align" style="height: 100%;"><div class="preloader-wrapper big active valign ">' + elmProgress.html() + '</div></div>').fadeIn();
            }
        }, 250);
        $.ajax({url: objSettings.strUrl}).done(function (result) {
            // setTimeout(function () {
                time = false;
                elmRender.hide().html(result).fadeIn('slow');
                if (typeof callback == 'function') {
                    callback.call(null, result);
                }
            // }, 2000)
        });
    };

    // $3('body').on('change', 'select[data-target]', function () {
    //     var select = $3(this),
    //         strUrl = select.attr('data-url'),
    //         objTarget = $3(select.attr('data-target'));
    //     ajaxRender(strUrl, objTarget, select.val(), '');
    // });
    // function ajaxRender(strUrl, objTarget, strVal, strValChecked) {
    //     var strHtml = '';
    //     $3.ajax({
    //         method: "POST",
    //         url: strUrl,
    //         data: {id: strVal}
    //     }).done(function (result) {
    //         json = $3.parseJSON(result);
    //         strHtml = '<option value="" selected>Selecione...</option>';
    //         $3.each(json, function(key, value){
    //             strHtml += '<optgroup label="' + key + '">';
    //             $3.each(value, function(key2, value2){
    //                 if (strValChecked && strValChecked == key2) {
    //                     strHtml += '<option value="' + key2 + '" selected="selected">' + value2 + '</option>';
    //                 } else {
    //                     strHtml += '<option value="' + key2 + '">' + value2 + '</option>';
    //                 }
    //             });
    //             strHtml += '</optgroup>';
    //         });
    //         objTarget.html(strHtml);
    //         objTarget.material_select();
    //         objTarget.closest('.input-field').find('input.select-dropdown').addClass('invalid');
    //     });
    // }

    // $.fn.flexGridRedirect = function(strRoute)
    // {
    //     var intId = $(this).closest('tr').attr('id').replace('row','');
    //     var strUrl = strRoute + '/' + intId;
    //     $.redirect(strUrl);
    // }
    //
    // $.redirect = function(strRoute) {
    //     window.location.href = strGlobalBasePath + strRoute;
    // }
    //
    // $.flexGridDeleteAfter = function (strResult)
    // {
    //     objResult = getJsonObject(strResult);
    //
    //     if (objResult.strStatus == 1) {
    //         $('table[summary=flexigridTable]').flexReload();
    //         $('table[summary=flexigridTable]').attr('data-load-flexigrid', '1');
    //     }
    //
    //     $.dialogAlert({mixText: objResult.strMessage});
    // }
    //
    // $.flexGridDelete = function(strUrl)
    // {
    //     ajaxRequest(strGlobalBasePath + strUrl, '', '$.flexGridDeleteAfter');
    // }
    //
    // /**
    //  * Abre modal com apenas um botao de OK.
    //  *
    //  * @param objOption.mixText - Conteudo que fica no centro da modal.
    //  * @param objOption.strTitle - Titulo da modal.
    //  * @param objOption.intWidth - Largura da modal.
    //  * @param objOption.intHeight - Altura da modal.
    //  * @param objOption.strEvalOk - Funcao JS.
    //  * @param objOption.booModal
    //  * @param objOption.booResizable
    //  * @param objOption.strName
    //  * @param objOption.strEvalClose
    //  * @param objOption.booFocusButton
    //  */
    // $.dialogAlert = function (objOption)
    // {
    //     var objDefaults = {
    //         'mixText' : '',
    //         'strTitle': 'Aviso!',
    //         'intWidth': '500',
    //         'intHeight': '200',
    //         'strEvalOk': '',
    //         'booModal': '',
    //         'booResizable': '',
    //         'strName': '',
    //         'strEvalClose': '',
    //         'booFocusButton': ''
    //     };
    //     var objSettings = $.extend( {}, objDefaults, objOption );
    //     alertDialog(objSettings.mixText, objSettings.strTitle, objSettings.intWidth, objSettings.intHeight, objSettings.strEvalOk, objSettings.booModal, objSettings.booResizable, objSettings.strName, objSettings.strEvalClose, objSettings.booFocusButton);
    //
    // }
    //
    //
    // /**
    //  * Abre modal com botoes de confirmacao.
    //  *
    //  * @param objOption.mixText - Conteudo que fica no centro da modal.
    //  * @param objOption.strTitle - Titulo da modal.
    //  * @param objOption.intWidth - Largura da modal.
    //  * @param objOption.intHeight - Altura da modal.
    //  * @param objOption.strEvalOk - Funcao JS.
    //  * @param objOption.strEvalCancel - Funcao JS.
    //  * @param objOption.booModal
    //  * @param objOption.booResizable
    //  * @param objOption.strName
    //  * @param objOption.strEvalClose
    //  * @param objOption.booFocusButton
    //  */
    // $.dialogConfirm = function( objOption ) {
    //
    //     var objDefaults = {
    //         'mixText' : '',
    //         'strTitle': 'Aviso!',
    //         'intWidth': '500',
    //         'intHeight': '200',
    //         'strEvalOk': '',
    //         'strEvalCancel': '',
    //         'booModal': '',
    //         'booResizable': '',
    //         'strName': '',
    //         'strEvalClose': '',
    //         'booFocusButton': ''
    //     };
    //
    //     var objSettings = $.extend( {}, objDefaults, objOption );
    //
    //     confirmDialog(
    //         objSettings.mixText,
    //         objSettings.strTitle,
    //         objSettings.intWidth,
    //         objSettings.intHeight,
    //         objSettings.strEvalOk,
    //         objSettings.strEvalCancel,
    //         objSettings.booModal,
    //         objSettings.booResizable,
    //         objSettings.strName,
    //         objSettings.strEvalClose,
    //         objSettings.booFocusButton
    //     );
    // };

}($3));