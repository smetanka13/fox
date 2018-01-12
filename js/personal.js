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
