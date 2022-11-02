<?php
ob_start();
session_start();
include_once "includes/class.Main.php";
//Object initialization
$dbf = new Main();
if(!$dbf->checkSession()){
	header('location:./');
	exit;
}	
$page_title='Administrator';
include 'application-top.php';
?>
<body>
    <div id="wrapper">
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
          <?php include('admin-header.php'); ?>
          <?php include('left-menu.php'); ?>
      </nav>
    
      <div id="page-wrapper">
          <div class="row">
              <div class="col-lg-12">
                  <h1 class="page-header">Change Password</h1>
              </div>
          </div>
          
          
          <div class="row">
              <div class="col-lg-12">
                
                <?php if($_REQUEST['msg']=='6'){?>
                <div class="form-group">
                    <span style="color:#090;">Password has been changed successfully.</span>
                </div>
        		<?php } ?>
                
                <?php if($_REQUEST['msg']=='7'){?>
                <div class="form-group">
                    <span style="color:#F00;">You are doing somthing wrong.</span>
                </div>
   				<?php } ?>
                
                
                <?php if($_REQUEST['msg']=='8'){?>
                <div class="form-group">
                    <span style="color:#F00;">Please enter correct current password.</span>
                </div>
   				<?php } ?>
                
                <form id="frm_content" name="frm_content" method="post" action="login-process" class="form-horizontal" onSubmit="return adminChangePassword();">
                <input type="hidden" name="operation" value="changepassword">
                <div class="form-group row">
                    <label class="col-lg-12">Current Password</label>
                     <div class=" col-lg-6">
                        <input class="form-control" type="password" name="old_pwd" id="old_pwd" required="on">
                     </div>
                </div>
                 
                 
                <div class="form-group row">
                    <label class="col-lg-12">New Password</label>
                     <div class=" col-lg-6">
                        <input class="form-control" type="password" name="new_pwd" id="new_pwd" required="on">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Confirm Password</label>
                     <div class=" col-lg-6">
                        <input class="form-control" type="password" name="con_password" id="con_password" required="on">
                     </div>
                </div>
                
                                
                <button type="submit" class="btn btn-primary">Update</button>
                <div class="col-lg-12">&nbsp; </div>
				 
                </form>
			  </div>
          </div>
      </div>
      
    </div>

	<?php  include('common-js.php'); ?>
    <script type="text/javascript" src="js/all-validation.js"></script>
</body>
</html>