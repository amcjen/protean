/* patForms::Rule::MaxValue */

function pFRC_MaxValue(field) {
	this.field = eval('pfe_' + field);
}

pFRC_MaxValue.prototype.validate = function() {
	value = this.field.getValue();
	if (parseInt(value) != value) {
		alert('Please enter a number that is less or equal to ' + this.value);
	}
	if (parseInt(value) > this.value) {
		alert('Please enter a number that is less or equal to ' + this.value);
	}
}

pFRC_MaxValue.prototype.setMaxValue = function(value) {
	this.value	= value;
}

/* END: patForms::Rule::MaxValue */