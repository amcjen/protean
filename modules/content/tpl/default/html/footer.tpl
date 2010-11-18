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
{if $smarty.const.PF_ANALYTICS_TRACKING}
{literal}
<script type="text/javascript">
try{
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
{/literal}
	var pageTracker = _gat._getTracker("{$smarty.const.PF_ANALYTICS_ID}");
	pageTracker._trackPageview();
{literal}
} catch(err) {}
</script>
{/literal}
{/if}
</body>
</html>