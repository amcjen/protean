/* patForms::Rule::MaxLength */

function pFRC_MaxLength(field) {
	this.field = eval('pfe_' + field);
}

pFRC_MaxLength.prototype.validate = function() {
	value = this.field.getValue();
	if (value.length > this.value) {
		alert('Please enter a value that is max. ' + this.value + ' characters long.');
	}
}

pFRC_MaxLength.prototype.setValue = function(value) {
	this.value	= value;
}

/* END: patForms::Rule::MaxLength */