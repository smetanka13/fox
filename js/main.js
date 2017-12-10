$.fn.extend ({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
    }
});
function addCart(id, category, quantity, callback) {

    var cookie = $.cookie('cart');
    var cart = [];
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }

    var productIsset = false;

    for(var index in cart) {
        if(cart[index].id == id && cart[index].category == category) {
            cart[index].quantity = quantity;
            productIsset = true;
        }
    }

    if(!productIsset) {
        cart.push({
            id: id,
            category: category,
            quantity: quantity
        });
    }

    $.cookie('cart', JSON.stringify(cart));
    if(typeof(callback) != 'undefined') {
        callback();
    }
}
function removeCart(index, callback) {
    var cookie = $.cookie('cart');
    var cart = [];
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }
    cart.splice(index, 1);
    $.cookie('cart', JSON.stringify(cart));
    if(typeof(callback) != 'undefined') {
        callback(index);
    }
}
function updateCartQuantity(index, quantity, callback) {
    var cookie = $.cookie('cart');
    var cart = [];
    if(cookie != null) {
        var cart = JSON.parse(cookie);
    }
    cart[index].quantity = quantity;
    $.cookie('cart', JSON.stringify(cart));
    if(typeof(callback) != 'undefined') {
        callback(index);
    }
}
