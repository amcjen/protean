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