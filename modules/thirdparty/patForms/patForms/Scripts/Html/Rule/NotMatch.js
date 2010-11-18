/* patForms::Rule::NotMatch */

function pFRC_NotMatch(field) {
	this.field = eval('pfe_' + field);
}

pFRC_NotMatch.prototype.validate = function() {
	value = this.field.getValue();
	if (value.match(this.pattern)) {
		alert('This is an invalid value.');
	}
}

pFRC_NotMatch.prototype.setValue = function(pattern) {
	this.pattern = pattern;
}

/* END: patForms::Rule::NotMatch */