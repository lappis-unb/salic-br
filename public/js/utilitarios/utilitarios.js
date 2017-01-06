/**
 * @author Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 * @since 06/01/2016 11:52
 */
utilitarios = {
    marcarDesmarcarCheckBoxes: function (seletorCheckBoxes) {
        $(this).prop('checked', !$(this).prop('checked'));
        $(seletorCheckBoxes).prop("checked", $(this).prop("checked"));
    }
}