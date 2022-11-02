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

ini_set('memory_limit', '-1');
ini_set('upload_max_filesize','1024M');
ini_set('max_execution_time','3600');

require_once 'Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding("UTF-8");

require_once 'PHPExcel/Classes/PHPExcel.php';
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
	
#############################################################################
########################### ADD PRODUCT EXCEL SHEET #########################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){
	if($dbf->checkSecurity($_SERVER)){
		if($_FILES['upload_excel']['name']!=''){
			$up_file_name=$_FILES['upload_excel']['name'];
			$file_ext = substr(strrchr($up_file_name,'.'),1);
			if($file_ext!="xlsx" && $file_ext!="xls"){
				$msg=1;
			}
			if($file_ext=='xlsx'){
				$objPHPExcel = $objReader->load($_FILES['upload_excel']['tmp_name']);
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$highestRow = $objWorksheet->getHighestRow(); 
				$highestColumn = $objWorksheet->getHighestColumn(); 
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$rows = array();
				
				for($row= 2; $row <= $highestRow; $row++) {
					$supplier_id=trim($objPHPExcel->getActiveSheet()->getCell('A'.$row)->getValue());
					$prd_cat_id=trim($objPHPExcel->getActiveSheet()->getCell('B'.$row)->getValue());
					$product_name=trim($objPHPExcel->getActiveSheet()->getCell('C'.$row)->getValue());
					$product_code=trim($objPHPExcel->getActiveSheet()->getCell('D'.$row)->getValue());
					$qty_details = trim($objPHPExcel->getActiveSheet()->getCell('E'.$row)->getValue());
					$product_photo=trim($objPHPExcel->getActiveSheet()->getCell('F'.$row)->getValue());
					$prd_unit_name=trim($objPHPExcel->getActiveSheet()->getCell('G'.$row)->getValue());
					$product_price=trim($objPHPExcel->getActiveSheet()->getCell('H'.$row)->getValue());
					$product_details=trim($objPHPExcel->getActiveSheet()->getCell('I'.$row)->getValue());
					
					$cnt_rows=$dbf->countRows("products","supplier_id='$supplier_id' AND product_code='$product_code'");
					if($supplier_id!='' && $cnt_rows==0){
					  $string="supplier_id='$supplier_id',prd_cat_id='$prd_cat_id',product_name='$product_name',product_code='$product_code',qty_details='$qty_details',product_photo='$product_photo',prd_unit_name='$prd_unit_name',product_price='$product_price',product_details='$product_details',prd_avl_status='Yes',created_date=now(),updated_date=now()";
					  $dbf->insertSet("products",$string);
					 
					}
				}
				
			}else if($file_ext=='xls'){
				$data->read($_FILES['upload_excel']['tmp_name']);
				for($j = 1; $j <= $data->sheets[0][numRows]; $j++){
					$supplier_id = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][1])));
					$prd_cat_id = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][2])));
					$product_name = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][3])));
					$product_code = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][4])));
					$qty_details = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][5])));
					$product_photo = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][6])));
					$prd_unit_name = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][7])));
					$product_price = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][8])));
					$product_details = strtolower(trim(addslashes($data->sheets[0][cells][$j+1][9])));
					
					$cnt_rows=$dbf->countRows("products","supplier_id='$supplier_id' AND product_code='$product_code'");
					
					if($supplier_id!='' && $cnt_rows==0){
					  $string="supplier_id='$supplier_id',prd_cat_id='$prd_cat_id',product_name='$product_name',product_code='$product_code',qty_details='$qty_details',product_photo='$product_photo',prd_unit_name='$prd_unit_name',product_price='$product_price',product_details='$product_details',prd_avl_status='Yes',created_date=now(),updated_date=now()";
					  $dbf->insertSet("products",$string);
					}
				}
			}
			header("Location:manage-products");exit;
		}else{
			$msg=2;
		}
		//header("Location:manage-products");exit;
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
                  <h1 class="page-header">Upload Product Excel Sheet</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($msg=='1'){?>
                      <span style="color:#F00;">
                      Please upload xlsx or xls format file.</span>
					<?php } ?>
                    
                    <?php if($msg=='2'){?>
                    <span style="color:#F00;">Upload product excel sheet.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Product has been uploaded successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_upload" name="frm_upload" method="post" action="upload-excel" class="form-horizontal" onSubmit="return productExcelValidation();" enctype="multipart/form-data">
            <input type="hidden" name="operation" value="insert">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Upload Product Excel</label>
                      <input type="file" class="form-control" name="upload_excel" id="upload_excel">
                      <p id="ph_mesg" style="color:#F00;"></p>
                    </div>
                  </div>
                  
                  <div class="col-lg-2"></div>
                  
              </div>
              
              <div class="col-lg-12">&nbsp;</div>
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