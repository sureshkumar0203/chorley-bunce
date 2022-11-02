<?php
//echo $chefs_psw_encode = base64_decode(base64_decode("TVRJeg=="));
ob_start();
session_start();
include_once "includes/class.Main.php";
//Object initialization
$dbf = new Main();
if($dbf->checkSession()){
	header('location:home');
	exit;
}	
$page_title='Administrator';
include 'application-top.php';
?>
<body>
	<div style="background-color:#000;">
    <div class="container">
		<center><img src="images/cb_logo@2x.png" width="300"/></center>
    </div>    	
	</div>
    
    <div class="container">
        <div class="row">
         <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title" id="header_txt">Please Sign In</h3>
                    </div>
                    <div class="panel-body" id="txtHint">
                    
                    <div id="response" style="margin-bottom:10px;"></div>
                    
                      <form role="form" method="post" id="show_login" action="login-process" autocomplete="off">
                      <input type="hidden" name="operation" value="login">
                          <fieldset>
                              <div class="form-group">
                                  <input class="form-control" placeholder="E-mail" name="txt_email" id="txt_email" type="email" value="<?php echo $_COOKIE['admin_email']; ?>" required="on" autofocus>
                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Password" name="txt_password" id="txt_password" value="<?php echo $_COOKIE['admin_password']; ?>" required="on" type="password">
                              </div>
                              <div class="checkbox">
                                  <label>
                                      <input type="checkbox" name="remember_me" id="remember_me" value="remember" <?php if($_COOKIE['admin_email'] !='' && $_COOKIE['admin_password'] !=''){ ?>checked="checked" <?php }?>>Remember Me<br>
                                   </label>
                              </div>
                              <input type="submit" name="login" id="login" value="Login" class="btn btn-md btn-success btn-block">
                              
                              <a href="javascript:void(0)" onClick="forgotPassword('showlogin')">Forgot your password? click here</a>
                          </fieldset>
                      </form> 
                        
                      <form role="form" method="post" id="forg_pass" action="" style="display:none">
                        <input type="hidden"  name="operation" id="operation" value="forgotpass">
                            <fieldset>
                                <div class="form-group">
                                	<input class="form-control" placeholder="E-mail" name="email" id="email" type="email" required="on" autofocus>
                                </div>
                                <input type="button" name="resetpass" id="resetpass" value="Submit" class="btn btn-lg btn-success btn-block" onClick="resetPassword();">
                                <a href="javascript:void(0)" onClick="forgotPassword('showforg')">Click here to login</a>
                            </fieldset>
                       </form> 
                         
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/all-validation.js"></script>
</body>
</html>
