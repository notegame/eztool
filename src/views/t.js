var Permission = (function () {
    function Permission(id, name, close, childs) {
        if (close === void 0) { close = true; }
        if (childs === void 0) { childs = []; }
        this._id = id;
        this._name = name;
        this._close = close;
        this._childs = childs;
    }
    Object.defineProperty(Permission.prototype, "name", {
        get: function () {
            return this._name;
        },
        set: function (name) {
            this._name = name;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Permission.prototype, "close", {
        get: function () {
            return this._close;
        },
        set: function (status) {
            this._close = status;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(Permission.prototype, "childs", {
        get: function () {
            return this._childs;
        },
        set: function (obj) {
            this._childs.push(obj);
        },
        enumerable: true,
        configurable: true
    });
    return Permission;
})();
