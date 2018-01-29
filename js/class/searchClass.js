Search = new Object();

Search.items_container = $(document);
Search.drawFunc = function() {};
Search.query = '';
Search.page = 0;
Search.category = '';
Search.sort = '';
Search.direction = '';
Search.discount = 0;
Search.settings = {
    val: {}
};

/**
 * Adds a value to a parameter in settings
 *
 * @param param - setting parameter
 * @param value - value of parameter
 */
Search.settings.add = function (param, value) {
    if(typeof(Search.settings.val[param]) != 'undefined') {
        Search.settings.val[param].push(value);
    } else {
        Search.settings.val[param] = [value];
    }
};

/**
 * Empty settings
 */
Search.settings.empty = function (param, value) {
    Search.settings.val = {};
};

/**
 * Deletes a value from a parameter in settings
 *
 * @param param - setting parameter
 * @param value - value of parameter
 */
Search.settings.delete = function (param, value) {

    let dummy = Search.settings.val[param];

    if(dummy.length == 1) {
        delete Search.settings.val[param];
    } else {
        Search.settings.val[param].splice(dummy.indexOf(value), 1);
    }
};

/**
 * Go to direct page
 *
 * @param page - page num
 */
Search.goPage = function(page) {
    Search.page = page;
    Search.update();
};

/**
 * Go to next page
 */
Search.nextPage = function() {
    Search.page += 1;
    Search.update();
};

/**
 * Toggle sort direction filter between DESC and ASC
 */
Search.chDirection = function() {
    if(Search.direction == 'ASC') Search.direction = 'DESC';
    else Search.direction = 'ASC';
    Search.update();
};

/**
 * Change sort filter and set direction to ASC
 *
 * @param sort - value of sort
 */
Search.chSort = function(sort) {
    Search.sort = sort;
    Search.direction = 'ASC'
    Search.update();
};

/**
 * Go to next page
 */
Search.prevPage = function() {
    Search.page -= 1;
    Search.update();
};

/**
 * Updates the search , draws it
 *
 * @param callback -
 */
Search.update = function(update, callback = function() {}) {
    FW.ajax.send({
        model: 'search',
        method: 'find',
        callback: function(data, params) {
            if(!data.status) return;

            Search.draw(data.output.search_result, params.update);

            params.callback({
                found: data.output.found,
                pages_left: data.output.pages_left
            });
        },
        local_params: {
            callback: callback,
            update: update
        },
        data: {
            page: Search.page,
            query: Search.query,
            category: Search.category,
            settings: JSON.stringify(Search.settings.val),
            sort: Search.sort,
            direction: Search.direction
        },
        decoder: {
            settings: 'JSON'
        }
    });
};

/**
 * Вырисовывает блоки , полученные поиском
 *
 * @param data - данные для отрисовки
 */
Search.draw = function(data, update = true) {
    if(update)
        Search.items_container.html('');
    for(i in data) {
        Search.items_container.append(Search.drawFunc(data[i]));
    }
};
