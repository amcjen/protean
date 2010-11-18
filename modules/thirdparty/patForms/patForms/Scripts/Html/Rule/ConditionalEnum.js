/* patForms_Rule_Conditional_Enum */
function pFRC_ConditionalEnum( source, target )
{
	this.source = eval( 'pfe_' + source );
	this.target = eval( 'pfe_' + target );
	this.conditions = new Array();

	this.totalOptions	=	false;
}

pFRC_ConditionalEnum.prototype.adjustTarget	= function()
{
	if( this.totalOptions == false )
	{
		this.totalOptions	= new Array();
		var opts   = this.target.getOptions();
		for( var i = 0; i < opts.length; i++ )
		{
			this.totalOptions.push( opts[i] );
		}
	}

	var value = this.source.getValue();
	for( var i = 0; i < this.conditions.length; i++ )
	{
		if( this.conditions[i].value != value )
		{
			continue;
		}

		this.target.clearOptions();
		for( var j = 0; j < this.conditions[i].options.length; j++ )
		{
			var option = this.getOption( this.conditions[i].options[j] );
			if( option != false )
			{
				this.target.addOption( option );
			}
		}
		break;
	}
}

pFRC_ConditionalEnum.prototype.getOption = function( value )
{
	for( var i = 0; i < this.totalOptions.length; i++ )
	{
		if( this.totalOptions[i].value == value )
			return this.totalOptions[i];
	}
	return false;
}


pFRC_ConditionalEnum.prototype.addCondition = function( value, options )
{
	this.conditions.push( new pFRC_ConditionalEnum_Condition( value, options ) );
}

function pFRC_ConditionalEnum_Condition( value, options )
{
	this.value   = value;
	this.options = options;
}
/* END: patForms_Rule_ConditionalEnum */