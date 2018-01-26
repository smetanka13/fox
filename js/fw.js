var FW = new Object();

FW.ajax = new Object();

FW.getRandomInt = function(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};
FW.timeConverter = function(UNIX_timestamp){
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Янв','Фев','Март','Апр','Май','Июнь','Июль','Авг','Сент','Окт','Нояб','Дек'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
    return time;
}

// TAG MACHNISM
FW.getTags = function(text) {
    return text.match(/#\w+/gm);
};
FW.processTags = function(block, drawFunc) {
    var elem = document.querySelector(block);
    var description = elem.innerHTML;
    var tags = getTags(description);
    for(let index in tags) {
        description = description.replace(
            tags[index],
            drawFunc(tags[index])
        );
        elem.innerHTML = description;
    }
};

FW.showMessage = function(str, timer = 0) {
    var parent = document.querySelector('.fw-addons .message');
    var classes = parent.classList;
    if(classes.contains('opened')) {
        classes.remove('opened');
    } else {
        document.querySelector('.fw-addons .message .content').innerHTML = str;
        classes.add('opened');
        if(timer > 0) {
            setTimeout(function(block) {
                block.remove('opened');
            }, timer, classes);
        }
    }
};

FW.ajax.error = function(str) {

    var block = $('#ajax_error');

    block.html(str);
    block.fadeIn(100, function() {
        setTimeout(function () {
            block.fadeOut(100);
        }, 2000);
    });

};

FW.ajax.animate = function(mode) {
    var block = $("#ajax_load");
    if(mode) {
        block.show();
        block.css("top", "calc(100% - 128px)");
    } else {
        block.hide();
        block.css("top", "100%");
    }
};

FW.ajax.send = function(params, files = null) {

    if(typeof params.model == 'undefined' || typeof params.method == 'undefined')
        return;

    if(typeof params.callback == 'undefined')
        params.callback = function() {};

    this.animate(true);
    var data = new FormData();

    // Добавляет в data все ключи и их значения
    data.append('model', params.model);
    data.append('method', params.method);

    if(typeof params.decoder != 'undefined')
        data.append('decoder', JSON.stringify(params.decoder));

    for(let key in params.data) {
        data.append(key, params.data[key]);
    }

    // Если присутствуют файлы, добавить их в data
    if(files != null) {
        for(let key in files) {
            $.each(files[key][0].files, function(i, file) {
                data.append(key+i, file);
            });
        }
    }

    // Отправка data в PHP контроллер аjax запросов
    $.ajax({
        url: 'remote',
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        error: function() {
            FW.ajax.animate(false);
            FW.ajax.error('Server error.');
        },
        success: function(json) {
            console.log(json);
            FW.ajax.animate(false);
            let data = JSON.parse(json);

            if(typeof(params.local_params) != 'undefined') {
                var local_params = params.local_params;
            } else {
                var local_params = null;
            }

            if(typeof(data.permission) != 'undefined') {
                FW.ajax.error(data.permission);
            } else {
                params.callback(data, local_params);
            }
        }
    });
};
FW.ajax.betasend = function(params, files = null) {
    this.animate(true);
    var data = new FormData();

    // Добавляет в data все ключи и их значения
    for(let key in params) {
        if(key == 'callback' || key == 'local_params') continue;
        data.append(key, params[key]);
    }

    // Если присутствуют файлы, добавить их в data
    if(files != null) {
        for(let key in files) {
            for(let i in files[key][0].files) {
                data.append(key+i, files[key][0].files[i]);
            }
        }
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'remote', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percentComplete = (e.loaded / e.total) * 100;
            console.log(percentComplete + '% uploaded');
        }
    };
    xhr.onload = function() {
        if (this.status == 200) {
            console.log(this.responseText);
            FW.ajax.animate(false);
            let data = JSON.parse(this.responseText);
            params.callback(data);
        }
    };
    xhr.send(data);
};
FW.randomKey = function(len = 8) {
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    for(var i = 0; i < len; i++)
      text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
};
FW.b64EncodeUnicode = function(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
};
FW.b64DecodeUnicode = function(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
};
