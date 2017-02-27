/**
 * Created by ervin on 23.02.2017.
 */
doAction = function (form, ActionId, Params) {
    var prefix = $(form).closest('div[data-formprefix]').attr('data-formprefix');
    if (typeof(prefix) == 'undefined') {
        prefix = '';
    }

    $(
        $('<input type="hidden" />')
            .attr('name', prefix ? prefix + '[do][' + ActionId + ']' : 'do[' + ActionId + ']')
            .appendTo(form)
            .val(Params)
            .get(0)
            .form
    ).submit();
};

$(document).ready(function () {
    $('th[data-order-column]').each(function () {
        var th = $(this);
        if (th.attr('data-order') == 'ASC') {
            th.append(' <sup>▲' + th.attr('data-order-position') + '</sup>');
        } else if (th.attr('data-order') == 'DESC') {
            th.append(' <sup>▼' + th.attr('data-order-position') + '</sup>');
        }
    });


    $('th[data-order-column]').on('click', function (event) {

        var th = $(this);
        var val = th.attr('data-order-column');

        if (th.attr('data-order') == '') val = val + ' ASC';
        if (th.attr('data-order') == 'ASC') val = val + ' DESC';
        if (th.attr('data-order') == 'DESC') val = val + ' ';

        if (event.ctrlKey) {
            doAction(this, 'addOrder', val);
        } else {
            doAction(this, 'order', val);
        }
    });


});
