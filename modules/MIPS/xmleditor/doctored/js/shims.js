(function(){
	"use strict";

	if(!String.prototype.trim) { // thanks to http://stackoverflow.com/a/8522376
		String.prototype.trim = function(){
			return this.replace(/^\s+|\s+$/g,'');
		};
	}

}());

