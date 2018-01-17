var info = $('.personal_info');
var info_edit = $('.personal_info_edit');
var edit = $('.remark');

function roomEdit()
{
	if (info.css('display')=='block')
	{
		info.hide();
		edit.hide();
		info_edit.show();
	}

	else
	{
		info.show();
	}
}

function undo()
{
	$('.inf_ed_inp').val('');
	if (info_edit.css('display') == 'block')
	{
		info_edit.hide();
		edit.show();
		info.show();
	}

	else
	{
		info.hide();
	}
}

function save(json)
{
	var data = JSON.parse(json);

	if (data.status == false) {
		if (typeof(data.system) != 'undefined')
		{
			alert(data.system);
		}
		if (typeof(data.pass) != 'undefined')
		{
			alert(data.pass);
		}
		if (typeof(data.confirm) != 'undefined')
		{
			alert(data.confirm);
		}
		if (typeof(data.phone) != 'undefined')
		{
			alert(data.phone);
		}
	} else {
		if($('#ed_inp_phone').val() != '') {
			$('#user_phone').html($('#ed_inp_phone').val());
		}
		if($('#ed_inp_public').val() != '') {
			$('#user_public').html($('#ed_inp_public').val());
		}
		undo();
	}
}

// ДЛЯ ИЗБРАННОГО
function updateFavorite(category, id_prod) {
  FW.ajax.send({
    model: 'user',
    method: 'updateFavorite',
    callback: function (data) {
			$( document ).ready(function () {
				if (data.output == 'added') {
					$('.pr_description #like').empty();
					$('.pr_description #like').append('<i class="fa fa-heart fa-lg fa-fw" aria-hidden="true"></i> Удалить из избранного</span>');
					$('.pr_description #like').addClass('red');
	    	}else{
					$('.pr_description #like').empty();
					$('.pr_description #like').append('<i class="fa fa-heart fa-lg fa-fw" aria-hidden="true"></i> В избранное</span>');
					$('.pr_description #like').removeClass('red');
				}
				if (data.status == 'false') {
					alert(data.error);
				}
			});
    },
    data: {
      category: category,
			id_prod: id_prod
    }
  });
}
// ДЛЯ ТАБЛИЦЫ ПРИНЯТЫХ ЗАКАЗОВ
function getAccepted(){
  FW.ajax.send({
      model: 'order',
      method: 'getAccepted',
      callback: function(data){
          $("#tb_buy tbody").empty();
          for( let index = data.output.length - 1; index >= 0 ; --index){
              var str = '';
              for(let j in data.output[index].prods){
                  var id_prod = data.output[index].prods[j].id_prod;
                  var articule = data.output[index].prods[j].articule;
                  var price = data.output[index].price;
                  var category = data.output[index].prods[j].category;

                  str += '<a target="_blank" title="Нажмите, чтобы перейти на страницу товара" href="product?category=' + category + '&id=' + id_prod + '"><li>' + articule + '</li></a>';
              }
              $('#accept_order_table tbody').append(`
                  <tr id="${data.output[index].id_order}">
                      <td>`+data.output[index].id_order+`</td>
                      <td><ul class="list-unstyled">`+str+`</ul></td>
                      <td>кол-во</td>
                      <td>`+price+`</td>
                      <td>`+data.output[index].pay_way+`</td>
                      <td>`+data.output[index].delivery_way+`</td>
                      <td id="time">`+FW.timeConverter(data.output[index].date)+`</td>
                  </tr>
              `);
          }
      }
  });
}
$( document ).ready(function() {
    getAccepted();
    setInterval(getAccepted,20000);
});
