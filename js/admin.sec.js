// function tableOrd(data) {
//     var str = '';
//     for(var j in data.prods){
//         str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + data.prods[j].category + '&id=' + data.prods[j].id_prod + '"><li>' + data.prods[j].articule + '</li></a>';
//     }
//     return `
//         <tr id="`+data.id_order+`">
//             <td><i class="fa fa-lightbulb-o fa-fw fa-lg" aria-hidden="true"></i> `+data.id_order+`</td>
//             <td>${data.public}</td>
//             <td>`+data.phone+`</td>
//             <td><ul class="list-unstyled">`+str+`</ul></td>
//             <td>кол-во</td>
//             <td>`+data.prods[j].price+`</td>
//             <td>`+data.pay_way+`</td>
//             <td>`+data.delivery_way+`</td>
//             <td class="description">`+data.text+`</td>
//             <td>`+FW.timeConverter(data.date)+`</td>
//             <td class="order order_ord form-group">
//                 <div id="`+data.id_order+`" class="wth_boot_but ord_but">Подтвердить</div>
//                 <div id="`+data.id_order+`" class="wth_boot_but no_ord_but">Отказать</div>
//             </td>
//         </tr>
//     ` ;
// }
function getUnaccepted() {
  FW.ajax.send({
      model: 'order',
      method: 'getUnaccepted',
      callback: function(data) {
        // if ( data.output == 0 ) {
        //   $('#order_table').empty();
        //   $('#order_table').html('<h4>Пока что нет заказов</h4>');
        // } else {
          $("#order_table tbody").empty();
          for( let index = data.output.length - 1; index >= 0 ; --index) {
              var str = '';
              var count = '';
              for(let j in data.output[index].prods){
                  var id_prod = data.output[index].prods[j].id_prod;
                  var articule = data.output[index].prods[j].articule;
                  var price = data.output[index].price;
                  var category = data.output[index].prods[j].category;
                  var quantity = data.output[index].prods[j].quantity;

                  str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
                  count += '<li>' + quantity + '</li>';
              }
              $('#order_table tbody').append(`
                  <tr id="${data.output[index].id_order}">
                      <td>`+data.output[index].id_order+`</td>
                      <td>${data.output[index].public}</td>
                      <td>`+data.output[index].phone+`</td>
                      <td><ul class="list-unstyled">`+str+`</ul></td>
                      <td><ul class="list-unstyled">`+count+`</ul></td>
                      <td>`+price+`</td>
                      <td>`+data.output[index].pay_way+`</td>
                      <td>`+data.output[index].delivery_way+`</td>
                      <td class="description">`+data.output[index].text+`</td>
                      <td>`+FW.timeConverter(data.output[index].date)+`</td>
                      <td class="order order_ord form-group">
                          <div id="`+data.output[index].id_order+`" onclick="acceptOrder(${data.output[index].id_order})" class="wth_boot_but ord_but">Подтвердить</div>
                          <div id="`+data.output[index].id_order+`" onclick="deleteOrder(${data.output[index].id_order})" class="wth_boot_but no_ord_but">Отказать</div>
                      </td>
                  </tr>
              `);
          }
        // }
      }
  });
}
function getAccepted(){
  FW.ajax.send({
      model: 'order',
      method: 'getAccepted',
      callback: function(data) {
        // if ( data.output == 0 ) {
        //   $('#accept_order_table').empty();
        //   $('#accept_order_table').html('<h4>Пока что нет заказов</h4>');
        // } else {
          $("#accept_order_table tbody").empty();
          for( let index = data.output.length - 1; index >= 0 ; --index) {
              var str = '';
              var count = '';
              for(let j in data.output[index].prods){
                  var id_prod = data.output[index].prods[j].id_prod;
                  var articule = data.output[index].prods[j].articule;
                  var price = data.output[index].price;
                  var category = data.output[index].prods[j].category;
                  var quantity = data.output[index].prods[j].quantity;

                  str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
                  count += '<li>' + quantity + '</li>';
              }
              $('#accept_order_table tbody').append(`
                  <tr id="${data.output[index].id_order}">
                      <td>`+data.output[index].id_order+`</td>
                      <td>${data.output[index].public}</td>
                      <td>`+data.output[index].phone+`</td>
                      <td><ul class="list-unstyled">`+str+`</ul></td>
                      <td><ul class="list-unstyled">`+count+`</ul></td>
                      <td>`+price+`</td>
                      <td>`+data.output[index].pay_way+`</td>
                      <td>`+data.output[index].delivery_way+`</td>
                      <td class="description">`+data.output[index].text+`</td>
                      <td id="time">`+FW.timeConverter(data.output[index].date)+`</td>
                  </tr>
              `);
          }
        // }
      }
  });
}
function getPhone() {
  FW.ajax.send({
    model: 'callback',
    method: 'get',
    callback: function (data) {
      // if ( data.output == 0 ) {
      //   $('#accept_phone').empty();
      //   $('#accept_phone').html('<h4>Пока что нет запросов перезвонить</h4>');
      // } else {
        $("#accept_phone tbody").empty();
        for( let index = data.output.length - 1; index >= 0 ; --index) {
          $('#accept_phone tbody').append(`
            <tr id="${data.output[index].id_callback}">
                <td>${data.output[index].phone}</td>
                <td class="order order_phone">
                    <div onclick="acceptPhone('${data.output[index].phone}')" class="wth_boot_but ord_but">Подтвердить</div>
                </td>
                <td>${FW.timeConverter(data.output[index].date)}</td>
            </tr>
          `);
        }
      // }
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
function acceptPhone(phone) {
  FW.ajax.send({
    model: 'callback',
    method: 'delete',
    callback: getPhone,
    data: {
      phone: phone
    }
  });
}
$( document ).ready(function() {
    getUnaccepted();
    getAccepted();
    getPhone();
    setInterval(getUnaccepted,10000);
    setInterval(getAccepted,20000);
    setInterval(getPhone,10000);
});
