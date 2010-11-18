/* patForms::Rule::ValidValues */

Array.prototype.inArray = function(value) {
	var i;
	for (i=0; i < this.length; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;
};

function pFRC_ValidValue(field) {
	this.field = eval('pfe_' + field);
}

pFRC_ValidValue.prototype.validate = function() {
	value = this.field.getValue();
	for (var i = 0; i < this.values.length; i++) {
		if (this.values[i] === value) {
			return true;
		}
	}
	var msg = 'Please enter one of the following values: ';
	for (var i = 0; i < this.values.length; i++) {
		msg = msg + this.values[i];
		if (i < this.values.length - 1) {
			msg = msg + ', ';
		}
	}
	alert(msg);
}

pFRC_ValidValue.prototype.setValues = function(values) {
	this.values	= values;
}

/* END: patForms::Rule::ValidValue */