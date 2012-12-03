<PFForm:Form name="new-user" id="new-user">
  <ul class="pairings">
    <li class="spacer">
      <label for="firstname">First Name <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" class="med" name="firstname" id="firstname" /></div>
    </li>
    <li>
      <label for="lastname">Last Name <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" class="med left" name="lastname" id="lastname" /></div>
    </li>
    <li class="spacer">
      <label for="email">Email address <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" class="med" name="email" id="email" /></div>
    </li>
		<li>
<label for="email2">Re-type email address <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" class="med left" name="email2" id="email2" /></div>
</li>

    <li class="spacer">
      <label for="password">Password <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" type="password" class="med" name="password" id="password" /></div>
    </li>
    <li>
      <label for="password2">Re-type password <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" type="password" class="med left" name="password2" id="password2" /></div>
    </li>
  </ul>
  <p><input type="checkbox" name=""> send me email updates</p>
  <input type="submit" class="turq-btn-lg" name="createaccount" id="createaccount" value="create account">
</PFForm:Form>