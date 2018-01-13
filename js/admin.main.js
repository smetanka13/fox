function invalidParse(id) {
    var params = [];
    var error = false;
    $.each($(id), function(i, val) {
        params.push(val.value);
        if(val.value == '') error = true;
    });
    if(error) return false;
    return JSON.stringify(params);
}
function addInput(cnt, placeholder) {
    $(cnt).append('<input type="text" placeholder="'+placeholder+' '+($(cnt+' input').length + 1)+'">');
}
function removeInput(cnt) {
    $(cnt+' input:eq('+($(cnt+' input').length - 1)+')').remove();
}



function addCategory() {
    var json = invalidParse('#newcategory #params_cnt input');

    if(!json) {
        FW.showMessage("Пустой парметр недопустим");
        return;
    }

    FW.ajax.send({
        model: 'category',
        method: 'newCategory',
        callback: callback,
        data: {
            name: $('#newcategory #name').val(),
            params: json
        },
        decoder: {
            params: 'JSON'
        }
    });
}
function updateParams() {

    var category = $('#newparams #category').val();
    var param = $('#newparams #param').val();

    if(category == '') {
        FW.showMessage("Выберите категорию");
        return;
    }

    if(param == '') {
        FW.showMessage("Пустое значение недопустимо");
        return;
    }

    FW.ajax.send({
        model: 'category',
        method: 'addParams',
        callback: callback,
        data: {
            category: category,
            param: param
        }
    });
}
function updateValues() {
    var json = invalidParse('#values_cnt input');

    if($('#newparamvalues #category').val() == '') {
        FW.showMessage("Выберите категорию");
        return;
    }
    if($('#newparamvalues #param').val() == '') {
        FW.showMessage("Выберите спецификацию");
        return;
    }

    if(!json) {
        FW.showMessage("Пустое значение недопустимо");
        return;
    }

    FW.ajax.send({
        model: 'category',
        method: 'addValues',
        callback: callback,
        data: {
            category: $('#newparamvalues #category').val(),
            param: $('#newparamvalues #param').val(),
            values: json
        },
        decoder: {
            values: 'JSON'
        }
    });
}
function updateProd() {
    var params = {};
    var cnt = $('#upload #values select');

    $.each(cnt, function(i, elem) {
        params[elem.attr('data-param')] = elem.val();
    });

    params = JSON.stringify(params);

    FW.ajax.send({
        model: 'product',
        method: 'upload',
        callback: callback,
        data: {
            category: $('#upload #category').val(),
            id: $('#upload #found_id').val(),
            text: $('#upload #text').val(),
            price: $('#upload #price').val(),
            quantity: $('#upload #quantity').val(),
            params: params,
        },
        decoder: {
            params: 'JSON'
        }

    }, {
        img: $('#upload #img')
    })
}

$(document).ready(function() {

    $('#newparamvalues #category').change(function() {
        $('#newparamvalues #values_cnt').html('');
        FW.ajax.send({
            model: 'category',
            method: 'getParams',
            callback: function(data) {
                $('#newparamvalues #param').html('<option>Не выбрано</option>');
                if(data.status) {
                    for(let i in data.output) {
                        $('#newparamvalues #param').append('<option>'+data.output[i]+'</option>');
                    }
                }
            },
            data: {
                category: $('#newparamvalues #category').val()
            }
        })
    });
    $('#newparamvalues #param').change(function() {
        $('#newparamvalues #values_cnt').html('');
        FW.ajax.send({
            model: 'category',
            method: 'getValues',
            callback: function(data) {
                if(data.status) {
                    for(var i = 0; data.output[i] != null; i++) {
                        addInput('#newparamvalues #values_cnt', 'Значение');
                        $('#newparamvalues #values_cnt input:eq('+i+')').val(data.output[i]);
                    }
                } else {
                    addInput('#newparamvalues #values_cnt', 'Значение');
                }
            },
            data: {
                category: $('#newparamvalues #category').val(),
                param: $('#newparamvalues #param').val()
            }
        })
    });
    $('#upload #category').change(function() {
        $('#upload #values').html('');
        FW.ajax.send({
            model: 'category',
            method: 'getFullCategory',
            callback: function(data) {
                if(data.status) {
                    for(var key in data.output) {
                        $('#upload #values').append('<h4>'+key+'</h4>');
                        var str = '';
                        for(var i = 0; data.output[key][i] != null; i++) {
                            str += '<option>'+data.output[key][i]+'</option>';
                        }
                        $('#upload #values').append('<select data-param="'+key+'">'+str+'</select>');
                    }
                }
            },
            data: {
                category: $('#upload #category').val()
            }
        })
    });
    $('#upload #id').keyup(function() {
        if($('#upload #category').val() != 'Не выбрано' && $('#upload #id').val() != '') {
            FW.ajax.send({
                model: 'product',
                method: 'getApprox',
                callback: function(data) {
                    if(data.output != null) {
                        $('#upload .alert-danger').hide();
                        $('#upload .alert-success').show();
                        $('#upload .alert-success a').attr('href', 'product?category='+data.output.category+'&id='+data.output.id_prod);
                        $('#upload .alert-success a').html(data.output.title);
                        $('#upload #quantity').val(data.output.quantity);
                        $('#upload #text').val(data.output.text);
                        $('#upload #price').val(data.output.price);

                        var cnt = $('#upload #values select');

                        for(var i = 0; i < cnt.length; i++) {
                            $(cnt[i]).val(data.output[$(cnt[i]).attr('data-param')]);
                        }
                        $('#upload #found_id').val(data.output.id_prod);
                    } else {
                        $('#upload .alert-danger').show();
                        $('#upload .alert-success').hide();
                    }
                },
                data: {
                    category: $('#upload #category').val(),
                    id: $('#upload #id').val()
                }
            })
        }
    });
});
