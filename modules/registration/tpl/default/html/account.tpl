{include file=$PF_HEADER}

<div class="login-content">
  <h1>Your Account</h1>
  <div class="new-user left">
    <h2>Account Information</h2>
    {$FORM_ELEMENTS}
    <hr>
    <div>
      <h3>Your Newsletters</h3>
        <!-- TODO: add subscription newsletter links here -->
      <hr>
    </div>
    <div>
      <h3>You currently have {$PARTY_CURRENT_POINTS} Quilter's Cash Points</h3>
      <hr>
    </div>
  </div>
  <div class="divider left"></div>
  <div class="left">
    <h2>Order History</h2>
    <table class="review" border="0" cellpadding="0" cellspacing="0" width="330">
			<colgroup>
			<col span="1" width="80">
			<col span="1" width="170">
			<col span="1" width="70">
			</colgroup>
			<thead>
				<tr>
					<th scope="col">Order #</th>
					<th scope="col">Date</th>
					<th scope="col">Total</th>
				</tr>
			</thead>
			<tbody>
  			<tr>
					<td colspan="3"><hr class="none"></td>
				</tr>
        {foreach $orderhistory as $order}
        <tr>
  				<td>
  				  <a href="/shop/receipt/{$order->getShopOrderId()}">{$order->getShopOrderId()}</a>
  				</td>
  				<td>
  				  <a href="/shop/receipt/{$order->getShopOrderId()}">{$order->getCreatedAt('M j, Y, g:ia')}</a>
  				</td>
  				<td>
  				  $<span class="unit-price">{$order->getTotal()|number_format:2}</span>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="3"><hr></td>
  			</tr>
        {/foreach}
        <tr>
					<td colspan="3"><hr class="none"></td>
				</tr>
			</tbody>
		</table>
  </div>
</div>
{include file=$PF_FOOTER}