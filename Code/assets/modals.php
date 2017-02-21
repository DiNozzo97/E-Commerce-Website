<?php
if (isset($_SESSION['userID'])) { // If the user is signed in then add the following modals to the page ?> 
<!-- Modal displayed for the user to edit their details -->
<div id="editUserModal" class="modal fade" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--            Edit User Modal Header            -->                        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Edit Details</h2>
                <p class="help-block text-center">Edit the information below to update your profile. <br>If you wish to change your password, enter your new password and then confirm it, <br> otherwise your password will remain.</p>
            </div>
            <!--            Edit User Modal Body            --> 
            <div class="modal-body">
                <div id="editUserAlert"></div>
                <!--              Register Main Form              -->                           
                <form id="editUserForm" class="form-horizontal" method="post">

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="firstNameEditUser">First Name</label>  
                        <div class="col-md-6">
                            <input id="firstNameEditUser" name="firstNameEditUser" type="text" placeholder="first name" class="form-control input-md" value="John">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="lastNameEditUser">Last Name</label>  
                        <div class="col-md-6">
                            <input id="lastNameEditUser" name="lastNameEditUser" type="text" placeholder="last name" class="form-control input-md" value="Smith">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="emailEditUser">Email Address</label>  
                        <div class="col-md-6">
                            <input id="emailEditUser" name="emailEditUser" type="text" placeholder="email address" class="form-control input-md" value="john@johnsmith.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="passwordEditUser">Password</label>  
                        <div class="col-md-6">
                            <input id="passwordEditUser" name="passwordEditUser" type="password" placeholder="password" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="passwordEditUser">Confirm Password</label>  
                        <div class="col-md-6">
                            <input id="confirmPasswordEditUser" name="confirmPasswordEditUser" type="password" placeholder="confirm password" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="addressLine1EditUser">Address Line 1</label>  
                        <div class="col-md-6">
                            <input id="addressLine1EditUser" name="addressLine1EditUser" type="text" placeholder="address line 1" class="form-control input-md" value="Middlesex University">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="addressLine2EditUser">Address Line 2</label>  
                        <div class="col-md-6">
                            <input id="addressLine2EditUser" name="addressLine2EditUser" type="text" placeholder="email address" class="form-control input-md" value="The Burroughs">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="cityEditUser">City</label>  
                        <div class="col-md-6">
                            <input id="cityEditUser" name="cityEditUser" type="text" placeholder="city" class="form-control input-md" value="London">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="postcodeEditUser">UK Postcode</label>  
                        <div class="col-md-6">
                            <input id="postcodeEditUser" name="postcodeEditUser" type="text" placeholder="uk postcode" class="form-control input-md" value="NW4 4BT">
                        </div>
                    </div>

                    <button id="editUserButton"type="submit" class="btn btn-success center-block">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } else {  // Otherwise if they aren't signed in then add these modals ?>
<!--     Login Modal (This overlays the page when the user clicks login)       -->
<div id="loginModal" class="modal fade" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--            Login Modal Header            -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Login</h2>
            </div>
            <!--            Login Modal Body            -->
            <div class="modal-body">
                <div id="loginAlert"></div> 
                <!--              Login Main Form              -->
                <form id="loginForm" class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="emailLogin">Email Address</label>  
                        <div class="col-md-6">
                            <input id="emailLogin" name="emailLogin" type="text" placeholder="email address" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="passwordLogin">Password</label>  
                        <div class="col-md-6">
                            <input id="passwordLogin" name="passwordLogin" type="password" placeholder="password" class="form-control input-md">
                        </div>
                    </div>
                    <input id="loginButton" type="button" class="btn btn-info center-block" onclick="processLogin();" value="Login">
                </form>
            </div>
            <!--           Login Modal Footer              -->
            <div class="modal-footer">
                <p class="help-block">No account? <a href="#" data-toggle="modal" data-target="#registerModal">Create one!</a></p>
            </div>
        </div>
    </div>
</div>


<!-- Modal displayed for the user to register -->
<div id="registerModal" class="modal fade" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--            Register Modal Header            -->                        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Register</h2>
            </div>
            <!--            Register Modal Body            -->
            <div class="modal-body">
                <div id="registerAlert"></div>
                <!--              Register Main Form              -->                           
                <form id="registerForm" class="form-horizontal" method="post">

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="firstNameRegister">First Name</label>  
                        <div class="col-md-6">
                            <input id="firstNameRegister" name="firstNameRegister" type="text" placeholder="first name" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="lastNameRegister">Last Name</label>  
                        <div class="col-md-6">
                            <input id="lastNameRegister" name="lastNameRegister" type="text" placeholder="last name" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="emailRegister">Email Address</label>  
                        <div class="col-md-6">
                            <input id="emailRegister" name="emailRegister" type="text" placeholder="email address" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="passwordRegister">Password</label>  
                        <div class="col-md-6">
                            <input id="passwordRegister" name="passwordRegister" type="password" placeholder="password" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="passwordRegister">Confirm Password</label>  
                        <div class="col-md-6">
                            <input id="confirmPasswordRegister" name="confirmPasswordRegister" type="password" placeholder="confirm password" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-inline">
                        <label class="col-md-4 control-label" for="dobDayRegister">Date of Birth</label>
                        <div class="col-md-6 registerMarginOveride">
                            <input id="dobDayRegister" name="dobDayRegister" type="text" size="3" class="form-control" placeholder="DD" maxlength="2">
                            <input id="dobMonthRegister" name="dobMonthRegister" type="text" size="3" class="form-control" placeholder="MM" maxlength="2">
                            <input id="dobYearRegister" name="dobYearRegister" type="text" size="5" class="form-control" placeholder="YYYY" maxlength="4">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="addressLine1Register">Address Line 1</label>  
                        <div class="col-md-6">
                            <input id="addressLine1Register" name="addressLine1Register" type="text" placeholder="address line 1" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="addressLine2Register">Address Line 2</label>  
                        <div class="col-md-6">
                            <input id="addressLine2Register" name="addressLine2Register" type="text" placeholder="email address" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="cityRegister">City</label>  
                        <div class="col-md-6">
                            <input id="cityRegister" name="cityRegister" type="text" placeholder="city" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="postcodeRegister">UK Postcode</label>  
                        <div class="col-md-6">
                            <input id="postcodeRegister" name="postcodeRegister" type="text" placeholder="uk postcode" class="form-control input-md">
                        </div>
                    </div>

                    <button id="registerButton"type="submit" class="btn btn-info center-block">Register</button>
                </form>
            </div>
            <!--           Register Modal Footer              -->
            <div class="modal-footer">
                <p class="help-block">Already have an account? <a id="registerToSignIn" href="#" data-dismiss="modal">Sign in!</a></p>
            </div>
        </div>
    </div>
</div>
<?php } ?>