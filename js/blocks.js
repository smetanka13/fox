function prodBlock(data) {
    data.image = (data.image != '') ? `material/catalog/${data.category}/${data.image}` : 'images/icons/no_photo.svg';
    var params = '';

    for(param in data.params) {
        if(data.params[param] != '')
            params += param + ': ' + data.params[param] + '<br>';
    }

    var discount = '';

    if(data.discount == 1) {
        data.price = data.price * (data.discount_percent / 100);
        discount = '<div class="discount"></div>';
    }

    return `
        <div class="prods_cnt">
            <div class="prods_wrapper">
                ${discount}
                <h3 class="title" title="${data.title}">${data.title}</h3>
                <div class="prods_img_cnt"><img src="${data.image}"></div>
                <p>${params}</p>
                <div class="prods_bottom">
                    <a href="product?category=${data.category}&id=${data.id_prod}"><button>Купить</button></a>
                    <div class="exchange-render" data-exchange="${data.price}">
                        <span class="exchange-val">${Exchange.convert(data.price)}</span>
                        <span class="exchange-currency">${Exchange.getCurrency()}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}
