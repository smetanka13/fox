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
 * @param value - value of parameter
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
 * @param value - value of parameter
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
    this.update();
};

/**
 * Go to next page
 */
Search.nextPage = function() {
    this.page += 1;
    this.update();
};

/**
 * Toggle sort direction filter between DESC and ASC
 */
Search.chDirection = function() {
    if(this.direction == 'ASC') this.direction = 'DESC';
    else this.direction = 'ASC';
    this.update();
};

/**
 * Change sort filter and set direction to ASC
 *
 * @param sort - value of sort
 */
Search.chSort = function(sort) {
    this.sort = sort;
    this.direction = 'ASC'
    this.update();
};

/**
 * Go to next page
 */
Search.prevPage = function() {
    this.page -= 1;
    this.update();
};

/**
 * Updates the search , draws it
 *
 * @param callback -
 */
Search.update = function(callback = function() {}) {
    ajaxController({
        model: 'search',
        method: 'find',
        callback: function(data, callback) {
            if(!data.status) return;

            Search.draw(data.output.search_result);

            callback({
                found: data.output.found,
                pages_left: data.output.pages_left
            });
        },
        local_params: callback,
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
