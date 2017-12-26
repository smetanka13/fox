var Cart = {
    /*
        Очищает карзину

    */
    empty: function() {
        $('.header .cart .badge').html('0');
        $('.header .cart ul li:eq(1)').html('UAH: 0');
        $.removeCookie('cart');
    },
    /*
        Обновляет визуально карзину (сверху слева)

    */
    updateVisual: function() {

        var cookie = $.cookie('cart');
        if(cookie != null) {
            var cart = JSON.parse(cookie);
            $('.header .cart .badge').html(Object.keys(cart).length);
        } else {
            return;
        }



        ajaxController({
            model: 'product',
            method: 'getFullPriceCookie',
            callback: function(data) {
                $('.header .cart ul li:eq(1)').html('UAH: '+data.output.toFixed(2));
            },
            cookie: cookie
        })
    },
    /*
        Добавляет продукт в корзину:

            Каждый продукт храниться в виде объектов
            с уникальным ключом(рандомные числа, буквы):

                key: {id_prod, category, quantity}
                "ABCD24": {24, Масла, 5}

        @param id_prod - айди продукта
        @param category - категория продукта
        @param quantity - кол-во
        @param callback - послед. функция
    */
    add: function(id_prod, category, quantity, callback = function(){}) {

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
    },
    /*
        Удаляет продукт из корзины по его ключу

        @param key - уникальный ключ продукта
        @param callback - послед. функция
    */
    remove: function(key, callback = function(){}) {
        var cookie = $.cookie('cart');
        var cart = {};
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        }
        delete cart[key];
        $.cookie('cart', JSON.stringify(cart));
        this.updateVisual();
        callback(key);
    },
    /*
        Обновляет количество для опред. продукта по его ключу

        @param key - уникальный ключ продукта
        @param quantity - новое кол-во
    */
    updateQuantity: function(key, quantity, callback = function(){}) {
        var cookie = $.cookie('cart');
        var cart = {};
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        }
        cart[key].quantity = quantity;
        $.cookie('cart', JSON.stringify(cart));
        this.updateVisual();
        callback(key);
    }
};
