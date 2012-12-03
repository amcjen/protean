<div id="ft" class="grid_16 omega">
	<div class="grid_16 right">
		<p class="margin-bottom-0">
			Copyright &copy; {$smarty.now|date_format:"%Y"} Loopshot Inc. All rights reserved.
			{if PF_PROFILER}
			<br />Protean v.{$smarty.const.PF_VERSION} 
			<br />##EXECUTION_TIME##: {$PF_PROFILE_TIME} ##SECONDS##<br />
			{/if}
		</p>
	</div>
</div>
</div>
{$PF_JAVASCRIPT_INCLUDES}
{$PF_FOOTER_JAVASCRIPT}

{if $smarty.const.PF_INCLUDE_GA_TRACKING}{include file='modules/content/tpl/default/html/ga-js.tpl'}{/if}
{if $smarty.const.PF_INCLUDE_SNAPENGAGE}{include file='modules/content/tpl/default/html/snapengage-js.tpl'}{/if}
{if $smarty.const.PF_INCLUDE_GET_SATISFACTION}{include file='modules/content/tpl/default/html/getsatisfaction-js.tpl'}{/if}

</body>
</html>