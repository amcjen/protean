/* patForms::Rule::MinLength */

function pFRC_MinLength(field) {
	this.field = eval('pfe_' + field);
}

pFRC_MinLength.prototype.validate = function() {
	value = this.field.getValue();
	if (value.length < this.value) {
		alert('Please enter a value that is at least ' + this.value + ' characters long.');
	}
}

pFRC_MinLength.prototype.setValue = function(value) {
	this.value	= value;
}

/* END: patForms::Rule::MinLength */