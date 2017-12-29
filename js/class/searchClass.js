Search = Object;

Search.items_container = $(document);
Search.drawFunc = function() {};
Search.query = '';
Search.page = 0;
Search.category = '';
Search.sort = '';
Search.direction = '';
Search.settings = {
    val: {}
};

/**
 * Adds a value to a parameter in settings
 *
 * @param param - setting parameter
 * @param value - value of parameter to delete
 */
Search.settings.add = function (param, value) {
    if(typeof(this.val[param]) != 'undefined') {
        this.val[param] += '/' + value;
    } else {
        this.val[param] = value;
    }
};

/**
 * Deletes a value from a parameter in settings
 *
 * @param param - setting parameter
 * @param value - value of parameter to delete
 */
Search.settings.delete = function (param, value) {
    var tmp = this.val[param].split("/");
    if(Object.keys(tmp).length == 1) {
        delete this.val[param];
    } else {
        tmp.splice(tmp.indexOf(value), 1);
        this.val[param] = tmp.join('/');
    }
};


/**
 * Go to direct page
 *
 * @param page - page num
 */
Search.goPage = function(page) {
    this.page = page;
    this.updateFromCache();
};

/**
 * Updates the search , draws it
 *
 * @param history_push - обновлять ли адресную строку?
 */
Search.update = function(history_push = true) {
    ajaxController({
        model: 'search',
        method: 'find',
        callback: function(data, history_push) {
            if(!data.status) return;

            if(history_push) {
                history.pushState({
                        settings: Search.settings.val,
                        sort: Search.sort,
                        direction: Search.direction,
                        page: Search.page
                    },
                    null,
                    'search?query='+Search.query+'&page='+Search.page+'&sort='+Search.sort+'&direction='+Search.direction+'&category='+Search.category+'&settings='+FW.b64EncodeUnicode(JSON.stringify(Search.settings.val))
                );
            }
            Search.draw(data.output.search_result);
        },
        local_params: history_push,
        page: this.page,
        query: this.query,
        category: this.category,
        settings: JSON.stringify(this.settings.val),
        sort: this.sort,
        direction: this.direction
    });
};

/**
 * Вырисовывает блоки , полученные поиском
 *
 * @param data - данные для отрисовки
 */
Search.draw = function(data) {
    this.items_container.html('');
    for(i in data) {
        this.items_container.append(this.drawFunc(data[i]));
    }
};

window.onpopstate = function(event) {
    if(event && event.state) {
        Search.page = event.state.page;
        Search.sort = event.state.sort;
        Search.direction = event.state.direction;
        Search.settings.val = JSON.parse(FW.b64DecodeUnicode(event.state.settings));
        Search.update(false);
    }
};
