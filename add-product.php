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
############################# ADD PRODUCT ###################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){
	if($dbf->checkSecurity($_SERVER)){
		$supplier_id=$dbf->checkXssSqlInjection($_REQUEST['supplier_id']);
		$prd_cat_id = implode(',',$_REQUEST['prd_cat_id']);
		$product_name = $dbf->checkXssSqlInjection($_REQUEST['product_name']);
		$product_code = $dbf->checkXssSqlInjection($_REQUEST['product_code']);
		$qty_details = $dbf->checkXssSqlInjection($_REQUEST['qty_details']);
		
		$prd_unit_name=$dbf->checkXssSqlInjection($_REQUEST['prd_unit_name']);
		$product_price=$dbf->checkXssSqlInjection($_REQUEST['product_price']);
		$product_details = $dbf->checkXssSqlInjection($_REQUEST['product_details']);
		$prd_avl_status=$dbf->checkXssSqlInjection($_REQUEST['prd_avl_status']);
		
		
		$num=$dbf->countRows('products',"supplier_id='$supplier_id' AND product_code='$product_code'");
		
		//echo $supplier_id."--".$prd_cat_id."--".$product_name."--".$product_code."--".$prd_unit_name."--".$product_price."--".$product_details."--".$_FILES['product_photo']['name'];exit;
		
		if($supplier_id=="" || $prd_cat_id=="" || $product_name=="" || $product_code=="" || $prd_unit_name=="" || $product_price=="" || $product_details=="" || $_FILES['product_photo']['name']==""){
		  $msg=1;
		}else if($num > 0){
		  $msg=2;
		}else{
			$uploadDir="product-photos/";
			$thumbnailDir="product-photos/thumb/";
			
			$file_name=$_FILES['product_photo']['name'];
			$file_ext =strtolower(substr(strrchr($file_name, "."), 1));
			$tmp=$_FILES['product_photo']['tmp_name'];
			$type=$_FILES['product_photo']['type'];
			
			if($file_name!='' && $file_ext!="php" && $file_ext!="htaccess" && $file_ext!="txt" && $file_ext!="doc" && $file_ext!="pdf" && $type=='image/gif' || $type=='image/jpg' || $type=='image/jpeg' || $type=='image/pjpeg' || $type=='image/png' || $type=='image/bmp'){
				$fname =time().".".substr(strrchr($file_name, "."), 1);
				move_uploaded_file($tmp,"$uploadDir".$fname);
				
				$temp_path="product-photos/".$fname;
				$imgsize = getimagesize($temp_path);
				$chkwidth = $imgsize[0];
				$chkheight = $imgsize[1];
				
				//Medium Thumb hight width
				if($chkheight <= 300) {
					$thumb_height = $chkheight;
				} else {
					$thumb_height = 300;
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
				$path1="product-photos/".$fname;
				unlink($path1);
			}
			
			$string="supplier_id='$supplier_id',prd_cat_id='$prd_cat_id',product_name='$product_name',product_code='$product_code',qty_details='$qty_details',prd_unit_name='$prd_unit_name',product_price='$product_price',product_details='$product_details',prd_avl_status='$prd_avl_status',product_photo='$fname',created_date=Now(),updated_date=Now()";
			$dbf->insertSet("products",$string);
			header("Location:add-product?msg=3");exit;
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
                  <h1 class="page-header">Add Product Information</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($msg=='1'){?>
                    <span style="color:#F00;">Please enter all fileds value.</span>
					<?php } ?>
                    
                    <?php if($msg=='2'){?>
                    <span style="color:#F00;">This product already exist.</span>
                    <?php } ?>
                   
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_prd" name="frm_prd" method="post" action="add-product" class="form-horizontal" onSubmit="return productValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="insert">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Select Supplier*</label>
                      <select class="form-control" name="supplier_id" id="supplier_id">
                        <option value="">--Select--</option>
                        <?php  foreach($dbf->fetch("suppliers","","full_name","","ASC") as $supplier_det) { ?>
                       <option value="<?php echo $supplier_det['sid']; ?>"><?php echo $supplier_det['full_name']; ?></option>
                        <?php } ?>
                     </select>
                    </div>
                  
                    <div class="form-group">
                      <label>Select Category*</label><span id="chk_msg"></span>
                      <div style="border:1px solid #CCC; padding:5px; border-radius:5px;">
                        <ul style="list-style-type:none; margin:0; padding:0; height:200px; overflow-y:scroll;">
						   <?php foreach($dbf->fetch("categories","","category_name","","ASC") as $cat_det) { ?>
                            <li style="float:left; width:50%; box-sizing:border-box;"><input class='chkcat' type="checkbox" name="prd_cat_id[]" value="<?php echo $cat_det['id']; ?>"/>&nbsp; <?php echo $cat_det['category_name']; ?></li>
                           <?php } ?>
                        </ul>
                      <div style="clear:both;float:none;"></div>
                      
                     
                      </div>
                    </div>
                    
                     <div class="form-group">
                      <label>Product Name*</label>
                      <input class="form-control" name="product_name" id="product_name" value="<?php echo $_REQUEST['product_name']; ?>" autocomplete="off">
                     </div>
                    
                     <div class="form-group">
                      <label>Product Code*</label>
                      <input class="form-control" name="product_code" id="product_code" value="<?php echo $_REQUEST['product_code']; ?>" autocomplete="off">
                     </div>
                     
                     <div class="form-group">
                      <label>Qty Details* </label><span style="color:#F00;"> eg. 5 Case 48 X 86g</span>
                      <input class="form-control" name="qty_details" id="qty_details" value="<?php echo $_REQUEST['qty_details']; ?>" autocomplete="off">
                     </div>
                     
                      <div class="form-group">
                      <label>Product Available Status*</label>
                      <select class="form-control" name="prd_avl_status" id="prd_avl_status">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                     </select>
                     </div>
                     
                  </div>
                  
                  <div class="col-lg-1"></div>
                  
                  <div class="col-lg-4">
                     <div class="form-group">
                      <label>Product Price*</label>
                      <input class="form-control" name="product_price" id="product_price" value="<?php echo $_REQUEST['product_price']; ?>" autocomplete="off"  onBlur="extractNumber(this,2,false)" onKeyUp="extractNumber(this,2,false);" onKeyPress="return blockNonNumbers(this, event, true, false);">
                     </div>
                     
                      <div class="form-group">
                      <label>Unit*</label>
                      <input class="form-control" name="prd_unit_name" id="prd_unit_name" value="<?php echo $_REQUEST['prd_unit_name']; ?>" autocomplete="off">
                     </div>
                     
                     <div class="form-group">
                      <label>Upload product photo*</label>
                      <input type="file" class="form-control" name="product_photo" id="product_photo"><br>
                      <p style="color:#F00;">Note : Image Width = 300 & Height = 300 for better looking.</p>
                      <span id="ph_mesg" style="color:#F00;"></span>
                     </div>
                  </div>
   
              </div>
              
              <div class="col-lg-12 form-group">
                  <label>Product Details*</label>
                  <textarea class="form-control" name="product_details" id="product_details" rows="10"><?php echo $_REQUEST['product_details']; ?></textarea>
              </div>
                
              <div class="col-lg-12">&nbsp;</div>
              
              
             <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="manage-products" class="btn btn-danger"> << Back </a>
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