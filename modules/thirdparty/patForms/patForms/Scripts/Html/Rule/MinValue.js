/* patForms::Rule::MinValue */

function pFRC_MinValue(field) {
	this.field = eval('pfe_' + field);
}

pFRC_MinValue.prototype.validate = function() {
	value = this.field.getValue();
	if (parseInt(value) != value) {
		alert('Please enter a number that is greater or equal to ' + this.value);
	}
	if (parseInt(value) < this.value) {
		alert('Please enter a number that is greater or equal to ' + this.value);
	}
}

pFRC_MinValue.prototype.setMinValue = function(value) {
	this.value	= value;
}

/* END: patForms::Rule::MinValue */