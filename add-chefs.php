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

	
#############################################################################
############################## ADD CHEFS ####################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){
	if($dbf->checkSecurity($_SERVER)){
		$chefs_name=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_name']));
		$chefs_email=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_email']));
		$chefs_contact_number = $dbf->checkXssSqlInjection(trim($_REQUEST['chefs_contact_number']));
		$chefs_address=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_address']));
		$chefs_acc_number=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_acc_number']));
		
		$unique_id=$dbf->checkXssSqlInjection(trim($_REQUEST['unique_id']));
		$chefs_psw=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_psw']));
		$chefs_psw_encode = base64_encode(base64_encode(trim($chefs_psw)));
		
		
		$num=$dbf->countRows('chefs_registration',"unique_id='$unique_id'");
		
		if($chefs_name=="" || $chefs_email=="" || $unique_id=="" || $chefs_psw==""){
		  $msg=1;
		}else if($chefs_email!='' && !preg_match('/^[^\W][a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-zA-Z]{2,4}$/', $chefs_email)){
		  $msg=2;
		}else if($num > 0){
		  $msg=3;
		}else{
			$string="chefs_name='$chefs_name',chefs_email='$chefs_email',chefs_contact_number='$chefs_contact_number',chefs_acc_number='$chefs_acc_number',chefs_address='$chefs_address',unique_id='$unique_id',chefs_psw='$chefs_psw_encode',created_date=Now(),updated_date=Now()";
			$dbf->insertSet("chefs_registration",$string);
			
			/*************MAIL GOES TO USER***************/
			$admin_info=$dbf->fetchSingle("core","*","id='1'");
			$admin_email=$admin_info['alt_email']; //From Email
			$admin_name=$admin_info['admin_name'];
			$current_year = date("Y");
			
			//subject and content
			$res_email_template_user=$dbf->fetchSingle("email_template","*","id='8'");
			$subject ="Chorley Bunce Chef Login Credntial";
		
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From:".$admin_email."\n";
			$headers .= "Cc:".CC_MAIL. "\n";
			
			$input_user=$res_email_template_user['contents'];			
			$body = str_replace(array('%FULLNAME%','%LOGINID%','%PASSWORD%','%ADMINEMAIL%','%ADMINNAME%',"%CURRENTYEAR%"),array($chefs_name,$unique_id,$chefs_psw,$admin_email,$admin_name,$current_year),$input_user);
			
			//echo $body;exit;
			$ok=mail($chefs_email,$subject,$body,$headers);
			/*************MAIL GOES TO USER***************/
			
			header("Location:add-chefs?msg=4");exit;
		}
	}
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
                  <h1 class="page-header">Add Chefs Information</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($msg=='1'){?>
                    <span style="color:#F00;">Please enter fullname,email,Login ID & Password.</span>
					<?php } ?>
                    
                    <?php if($msg=='2'){?>
                    <span style="color:#F00;">Please enter valid email address.</span>
                    <?php } ?>
                    
                    <?php if($msg=='3'){?>
                    <span style="color:#F00;">This Login ID address already exist.</span>
                    <?php } ?>
                   
                    <?php if($_REQUEST['msg']=='4'){?>
                    <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_chefs" name="frm_chefs" method="post" action="add-chefs" class="form-horizontal" onSubmit="return chefsValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="insert">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Fullname*</label>
                      <input class="form-control" name="chefs_name" id="chefs_name" value="<?php echo $_REQUEST['chefs_name']; ?>" autocomplete="off">
                    </div>
                  
                    <div class="form-group">
                      <label>Email*</label>
                      <input class="form-control" name="chefs_email" id="chefs_email" value="<?php echo $_REQUEST['chefs_email']; ?>" autocomplete="off">
                    </div>
                    
                     <div class="form-group">
                      <label>Contact Number</label>
                      <input class="form-control" name="chefs_contact_number" id="chefs_contact_number" value="<?php echo $_REQUEST['chefs_contact_number']; ?>" autocomplete="off"  maxlength="15" onKeyPress="return Phone(event);">
                    </div>
                    <div class="form-group">
                      <label>Account Number</label>
                      <input class="form-control" name="chefs_acc_number" id="chefs_acc_number" value="<?php echo $_REQUEST['chefs_acc_number']; ?>" autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control" name="chefs_address" id="chefs_address"><?php echo $_REQUEST['chefs_address']; ?></textarea>
                    </div>

                  </div>
                  
                  <div class="col-lg-2"></div>
                  
                  
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Login ID*</label>
                      <input class="form-control" name="unique_id" id="unique_id" value="<?php echo $_REQUEST['unique_id']; ?>" autocomplete="off" style="text-transform:lowercase;" onKeyUp="chk_xss(this);" maxlength="40">
                    </div>
                    
                    <div class="form-group">
                      <label>Password*</label>
                      <input type="password" class="form-control" name="chefs_psw" id="chefs_psw" autocomplete="off">
                    </div>
                    
                    
                  </div>
                  
                  
   
              </div>
              
              <div class="col-lg-12">&nbsp;</div>
              <div class="col-lg-12">&nbsp;</div>
              
              
             <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="manage-chefs" class="btn btn-danger"> << Back </a>
             </div>
                
                
            </div>
          </form>
          
          
      </div>
      
    </div>

	<?php  include('common-js.php'); ?>
    <script type="text/javascript" src="js/all-validation.js"></script>
	<script>$('#lbl_3').addClass('active');</script>
</body>
</html>