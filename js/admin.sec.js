function tableOrd(data){
    var str = '';
    for(var j in data.prods){
        str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + data.prods[j].category + '&id=' + data.prods[j].id_prod + '"><li>' + data.prods[j].articule + '</li></a>';
    }
    return `
        <tr id="`+data.id_order+`">
            <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.id_order+`</td>
            <td>${data.public}</td>
            <td>`+data.phone+`</td>
            <td><ul class="list-unstyled">`+str+`</ul></td>
            <td>кол-во</td>
            <td>`+data.prods[j].price+`</td>
            <td>`+data.pay_way+`</td>
            <td>`+data.delivery_way+`</td>
            <td class="description">`+data.text+`</td>
            <td>`+FW.timeConverter(data.date)+`</td>
            <td class="order order_ord form-group">
                <div id="`+data.id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
                <div id="`+data.id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
                <div id="`+data.id_order+`" class="cf_ord">Заказ принят</div>
            </td>
        </tr>
    ` ;
}

function getUnaccepted() {
      FW.ajax.send({
          model: 'order',
          method: 'getUnaccepted',
          callback: function(data){
              $("#order_table tbody").empty();
              for( let index = data.output.length - 1; index >= 0 ; --index){
                  var str = '';
                  for(let j in data.output[index].prods){
                      var id_prod = data.output[index].prods[j].id_prod;
                      var articule = data.output[index].prods[j].articule;
                      var price = data.output[index].prods[j].price;
                      var category = data.output[index].prods[j].category;

                      str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
                  }
                  $('#order_table tbody').append(`
                      <tr id="${data.output[index].id_order}">
                          <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.output[index].id_order+`</td>
                          <td>${data.output[index].public}</td>
                          <td>`+data.output[index].phone+`</td>
                          <td><ul class="list-unstyled">`+str+`</ul></td>
                          <td>кол-во</td>
                          <td>`+price+`</td>
                          <td>`+data.output[index].pay_way+`</td>
                          <td>`+data.output[index].delivery_way+`</td>
                          <td class="description">`+data.output[index].text+`</td>
                          <td>`+FW.timeConverter(data.output[index].date)+`</td>
                          <td class="order order_ord form-group">
                              <div id="`+data.output[index].id_order+`" onclick="acceptOrder(${data.output[index].id_order})" class="wth_boot_but ord_but">Подтвердить</div>
                              <div id="`+data.output[index].id_order+`" onclick="deleteOrder(${data.output[index].id_order})" class="wth_boot_but no_ord_but">Отказать</div>
                              <div id="`+data.output[index].id_order+`" class="cf_ord">Заказ принят</div>
                          </td>
                      </tr>
                  `);
              }
          }
      });
}
function acceptOrder(id_order) {
  FW.ajax.send({
    model: 'order',
    method: 'accept',
    callback: getUnaccepted,
    data: {
      id_order: id_order
    }
  });
}
function deleteOrder(id_order) {
  FW.ajax.send({
    model: 'order',
    method: 'delete',
    callback: getUnaccepted,
    data: {
      id_order: id_order
    }
  });
}
function getAccepted(){
  FW.ajax.send({
      model: 'order',
      method: '',
      callback: function(data){
          $("#order_table tbody").empty();
          for( let index = data.output.length - 1; index >= 0 ; --index){
              var str = '';
              for(let j in data.output[index].prods){
                  var id_prod = data.output[index].prods[j].id_prod;
                  var articule = data.output[index].prods[j].articule;
                  var price = data.output[index].prods[j].price;
                  var category = data.output[index].prods[j].category;

                  str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
              }
              $('#order_table tbody').append(`
                  <tr id="${data.output[index].id_order}">
                      <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.output[index].id_order+`</td>
                      <td>${data.output[index].public}</td>
                      <td>`+data.output[index].phone+`</td>
                      <td><ul class="list-unstyled">`+str+`</ul></td>
                      <td>кол-во</td>
                      <td>`+price+`</td>
                      <td>`+data.output[index].pay_way+`</td>
                      <td>`+data.output[index].delivery_way+`</td>
                      <td class="description">`+data.output[index].text+`</td>
                      <td>`+FW.timeConverter(data.output[index].date)+`</td>
                      <td class="order order_ord form-group">
                          <div id="`+data.output[index].id_order+`" class="cf_ord green">Заказ принят</div>
                      </td>
                  </tr>
              `);
          }
      }
  });
}

$( document ).ready(function() {
    getUnaccepted();
    getAccepted();
    setInterval(getUnaccepted,10000);
    setInterval(getAccepted,20000);
});
$('.order_phone').click(
  function(){
      var order_but = $(this).find('.ord_but');
      var confirm_but = $(this).find('.cf_ord');

          if (order_but.css('display') == 'block') {
              order_but.fadeOut(0);
              confirm_but.addClass('text-success').fadeIn(0);
          }
      }
  );
