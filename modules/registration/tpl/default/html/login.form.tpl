<PFForm:Form name="login" id="login">
  <ul class="pairings">
    <li class="spacer">
      <label for="login-emailaddress">Email address <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" class="med" name="login-emailaddress" id="login-emailaddress" /></div>
    </li>
    <li>
      <label for="login-password">Password <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String required="yes" type="password" class="med left" name="login-password" id="login-password" /></div>
    </li>
  </ul>
  <p class="forgot-password"><a href="/registration/forgot">forgot your password?</a></p>
  <input type="submit" class="turq-btn-lg" name="signin" value="sign in">
</PFForm:Form>