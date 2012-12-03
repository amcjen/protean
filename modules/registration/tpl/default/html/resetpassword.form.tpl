<PFForm:Form name="forgot-form" id="forgot-form" class="forgot-form">
  <ul class="pairings">
    <li class="spacer">
      <label for="password">New Password <span class="required">*</span></label>
      <div class="input-controls"><PFForm:String type="password" required="yes" class="long" name="password" id="password" /></div>
    </li>
    <li class="spacer">
      <label for="password">New Password (again)<span class="required">*</span></label>
      <div class="input-controls"><PFForm:String type="password" required="yes" class="long" name="password2" id="password2" /></div>
    </li>
  </ul>
  <br />
  <input type="submit" class="turq-btn-lg" name="reset" value="change password" value="changepassword">
</PFForm:Form>