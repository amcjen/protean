/* patForms::Element::String */
function pFEC_String( id )
{
	this.__construct = function(id)
	{
		this.id = id;
	}

	this.getValue	=	function()
	{
		var element = document.getElementById( this.id );
		return element.value;
	}
	
	this.__construct(id);
}
/* END: patForms::Element::String */