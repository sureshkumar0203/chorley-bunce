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

	
$edit_adm_info=$dbf->fetchSingle('core','*',"id='1'");
#############################################################################
################## EDIT CONTENT #############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){ 		
  if($dbf->checkSecurity($_SERVER)){	
	  $admin_name=$dbf->checkXssSqlInjection($_REQUEST['admin_name']);
	  $email=$dbf->checkXssSqlInjection($_REQUEST['email']);
	  $alt_email=$dbf->checkXssSqlInjection($_REQUEST['alt_email']);
	  $contact_no=$dbf->checkXssSqlInjection($_REQUEST['contact_no']);
	  $site_url = $dbf->checkXssSqlInjection($_REQUEST['site_url']);
	  
	  $facebook_left_url=$dbf->checkXssSqlInjection($_REQUEST['facebook_left_url']); 
	  $facebook_right_url=$dbf->checkXssSqlInjection($_REQUEST['facebook_right_url']); 
	  
	  
	  $twitter_left_url=$dbf->checkXssSqlInjection($_REQUEST['twitter_left_url']); 
	  $twitter_right_url=$dbf->checkXssSqlInjection($_REQUEST['twitter_right_url']); 
	  
	  
	  $string="admin_name='$admin_name', email='$email',alt_email='$alt_email',contact_no='$contact_no',site_url='$site_url',facebook_left_url='$facebook_left_url',facebook_right_url='$facebook_right_url',twitter_left_url='$twitter_left_url',twitter_right_url='$twitter_right_url'";
	  
	  $dbf->updateTable("core",$string,"id='1'");
	  header("Location:my-profile?msg=6");exit;	
  }
  header("Location:my-profile?msg=7");exit;	
}
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
                  <h1 class="page-header">My Profile</h1>
              </div>
          </div>
          
          
          <div class="row">
              <div class="col-lg-12">
              	<?php if($_REQUEST['msg']=='7'){?>
                <div class="form-group">
                    <span style="color:#F00;">You are doing somthing wrong.</span>
                </div>
   				<?php } ?>
                
                <?php if($_REQUEST['msg']=='6'){?>
                <div class="form-group">
                    <span style="color:#090;">Records has been updated successfully.</span>
                </div>
        		<?php } ?>
                
                <form id="frm_content" name="frm_content" method="post" action="" class="form-horizontal" onSubmit="return contentValidation();">
                <input type="hidden" name="operation" value="update">
                <div class="form-group row">
                    <label class="col-lg-12">Name</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="admin_name" id="admin_name" required="on" value="<?php echo $edit_adm_info['admin_name']; ?>">
                     </div>
                </div>
                 
                 
                <div class="form-group row">
                    <label class="col-lg-12">Email (Login) </label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="email" id="email" required="on" value="<?php echo $edit_adm_info['email']; ?>">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Alternate Email (Mailing)</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="alt_email" id="alt_email" required="on" value="<?php echo $edit_adm_info['alt_email']; ?>" type="email">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Contact No. </label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="contact_no" id="contact_no" required="on" value="<?php echo $edit_adm_info['contact_no']; ?>">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Site Url</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="site_url" id="site_url" required="on" value="<?php echo $edit_adm_info['site_url']; ?>">
                     </div>
                </div>
                
                
                <div class="form-group row">
                    <label class="col-lg-12">Facebook Left Url</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="facebook_left_url" id="facebook_left_url" required="on" value="<?php echo $edit_adm_info['facebook_left_url']; ?>">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Facebook Right Url</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="facebook_right_url" id="facebook_right_url" required="on" value="<?php echo $edit_adm_info['facebook_right_url']; ?>">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Twitter Left Url</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="twitter_left_url" id="twitter_left_url" required="on" value="<?php echo $edit_adm_info['twitter_left_url']; ?>">
                     </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-lg-12">Twitter Right Url</label>
                     <div class=" col-lg-6">
                        <input class="form-control" name="twitter_right_url" id="twitter_right_url" required="on" value="<?php echo $edit_adm_info['twitter_right_url']; ?>">
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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
</body>
</html>