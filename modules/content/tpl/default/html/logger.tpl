			<div id="pfhtmlloggerpanel" style="display: hidden;">
				<div class="hd">
					{$PF_HTML_LOGGER_HEADER}
				</div>
				<div class="bd">
					{$PF_HTML_LOGGER_BODY}
				</div>
				<div class="ft">
					{$PF_HTML_LOGGER_FOOTER}
					{if PF_PROFILER}
						<br />##EXECUTION_TIME##: {$PF_PROFILE_TIME} ##SECONDS##
					{/if}
				</div>
			</div>
			{literal}
			<script type="text/javascript">
					YAHOO.namespace("protean.htmllogger");

					function init() {
						// Instantiate a Panel from markup
						YAHOO.protean.htmllogger.pfhtmlloggerpanel = new YAHOO.widget.Panel("pfhtmlloggerpanel", { width:"360px", xy:[600,600],
						
						visible:
			{/literal}
					{if isset($smarty.get.log) || $smarty.const.PF_DEBUG_ALWAYS_DISPLAY_LOGGER == true}true{else}false{/if}, 
			{literal}		
					constraintoviewport:true } );
						YAHOO.protean.htmllogger.pfhtmlloggerpanel.render();
					}

					YAHOO.util.Event.addListener(window, "load", init);
			</script>
			{/literal}