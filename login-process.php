<?php
ob_start();
session_start();
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new Main();
#################################################################
######################   Login Page 	#########################
#################################################################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=="login"){	
	if($dbf->checkSecurity($_SERVER)){		
		$email=$dbf->checkXssSqlInjection($_POST['txt_email']);
		$password=addslashes(md5($_POST['txt_password']));			
		$login = $dbf->checkLogin($email,$password);
		if($login){
			if($_REQUEST['remember_me']=='remember'){
				setcookie("admin_email",$email,time()+3600);
				setcookie("admin_password",$_POST['txt_password'],time()+3600);
			}else{
				setcookie("admin_email", "", time()-3600);
				setcookie("admin_password", "", time()-3600);
			}
			// Login Success
			header("location:home");exit;
		}else{			
			// Login Failed
			header("location:./");exit;
		}
	}else{
		header("location:./");exit;
	}
}


#################################################################
######################   Change Password ########################
#################################################################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='changepassword'){	
	if($dbf->checkSecurity($_SERVER)){	
		$cur_password=md5(trim($_POST['old_pwd']));
		if($dbf->countRows("core","id='$_SESSION[admin_id]' AND password='$cur_password'")==1){
			$password=md5(trim($_POST['new_pwd']));
			$string="password='$password'";
			$dbf->updateTable("core",$string,"id='$_SESSION[admin_id]'");
			header("Location:change-password?msg=6");
			exit;			
		}else{
			header("Location:change-password?msg=8");
			exit;
		}
	}else{
		header("Location:change-password?msg=7");;exit;
	}
}
#################################################################
######################   Update Profile  ########################
#################################################################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='updateprofile'){
	if($dbf->checkSecurity($_SERVER)){
		$admin_name=$dbf->checkXssSqlInjection($_POST['admin_name']);
		$email=$dbf->checkXssSqlInjection($_POST['email']);
		$alt_email=$dbf->checkXssSqlInjection($_POST['alt_email']);
		$contact_no=$dbf->checkXssSqlInjection($_POST['contact_no']);
		$site_url=$dbf->checkXssSqlInjection($_POST['site_url']);
		
		if(!$dbf->checkEmailAddress($email) || !$dbf->checkEmailAddress($alt_email)){
			header("location:myProfile?msg=3");exit;				
		}
		$string="admin_name='$admin_name',email='$email',alt_email='$alt_email',contact_no='$contact_no',site_url='$site_url'";
		$dbf->updateTable("core",$string,"id='1'");
		header("Location:myProfile?msg=6");exit;
	}else{
		header("location:myProfile");exit;
	}
}

#################################################################
######################   Forgot Password  #######################
#################################################################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='forgotpass'){
	if($dbf->checkSecurity($_SERVER)){
		$email=$dbf->checkXssSqlInjection($_REQUEST['mail']);
		if($dbf->countRows("core","email='$email'")==1){
			$new_password = $dbf->createRandomPassword();
			$new_password_enc = md5($new_password);
			$dbf->updateTable("core","password='$new_password_enc'","email='$email'");
			
			$current_year = date("Y");
			
			/*************MAIL GOES TO ADMIN***************/
			$admin_info=$dbf->fetchSingle("core","*","email='$email'");
			$admin_name = $admin_info['admin_name'];
			$admin_email=$admin_info['alt_email']; //From Email
			
			//subject and content
			$email_template=$dbf->fetchSingle("email_template","*","id='5'");
			$subject  = "Password has been changed successfully";
		
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "From:$admin_email\n";
			
			$input=$email_template['contents'];			
			$body = str_replace(array('%ADMINNAME%','%EMAIL%','%PASSWORD%','%CURRENTYEAR%'),array($admin_name,$email,$new_password,$current_year),$input);
			
			//echo $body;exit;
			$ok=mail($email,$subject,$body,$headers);
			/*************MAIL GOES TO ADMIN***************/
			echo 1;exit;
		}else{
			echo 2;exit;
		}
	}
}
?>