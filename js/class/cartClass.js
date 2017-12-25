var Cart = {
    empty: function() {
        $('.header .cart .badge').html('0');
        $('.header .cart ul li:eq(1)').html('UAH: 0');
        $.cookie('cart', JSON.stringify(cart));
    },
    updateVisual: function() {

        var cookie = $.cookie('cart');
        var cart = [];
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        } else {
            return;
        }

        $('.header .cart .badge').html(cart.length);

        ajaxController({
            model: 'product',
            method: 'getFullPriceCookie',
            callback: function(data) {
                $('.header .cart ul li:eq(1)').html('UAH: '+data.output.toFixed(2));
            },
            cookie: cookie
        })
    },
    add: function(id_prod, category, quantity, callback) {

        var cookie = $.cookie('cart');
        var cart = [];
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        }

        var productIsset = false;

        for(var index in cart) {
            if(cart[index].id_prod == id_prod && cart[index].category == category) {
                cart[index].quantity = quantity;
                productIsset = true;
            }
        }

        if(!productIsset) {
            cart.push({
                id_prod: id_prod,
                category: category,
                quantity: quantity
            });
        }

        this.updateVisual();

        $.cookie('cart', JSON.stringify(cart));
        if(typeof(callback) != 'undefined') {
            callback();
        }
    },
    remove: function(index, callback) {
        var cookie = $.cookie('cart');
        var cart = [];
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        }
        cart.splice(index, 1);
        this.updateVisual();
        $.cookie('cart', JSON.stringify(cart));
        if(typeof(callback) != 'undefined') {
            callback(index);
        }
    },
    updateQuantity: function(index, quantity, callback) {
        var cookie = $.cookie('cart');
        var cart = [];
        if(cookie != null) {
            var cart = JSON.parse(cookie);
        }
        cart[index].quantity = quantity;
        this.updateVisual();
        $.cookie('cart', JSON.stringify(cart));
        if(typeof(callback) != 'undefined') {
            callback(index);
        }
    }
};
