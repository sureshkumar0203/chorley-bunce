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
include('photo-cropping.php');


$sid=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$testurlkey=$dbf->keyMaker($sid);
 
if($testurlkey!=$_GET['token']){ 		
  header("location:manage-suppliers");exit;
}
$supplier_info=$dbf->fetchSingle('suppliers','*',"sid='$sid'");

#############################################################################
################## EDIT SUPPLIER ############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){
	if($dbf->checkSecurity($_SERVER)){
		$full_name=$dbf->checkXssSqlInjection($_REQUEST['full_name']);
		$email=$dbf->checkXssSqlInjection($_REQUEST['email']);
		$contact_number = $dbf->checkXssSqlInjection($_REQUEST['contact_number']);
		$address=$dbf->checkXssSqlInjection($_REQUEST['address']);
		$supp_acc_number=$dbf->checkXssSqlInjection($_REQUEST['supp_acc_number']);
		
		if($full_name=="" || $email==""){
			header("Location:edit-supplier?msg=1&editId=$sid&token=$_GET[token]");
		  	exit;
		}else if($email!='' && !preg_match('/^[^\W][a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-zA-Z]{2,4}$/', $email)){
			header("Location:edit-supplier?msg=2&editId=$sid&token=$_GET[token]");
		  	exit;
		}else{
			$uploadDir="supplier-photos/";
			$thumbnailDir="supplier-photos/thumb/";
			
			$file_name=$_FILES['supplier_photo']['name'];
			$file_ext =strtolower(substr(strrchr($file_name, "."), 1));
			$tmp=$_FILES['supplier_photo']['tmp_name'];
			$type=$_FILES['supplier_photo']['type'];
			
			if($file_name!='' && $file_ext!="php" && $file_ext!="htaccess" && $file_ext!="txt" && $file_ext!="doc" && $file_ext!="pdf" && $type=='image/gif' || $type=='image/jpg' || $type=='image/jpeg' || $type=='image/pjpeg' || $type=='image/png' || $type=='image/bmp'){
				
				//Unlink existing Photo
				$photoInfo=$dbf->fetchSingle('suppliers','*',"sid='$sid'");
				$path1="supplier-photos/thumb/".$photoInfo['supplier_photo'];
				unlink($path1);
				
				$fname =time().".".substr(strrchr($file_name, "."), 1);
				move_uploaded_file($tmp,"$uploadDir".$fname);
				
				$temp_path="supplier-photos/".$fname;
				$imgsize = getimagesize($temp_path);
				$chkwidth = $imgsize[0];
				$chkheight = $imgsize[1];
				
				//Medium Thumb hight width
				if($chkheight <= 200) {
					$thumb_height = $chkheight;
				} else {
					$thumb_height = 200;
				}
				$thumb_width = ($imgsize[0] * $thumb_height)/$imgsize[1];
					
				################################## For BMP Image #####################
				$imgInfo = getimagesize($uploadDir . $fname);					
				if ($imgInfo['mime'] == 'image/bmp' || $imgInfo['mime']=='image/x-ms-bmp') {
					$srcBMP = $uploadDir . $fname;
					$srcJPG = substr($uploadDir . $fname,0,strrpos($uploadDir . $fname,".")+1)."jpg";
					bmp2gd($srcBMP,$srcJPG);
					$fname = substr($fname,0,strrpos($fname,".")+1)."jpg";
				}
				################################## For BMP Image #####################
				$thumbObj = new photo_cropping_manager($uploadDir . $fname, $thumbnailDir . $fname);
				$thumbObj->get_container_thumb($thumb_width, $thumb_height, 0, 0);	 //Medium Thumb
				
				//unlink the photo from the root directory
				$path1="supplier-photos/".$fname;
				unlink($path1);
				
				$str_photo="supplier_photo='$fname',updated_date=Now()";
				$dbf->updateTable("suppliers",$str_photo,"sid='$sid'");
			}
			$string="full_name='$full_name',email='$email',contact_number='$contact_number',address='$address',supp_acc_number='$supp_acc_number',updated_date=Now()";
		$dbf->updateTable("suppliers",$string,"sid='$sid'");
		header("Location:edit-supplier?msg=4&editId=$sid&token=$_GET[token]");exit;
		}
	}
	header("Location:manage-suppliers");exit;

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
                  <h1 class="page-header">Edit Supplier Information </h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($_REQUEST['msg']=='1'){?>
                    <span style="color:#F00;">Please enter fullname,email & supplier photo.</span>
					<?php } ?>
                    
                    <?php if($_REQUEST['msg']=='2'){?>
                    <span style="color:#F00;">Please enter valid email address.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#F00;">This email address already exist.</span>
                    <?php } ?>
                   
                    <?php if($_REQUEST['msg']=='4'){?>
                    <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_supplier" name="frm_supplier" method="post" class="form-horizontal" onSubmit="return editSupplierValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="update">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Fullname*</label>
                      <input class="form-control" name="full_name" id="full_name" value="<?php echo $supplier_info['full_name']; ?>" autocomplete="off">
                    </div>
                  
                    <div class="form-group">
                      <label>Email*</label>
                      <input class="form-control" name="email" id="email" value="<?php echo $supplier_info['email']; ?>" autocomplete="off">
                    </div>
                    
                     <div class="form-group">
                      <label>Contact Number</label>
                      <input class="form-control" name="contact_number" id="contact_number" value="<?php echo $supplier_info['contact_number']; ?>" autocomplete="off" maxlength="15" onKeyPress="return Phone(event);">
                    </div>
                     <div class="form-group">
                      <label>Account Number</label>
                      <input class="form-control" name="supp_acc_number" id="supp_acc_number" value="<?php echo $supplier_info['supp_acc_number']; ?>" autocomplete="off" >
                    </div>
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control" name="address" id="address"><?php echo $supplier_info['address']; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label>Upload supplier photo*</label>
                      <input type="file" class="form-control" name="supplier_photo" id="supplier_photo"><br>
                      <p style="color:#F00;">Note : Image Width = 200 & Height = 200 for better looking.</p>
                      <span id="ph_mesg" style="color:#F00;"></span>
                      <br>

					<?php if($supplier_info['supplier_photo']!=''){ ?>
                    	<img src="supplier-photos/thumb/<?php echo $supplier_info['supplier_photo']; ?>" height="40"/>
                    <?php }  ?>
                    
                    </div>
                  </div>
                  
                  <div class="col-lg-2"></div>
   
              </div>
              
              <div class="col-lg-12">&nbsp;</div>
              <div class="col-lg-12">&nbsp;</div>
              
              
             <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="manage-suppliers" class="btn btn-danger"> << Back </a>
             </div>
                
                
            </div>
          </form>
          
          
      </div>
      
    </div>

	<?php  include('common-js.php'); ?>
    <script type="text/javascript" src="js/all-validation.js"></script>
	<script>$('#lbl_1').addClass('active');</script>
    
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