Cart = new Object();

/**
 * Обновляет сверху визуально корзину (сверху слева)
 */
Cart.updateVisual = function() {

    var cookie = $.cookie('cart');
    if(cookie != null) {
        let quantity = Object.keys(JSON.parse(cookie)).length;
        $('.header .cart .badge').html(quantity);
        if(quantity <= 0) {
            $('.header .cart').attr('href', 'javascript:void(0)');
            $('.header .cart').onclick(function() {
                FW.ajax.error('Корзина пустая.');
            });
        }
    } else {
        return;
    }
    FW.ajax.send({
        model: 'product',
        method: 'getFullPriceCookie',
        callback: function(data) {

            $('.header .cart .exchange-render').attr('data-exchange', data.output);
            $('.header .cart .exchange-render .exchange-currency').html(Exchange.getCurrency());
            $('.header .cart .exchange-render .exchange-val').html(Exchange.convert(data.output));

        },
        data: {
            cookie: cookie
        }
    })
};

/**
 *  Добавляет продукт в корзину:
 *
 *     Каждый продукт храниться в виде объектов
 *     с уникальным ключом(рандомные числа, буквы):
 *
 *         key: {id_prod, category, quantity}
 *         "ABCD24": {24, 'Масла', 5}
 *
 *  @param {Int} id_prod - айди продукта
 *  @param category - категория продукта
 *  @param quantity - кол-во
 *  @param callback - послед. функция
 */
Cart.add = function(id_prod, category, quantity, callback = function(){}) {

    var cookie = $.cookie('cart');
    var cart = {};
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }

    var new_item = true;

    for(i in cart) {
        if(cart[i].id_prod == id_prod) {
            cart[i].quantity = quantity;
            new_item = false;
        }
    }

    if(new_item) {
        var key = FW.randomKey(8);
        cart[key] = {
            id_prod: id_prod,
            category: category,
            quantity: quantity
        };
    }
    $.cookie('cart', JSON.stringify(cart));

    this.updateVisual();
    callback();
};

/**
 *  Обновляет количество для опред. продукта по его ключу
 *
 *  @param key - уникальный ключ продукта
 *  @param quantity - новое кол-во
 */
Cart.updateQuantity = function(key, quantity, callback = function(){}) {
    var cookie = $.cookie('cart');
    var cart = {};
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }
    cart[key].quantity = quantity;
    $.cookie('cart', JSON.stringify(cart));
    this.updateVisual();
    callback(key);
};

/**
 *  Удаляет продукт из корзины по его ключу
 *
 *  @param callback - послед. функция
 *  @param key - уникальный ключ продукта
 */
Cart.remove = function(key, callback = function(){}) {
    var cookie = $.cookie('cart');
    var cart = {};
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }
    delete cart[key];
    $.cookie('cart', JSON.stringify(cart));
    this.updateVisual();
    callback(key);
};


/**
 *  Очищает карзину
 */
Cart.empty = function() {
    $('.header .cart .exchange-render').attr('data-exchange', 0);
    $('.header .cart .exchange-render .exchange-val').html(0);
    $.removeCookie('cart');
};

Cart.updateVisual();
