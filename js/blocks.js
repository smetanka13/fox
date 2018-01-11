function prodBlock(data) {
    data.image = (data.image != '') ? `material/catalog/${data.category}/${data.image}` : 'images/icons/no_photo.svg';
    var params = '';

    for(param in data.params) {
        if(data.params[param] != '')
            params += param + ': ' + data.params[param] + '<br>';
    }

    return `
        <div class="prods_cnt">
            <div class="prods_wrapper">
                <h3 class="title">${data.title}</h3>
                <div class="prods_img_cnt"><img src="${data.image}"></div>
                <p>${params}</p>
                <div class="prods_bottom">
                    <a href="product?category=${data.category}&id=${data.id_prod}"><button>КУПИТЬ</button></a>
                    <h4>${data.price} &euro;</h4>
                </div>
            </div>
        </div>
    `;
}
