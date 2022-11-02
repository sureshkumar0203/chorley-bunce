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

#############################################################################
########################### ADD CATEGORY ####################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){ 		
  if($dbf->checkSecurity($_SERVER)){
	  $category_name=$dbf->checkXssSqlInjection($_REQUEST['category_name']);     	
	  $num=$dbf->countRows('categories',"category_name='$category_name'");
	  
	  if($category_name=="" || $_FILES[category_photo][name]==""){
		  $msg=1; 
	  }else if($num > 0){
		  $msg=2;
	  }else{
		  $uploadDir="category-photos/";
		  $thumbnailDir="category-photos/thumb/";
		  
		  $file_name=$_FILES['category_photo']['name'];
		  $file_ext =strtolower(substr(strrchr($file_name, "."), 1));
		  $tmp=$_FILES['category_photo']['tmp_name'];
		  $type=$_FILES['category_photo']['type'];
		  
		  if($file_name!='' && $file_ext!="php" && $file_ext!="htaccess" && $file_ext!="txt" && $file_ext!="doc" && $file_ext!="pdf" && $type=='image/gif' || $type=='image/jpg' || $type=='image/jpeg' || $type=='image/pjpeg' || $type=='image/png' || $type=='image/bmp'){
			  
			  $fname =time().".".substr(strrchr($file_name, "."), 1);
			  move_uploaded_file($tmp,"$uploadDir".$fname);
			  
			  $temp_path="category-photos/".$fname;
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
			  $path1="category-photos/".$fname;
			  unlink($path1);
		  }
		
		$string="category_name='$category_name',category_photo='$fname',created_date=Now(),updated_date=Now()";
		$dbf->insertSet("categories",$string);
		header("Location:add-category?msg=3");
		exit;
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
                  <h1 class="page-header">Add Category</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($msg=='1'){?>
                      <span style="color:#F00;">
                      Please enter categoy name & upload photo.</span>
					<?php } ?>
                    
                    <?php if($msg=='2'){?>
                    <span style="color:#F00;">This category name already exist.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_cat" name="frm_cat" method="post" action="add-category" class="form-horizontal" onSubmit="return categoryValidation();" enctype="multipart/form-data">
            <input type="hidden" name="operation" value="insert">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Category Name</label>
                      <input class="form-control" name="category_name" id="category_name" value="<?php echo $_REQUEST['category_name']; ?>" autocomplete="off">
                    </div>
                   
                    <div class="form-group">
                      <label>Upload category Picture</label>
                      <input type="file" class="form-control" name="category_photo" id="category_photo"><br>
                      <p style="color:#F00;">Note : Image Width = 200 & Height = 200 for better looking.</p>
                      <span id="ph_mesg" style="color:#F00;"></span>
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
</body>
</html>