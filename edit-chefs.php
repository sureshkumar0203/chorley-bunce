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

$chefs_id=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$testurlkey=$dbf->keyMaker($chefs_id);
 
if($testurlkey!=$_GET['token']){ 		
  header("location:manage-chefs");exit;
}
$chefs_info=$dbf->fetchSingle('chefs_registration','*',"chefs_id='$chefs_id'");

#############################################################################
##################### EDIT CHEFS ############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){
	if($dbf->checkSecurity($_SERVER)){
		$chefs_name=$dbf->checkXssSqlInjection($_REQUEST['chefs_name']);
		$chefs_email=$dbf->checkXssSqlInjection($_REQUEST['chefs_email']);
		$chefs_contact_number = $dbf->checkXssSqlInjection($_REQUEST['chefs_contact_number']);
		$chefs_address=$dbf->checkXssSqlInjection($_REQUEST['chefs_address']);
		$chefs_acc_number=$dbf->checkXssSqlInjection(trim($_REQUEST['chefs_acc_number']));
		
		$unique_id = $dbf->checkXssSqlInjection($_REQUEST['unique_id']);
		$chefs_psw=$dbf->checkXssSqlInjection($_REQUEST['chefs_psw']);
		$chefs_psw_encode = base64_encode(base64_encode($chefs_psw));
		
		
		if($chefs_name=="" || $chefs_email=="" || $chefs_psw==""){
			header("Location:edit-chefs?msg=1&editId=$chefs_id&token=$_GET[token]");
		  	exit;
		}else if($chefs_email!='' && !preg_match('/^[^\W][a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-zA-Z]{2,4}$/', $chefs_email)){
			header("Location:edit-chefs?msg=2&editId=$chefs_id&token=$_GET[token]");
		  	exit;
		}else{
			$string="chefs_name='$chefs_name',chefs_email='$chefs_email',chefs_contact_number='$chefs_contact_number',chefs_acc_number='$chefs_acc_number',chefs_address='$chefs_address',login_status='1',updated_date=Now()";
			
			//$string="chefs_acc_number='$chefs_acc_number',login_status='1',updated_date=Now()";
			$dbf->updateTable("chefs_registration",$string,"chefs_id='$chefs_id'");
			
			
			/*************MAIL GOES TO CHEFS ABOUT ACCOUNT APPROVED***************/
			if($chefs_info['chefs_acc_number']!=$chefs_acc_number){
				$admin_info=$dbf->fetchSingle("core","*","id='1'");
				$admin_email=$admin_info['alt_email']; //From Email
				$admin_name=$admin_info['admin_name'];
				$current_year = date("Y");
		
				//subject and content
				$res_email_template_user=$dbf->fetchSingle("email_template","*","id='1'");
				$subject ="Chorley Bunce approved your account";
			
				$headers = "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";
				$headers .= "From:".$admin_email."\n";
				$headers .= "Cc:".CC_MAIL. "\n";
				
				
				$input_user=$res_email_template_user['contents'];			
				$body = str_replace(array('%FULLNAME%','%LOGINID%','%PASSWORD%','%ADMINEMAIL%','%ADMINNAME%',"%CURRENTYEAR%"),array($chefs_name,$unique_id,$chefs_psw,$admin_email,$admin_name,$current_year),$input_user);
				
				//echo $body;exit;
				$ok=mail($chefs_email,$subject,$body,$headers);
				/*************MAIL GOES TO CHEFS ABOUT ACCOUNT APPROVED***************/
			}
			
			//header("Location:manage-chefs");exit;
			header("Location:edit-chefs?msg=3&editId=$chefs_id&token=$_GET[token]");exit;
		}
	}
	header("Location:manage-chefs");exit;

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
                  <h1 class="page-header">Chefs Information </h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($_REQUEST['msg']=='1'){?>
                    <span style="color:#F00;">Please enter fullname,email & Password.</span>
					<?php } ?>
                    
                    <?php if($_REQUEST['msg']=='2'){?>
                    <span style="color:#F00;">Please enter valid email address.</span>
                    <?php } ?>
                   
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">This chefs registration approved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          <form id="frm_chefs" name="frm_chefs" method="post" class="form-horizontal" onSubmit="return chefsValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="update">
          <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label>Fullname*</label>
                    <input class="form-control" name="chefs_name" id="chefs_name" value="<?php echo $chefs_info['chefs_name']; ?>" autocomplete="off">
                  </div>
                
                  <div class="form-group">
                    <label>Email*</label>
                    <input class="form-control" name="chefs_email" id="chefs_email" value="<?php echo $chefs_info['chefs_email']; ?>" autocomplete="off">
                  </div>
                  
                   <div class="form-group">
                    <label>Contact Number*</label>
                    <input class="form-control" name="chefs_contact_number" id="chefs_contact_number" value="<?php echo $chefs_info['chefs_contact_number']; ?>" autocomplete="off" maxlength="15" onKeyPress="return Phone(event);">
                  </div>
                  
                  
                  <div class="form-group">
                    <label>Address*</label>
                    <textarea class="form-control" name="chefs_address" id="chefs_address"><?php echo $chefs_info['chefs_address']; ?></textarea>
                  </div>

                </div>
                
                <div class="col-lg-2"></div>
                
                
                <div class="col-lg-4">
                  <div class="form-group">
                    <label>Login ID*</label>
                    <input class="form-control" name="unique_id" id="unique_id" value="<?php echo $chefs_info['unique_id']; ?>" autocomplete="off" style="text-transform:lowercase;" onKeyUp="chk_xss(this);" maxlength="140" readonly>
                  </div>
                  
                  <div class="form-group">
                    <label>Password*</label>
                    <input type="text" class="form-control" name="chefs_psw" id="chefs_psw" autocomplete="off" value="<?php echo base64_decode(base64_decode($chefs_info['chefs_psw'])); ?>" maxlength="140" readonly>
                  </div>
                  
                  
                   <div class="form-group">
                    <label>Account Number*</label>
                    <input class="form-control" name="chefs_acc_number" id="chefs_acc_number" value="<?php echo $chefs_info['chefs_acc_number']; ?>">
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
    
    <!--date picker-->
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    <script src="datepicker/jquery-ui.js"></script>
    <script type="text/javascript">
    $(function() {
        $("#expiry_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            //minDate: new Date(),
            showWeek: true,
         });
    });
    </script>



</body>
</html>