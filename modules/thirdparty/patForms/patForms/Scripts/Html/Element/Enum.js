/* patForms_Element_Enum */
function pFEC_Enum( id )
{
	this.__construct = function(id)
	{
		this.id = id;
	}

	this.getValue = function()
	{
		var element = document.getElementById( this.id );
		return element[element.selectedIndex].value;
	}
	
	this.getLabel = function()
	{
		var element = document.getElementById( this.id );
		return element[element.selectedIndex].text;
	}
	
	this.getOptions = function()
	{
		var element = document.getElementById( this.id );
		return element.options;
	}
	this.clearOptions = function()
	{
		var element = document.getElementById( this.id );
		element.options.length = 0;
	}
	
	this.addOption = function(option)
	{
		var element = document.getElementById( this.id );
		element.options[element.options.length] = option;
	}
	
	this.__construct(id);
}
/* END: patForms_Element_Enum */