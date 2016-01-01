class Permission {
	private _id:Number;
	private _name:String;
	private _close:Boolean;

	constructor(id,name,close=true,childs=[]) {
	    this._id = id;
	    this._name = name;
	    this._close = close;
	    this._childs = childs;
	}

	get name():String {
        return this._name;
    }
    set name(name:String) {
        this._name = name;
    }

    get close():Boolean {
        return this._close;
    }
    set close(status:Boolean) {
        this._close = status;
    }

    get childs():Boolean {
        return this._childs;
    }
    set childs(obj:Object) {
        this._childs.push(obj);
    }
}