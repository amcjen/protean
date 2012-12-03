{include file=$PF_HEADER}

			<p>
				<form method="post" name="loginform" id="loginform" action="{$LOGIN_LINK}" accept-charset="utf-8" enctype="multipart/form-data">
					<fieldset>
							<label for="username" style="width:58px;">##USERNAME##:</label>
							<input name="username" id="username" type="text" size="12" maxlength="255" /><br />
							<label for="password" style="width:58px;">##PASSWORD##:</label>
							<input name="password" id="password" type="password" size="12" maxlength="255" /><br />
							<div id="center" style="width:50px;">
								<input name="login" id="login" type="submit" class="buttons" value="Login" /><br />
							</div>
					</fieldset>
				</form>
			</p>
		
{include file=$PF_FOOTER}