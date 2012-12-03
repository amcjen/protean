<section class="account-section">
	<dl class="form-layout" id="registration-account-name-view">
		<div class="response"></div>
		<dt>
			<label>Member Name:</label><span class="account-section-loading" id="registration-account-name-loading"><img src="{$MQC_CDN_URL}/loading.gif" /></span>
		</dt>
		<dd>
			<span id="registration-account-name">{$user->getParty()->getFullName()|default:'None'}</span>
			<a href="#" id="registration-account-name-edit"><span class="account-button">Edit Name</span></a>
		</dd>
	</dl>
	<dl class="form-layout-update hdn" id="registration-account-name-update">
		<form id="registration-account-name-form" method="post">
			<dt>
				<label>First Name:</label>
			</dt>
			<dd>
				<input type="text" name="firstname" value="{$user->getParty()->getFirstName()}" id="firstname" tabindex="10" />
				<span class="account-button">
					<a href="#" data-id="{$user->getAuthUserId()}" id="registration-account-name-save" tabindex="20">Save</a>
					|
					<a href="#" id="registration-account-name-cancel" tabindex="25">Cancel</a>
				</span>
			</dd>
			<dt>
				<label>Last Name:</label>
			</dt>
			<dd>
				<input type="text" name="lastname" value="{$user->getParty()->getLastName()}" id="lastname" tabindex="15" />
			</dd>
		</form>
	</dl>


	<dl class="form-layout" id="registration-account-email-view">
		<div class="response"></div>
		<dt>
			<label>E-mail Address:</label><span class="account-section-loading" id="registration-account-email-loading"><img src="{$MQC_CDN_URL}/loading.gif" /></span>
		</dt>
		<dd>
			<span id="registration-account-email">{$user->getParty()->getEmail()}</span>
			<a href="#" id="registration-account-email-edit"><span class="account-button">Change E-mail/Password</span></a>
		</dd>
	</dl>
	<dl class="form-layout-update hdn" id="registration-account-email-update">
		<form id="registration-account-email-form" method="post">
			<dt>
				<label>E-mail Address:</label>
			</dt>
			<dd>
				<input type="text" name="email" value="{$user->getParty()->getEmail()}" id="email" tabindex="10" />
				<span class="account-button">
					<a href="#" data-id="{$user->getAuthUserId()}" id="registration-account-email-save" tabindex="25">Save</a>
				  |
					<a href="#" id="registration-account-email-cancel" tabindex="30">Cancel</a>
				</span>
			</dd>
			<dt>
				<label>Password:</label>
			</dt>
			<dd>
				<input type="password" name="password" value="" id="password" tabindex="15" />
			</dd>
			<dt>
				<label>Password Again:</label>
			</dt>
			<dd>
				<input type="password" name="password2" value="" id="password2" tabindex="20" />
			</dd>
		</form>
	</dl>

	<dl class="form-layout" id="registration-account-telephone-view">
		<div class="response"></div>
		<dt>
			<label>Telephone:</label><span class="account-section-loading" id="registration-account-telephone-loading"><img src="{$MQC_CDN_URL}/loading.gif" /></span>
		</dt>
		<dd>
			<span id="registration-account-telephone">{$user->getParty()->getTelephone()|default:"None"}</span>
			<a href="#" id="registration-account-telephone-edit"><span class="account-button">Edit Cell Telephone</span></a>
		</dd>
	</dl>
	<dl class="form-layout-update hdn" id="registration-account-telephone-update">
		<form id="registration-account-telephone-form" method="post">
			<dt>
				<label>Telephone:</label>
			</dt>
			<dd>
				<input type="text" name="telephone" value="{$user->getParty()->getTelephone()}" id="telephone" tabindex="10" />
				<span class="account-button">
					<a href="#" data-id="{$user->getAuthUserId()}" id="registration-account-telephone-save" tabindex="15">Save</a>
					|
					<a href="#" id="registration-account-telephone-cancel" tabindex="20">Cancel</a>
				</span>
			</dd>
		</form>
	</dl>

	<dl class="form-layout" id="registration-account-paymentinfo-view">
		<div class="response"></div>
		<dt>
			<label>Payment Information:</label><span class="account-section-loading" id="registration-account-paymentinfo-loading"><img src="{$MQC_CDN_URL}/loading.gif" /></span>
		</dt>
		<dd>
			{assign var="paymentMethod" value=$user->getParty()->getShopOrderPaymentMethods()}
			{if $paymentMethod[0]}
			<span id="registration-account-paymentinfo">{$paymentMethod[0]->getType()} {$paymentMethod[0]->getNumber()}</span>
			{else}
			<span id="registration-account-paymentinfo">None</span>
			{/if}
			<a href="#" id="registration-account-paymentinfo-edit"><span class="account-button">Edit Payment Information</span></a>
		</dd>
	</dl>
	<dl class="form-layout-update hdn" id="registration-account-paymentinfo-update">
		<form id="registration-account-paymentinfo-form" method="post">
			<dt>
				<label>Credit Card Type:</label>
			</dt>
			<dd>
				<select name="paymentinfo-type" id="paymentinfo-type" size="1" tabindex="10">
					<option value="Visa">Visa</option>
					<option value="Mastercard">Mastercard</option>
					<option value="Amex">Amex</option>
					<option value="Discover">Discover</option>
				</select>
        <span class="account-button">
					<a href="#" data-id="{$user->getAuthUserId()}" id="registration-account-paymentinfo-save" tabindex="45">Save</a>
					|
					<a href="#" id="registration-account-paymentinfo-cancel" tabindex="50">Cancel</a>
				</span>
			</dd>
			<dt>
				<label>Credit Card Number:</label>
			</dt>
			<dd>
				<input type="text" name="paymentinfo-number" value="" id="paymentinfo-number" size="20" maxlength="24" tabindex="15" />
			</dd>
			<dt>
				<label>Expiration Date:</label>
			</dt>
			<dd>
				<select name="paymentinfo-expirationmonth" id="paymentinfo-expirationmonth" size="1" tabindex="20">
					<option value="1"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 1} selected{/if}>1</option>
					<option value="2"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 2} selected{/if}>2</option>
					<option value="3"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 3} selected{/if}>3</option>
					<option value="4"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 4} selected{/if}>4</option>
					<option value="5"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 5} selected{/if}>5</option>
					<option value="6"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 6} selected{/if}>6</option>
					<option value="7"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 7} selected{/if}>7</option>
					<option value="8"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 8} selected{/if}>8</option>
					<option value="9"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 9} selected{/if}>9</option>
					<option value="10"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 10} selected{/if}>10</option>
					<option value="11"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 11} selected{/if}>11</option>
					<option value="12"{if $paymentMethod[0] && $paymentMethod[0]->getExpirationDate('n') == 12} selected{/if}>12</option>
				</select> /
				<select name="paymentinfo-expirationyear" id="paymentinfo-expirationyear" size="1" tabindex="25">
					{for $var=date('Y') to date('Y')+8}
					<option value="{$var}">{$var}</option>
					{/for}
				</select>
			</dd>
			<dt>
				<label>CCV Number:</label>
			</dt>
			<dd>
				<input type="text" name="paymentinfo-ccv" value="" id="paymentinfo-ccv" size="4" maxlength="4" tabindex="30" />
			</dd>
			<dt>
				<label>Name on Card:</label>
			</dt>
			<dd>
				<input type="text" name="billingaddress-name" value="{if $billingAddress}{$billingAddress->getName()}{/if}" id="billingaddress-name" maxlength="255" tabindex="35" />
			</dd>
			<dt>
				<label>Billing Postal Code:</label>
			</dt>
			<dd>
				<input type="text" name="billingaddress-postalcode" value="{if $billingAddress}{$billingAddress->getPostalCode()}{/if}" id="billingaddress-postalcode" maxlength="255" tabindex="40" />
			</dd>
		</form>
	</dl>

	<dl class="form-layout" id="registration-account-shippingaddress-view">
		<div class="response"></div>
		<dt>
			<label>Shipping Address:</label><span class="account-section-loading" id="registration-account-shippingaddress-loading"><img src="{$MQC_CDN_URL}/loading.gif" /></span>
			{assign var="shippingAddress" value=$user->getParty()->getShippingAddress()}
		</dt>
		<dd>
			<span id="registration-account-shippingaddress">
			{if $shippingAddress !== null && $shippingAddress->isShippingComplete()}
				{$shippingAddress->getName()}<br />
				{$shippingAddress->getAddress1()}<br />
				{if $shippingAddress->getAddress2()}{$shippingAddress->getAddress2()}<br />{/if}
				{if $shippingAddress->getAddress3()}{$shippingAddress->getAddress3()}<br />{/if}
				{$shippingAddress->getCity()}, {$shippingAddress->getRegion()} {$shippingAddress->getPostalCode()}<br />
				{$shippingAddress->getLocaleCountry()}
			{else}
				No shipping address
			{/if}
			</span>
			<a href="#" id="registration-account-shippingaddress-edit"><span class="account-button">Edit Shipping Address</span></a>
		</dd>
	</dl>
	<dl class="form-layout-update hdn" id="registration-account-shippingaddress-update">
		<form id="registration-account-shippingaddress-form" method="post">
			<dt>
				<label>Shipping Address - Ship To:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-name" value="{if $shippingAddress}{$shippingAddress->getName()}{/if}" id="shippingaddress-name" maxlength="255" tabindex="10" />
				<span class="account-button">
					<a href="#" data-id="{$user->getAuthUserId()}" id="registration-account-shippingaddress-save" tabindex="50" >Save</a>
					|
					<a href="#" id="registration-account-shippingaddress-cancel" tabindex="55" >Cancel</a>
				</span>
			</dd>
			<dt>
				<label>Address 1:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-address1" value="{if $shippingAddress}{$shippingAddress->getAddress1()}{/if}" id="shippingaddress-address1" maxlength="255" tabindex="15" />
			</dd>
			<dt>
				<label>Address 2:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-address2" value="{if $shippingAddress}{$shippingAddress->getaddress2()}{/if}" id="shippingaddress-address2" maxlength="255" tabindex="20" />
			</dd>
			<dt>
				<label>Address 3:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-address3" value="{if $shippingAddress}{$shippingAddress->getAddress3()}{/if}" id="shippingaddress-address3" maxlength="255" tabindex="25" />
			</dd>
			<dt>
				<label>City:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-city" value="{if $shippingAddress}{$shippingAddress->getCity()}{/if}" id="shippingaddress-city" maxlength="255" tabindex="30" />
			</dd>
			<dt>
				<label>State/Region:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-region" value="{if $shippingAddress}{$shippingAddress->getRegion()}{/if}" id="shippingaddress-region" maxlength="255" tabindex="35" />
			</dd>
			<dt>
				<label>Postal Code:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-postalcode" value="{if $shippingAddress}{$shippingAddress->getPostalCode()}{/if}" id="shippingaddress-postalcode" maxlength="255" tabindex="40" />
			</dd>
			<dt>
				<label>Country:</label>
			</dt>
			<dd>
				<input type="text" name="shippingaddress-country" value="{if $shippingAddress}{$shippingAddress->getLocaleCountry()}{/if}" id="shippingaddress-country" maxlength="2" tabindex="40" />
			</dd>
		</form>
	</dl>
</section>