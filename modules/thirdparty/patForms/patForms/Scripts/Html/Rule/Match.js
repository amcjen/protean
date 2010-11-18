/* patForms::Rule::Match */

function pFRC_Match(field) {
	this.field = eval('pfe_' + field);
}

pFRC_Match.prototype.validate = function() {
	value = this.field.getValue();
	if (!value.match(this.pattern)) {
		alert('This is an invalid value.');
	}
}

pFRC_Match.prototype.setValue = function(pattern) {
	this.pattern = pattern;
}

/* END: patForms::Rule::Match */