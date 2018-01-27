Exchange = new Object();

Exchange.layout = {
    UAH: 'грн.',
    RUB: 'руб.',
    USD: '$',
};
Exchange.data = {};
Exchange.selected_currency = ($.cookie('currency') != null) ? $.cookie('currency') : 'UAH';

Exchange.update = function(callback = function(){}) {
    FW.ajax.send({
        model: 'exchange',
        method: 'get',
        callback: function(data, callback) {
            Exchange.data = data.output;
            Exchange.render();
            callback();
        },
        local_params: callback
    });
}

Exchange.render = function() {
    $('.exchange-render .exchange-currency').html(Exchange.layout[Exchange.selected_currency]);

    var renders = $('.exchange-render').each(function(i) {
        let val = $(this).attr('data-exchange');
        val = Exchange.convert(val);
        $(this).find('.exchange-val').html(val);
    });
}

Exchange.change = function(currency) {
    Exchange.selected_currency = currency;
    Exchange.update();
}

Exchange.convert = function(val) {
    return (val * Exchange.data[Exchange.selected_currency]).toFixed(2);
}

Exchange.getCurrency = function() {
    return Exchange.layout[Exchange.selected_currency];
}



setInterval(function () {

    Exchange.update();

}, 60000);
Exchange.update();
