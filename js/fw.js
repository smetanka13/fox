$.fn.extend ({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
    }
});

var FW = new Object();

FW.ajax = new Object();

FW.getRandomInt = function(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};
FW.timeConverter = function(UNIX_timestamp){
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
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
    var description = block.html();
    var tags = getTags(description);
    for(index in tags) {
        description = description.replace(
            tags[index],
            drawFunc(tags[index])
        );
        block.html(description);
    }
};

FW.showMessage = function(str) {
    var main = $("#message_whole");
    var content = $("#message_block");
    var container = $("#message_cnt");

    if(main.css("display") == "none") {
        content.html(str);
        container.show();
        main.fadeIn(100, function() {
            container.css("top", "100px");
        });
    } else {
        container.css("top", "-"+container.height()+"px");
        main.fadeOut(200, function() {
            container.hide();
        });
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

    this.animate(true);
    var data = new FormData();

    // Добавляет в data все ключи и их значения
    for(var key in params) {
        if(key == 'callback' || key == 'local_params') continue;
        data.append(key, params[key]);
    }

    // Если присутствуют файлы, добавить их в data
    if(files != null) {
        for(var key in files) {
            $.each(files[key][0].files, function(i, file) {
                data.append(key+i, file);
            });
        }
    }

    // Отправка data в PHP контроллер аjax запросов
    $.ajax({
        url: "remote",
        type: "POST",
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
            var data = JSON.parse(json);

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
