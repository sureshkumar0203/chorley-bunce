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

$editId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$testurlkey=$dbf->keyMaker($editId);
 
if($testurlkey!=$_GET['token']){ 		
  header("location:manage-categories");exit;
}

	
$cat_info=$dbf->fetchSingle('categories','*',"id='$editId'");

#############################################################################
################## EDIT CATEGORY ############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){
	if($dbf->checkSecurity($_SERVER)){
		$category_name=$dbf->checkXssSqlInjection($_REQUEST['category_name']);
		$num=$dbf->countRows('categories',"category_name='$category_name' AND id!='$editId'");
		if($category_name==""){
			header("Location:edit-category?msg=1&editId=$editId&token=$_GET[token]");
			exit;
		}else if($num > 0){
			header("Location:edit-category?msg=2&editId=$editId&token=$_GET[token]");
			exit;
		}else{
			$uploadDir="category-photos/";
			$thumbnailDir="category-photos/thumb/";
			
			$file_name=$_FILES['category_photo']['name'];
			$file_ext =strtolower(substr(strrchr($file_name, "."), 1));
			$tmp=$_FILES['category_photo']['tmp_name'];
			$type=$_FILES['category_photo']['type'];
			
			if($file_name!='' && $file_ext!="php" && $file_ext!="htaccess" && $file_ext!="txt" && $file_ext!="doc" && $file_ext!="pdf" && $type=='image/gif' || $type=='image/jpg' || $type=='image/jpeg' || $type=='image/pjpeg' || $type=='image/png' || $type=='image/bmp'){
				
				//Unlink existing Photo
				$photoInfo=$dbf->fetchSingle('categories','*',"id='$editId'");
				$path1="category-photos/thumb/".$photoInfo['category_photo'];
				unlink($path1);
				
				$fname =time().".".substr(strrchr($file_name, "."), 1);
				move_uploaded_file($tmp,"$uploadDir".$fname);
				
				$temp_path="category-photos/".$fname;
				$imgsize = getimagesize($temp_path);
				$chkwidth = $imgsize[0];
				$chkheight = $imgsize[1];
				
				//Medium Thumb hight width
				if($chkheight <= 100) {
					$thumb_height = $chkheight;
				} else {
					$thumb_height = 100;
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
				$path1="category-photos/".$fname;
				unlink($path1);
				
				$str_photo="category_photo='$fname',updated_date=Now()";
				$dbf->updateTable("categories",$str_photo,"id='$editId'");
			}
			
			$update_string="category_name='$category_name',updated_date=Now()";
			$dbf->updateTable("categories",$update_string,"id='$editId'");
			header("Location:edit-category?msg=3&editId=$editId&token=$_GET[token]");
			exit;
		}
	}
	header("Location:manage-categories");exit;

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
                  <h1 class="page-header">Edit Category </h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($_REQUEST['msg']=='1'){?>
                    <span style="color:#F00;">Please enter category name.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='2'){?>
                    <span style="color:#F00;">This category name already exist.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Records has been updated successfully.</span>
                    <?php } ?>
                    
                </div>
              </div>
          </div>
          
          
          <form id="frm_cat" name="frm_cat" method="post" class="form-horizontal" onSubmit="return editCategoryValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="update">
          <div class="row">
          	<div class="col-lg-12">
            	<div class="col-lg-4">
                  <div class="form-group">
                      <label>Category Name</label>
                      <input class="form-control" name="category_name" id="category_name" value="<?php echo $cat_info['category_name']; ?>" autocomplete="off">
                  </div>
                  
                  <div class="form-group">
                    <label>Upload category Picture</label>
                    <input type="file" class="form-control" name="category_photo" id="category_photo"><br>
					<p style="color:#F00;">Note : Image Width = 100 & Height = 100 for better looking.</p>
                    <span id="ph_mesg" style="color:#F00;"></span>
                    <br>

					<?php if($cat_info['category_photo']!=''){ ?>
                    	<img src="category-photos/thumb/<?php echo $cat_info['category_photo']; ?>" height="40"/>
                    <?php }  ?>
                  </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
            
            <div class="col-lg-12">&nbsp;</div>
            <div class="col-lg-12">&nbsp;</div>
            
           <div class="form-group col-lg-12">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="manage-categories" class="btn btn-danger"> << Back </a>
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