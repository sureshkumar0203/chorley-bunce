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

$editId=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$testurlkey=$dbf->keyMaker($editId);
 
if($testurlkey!=$_GET['token']){ 		
  header("location:manage-measurment_units");exit;
}

	
$unit_info=$dbf->fetchSingle('measurment_units','*',"id='$editId'");

#############################################################################
##################### EDIT UNIT #############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){
	if($dbf->checkSecurity($_SERVER)){
		$unit_name=$dbf->checkXssSqlInjection($_REQUEST['unit_name']);
		$num=$dbf->countRows('measurment_units',"unit_name='$unit_name' AND id!='$editId'");
		if($unit_name==""){
			header("Location:edit-unit?msg=1&editId=$editId&token=$_GET[token]");
			exit;
		}else if($num > 0){
			header("Location:edit-unit?msg=2&editId=$editId&token=$_GET[token]");
			exit;
		}else{
			$update_string="unit_name='$unit_name',updated_date=Now()";
			$dbf->updateTable("measurment_units",$update_string,"id='$editId'");
			header("Location:edit-unit?msg=3&editId=$editId&token=$_GET[token]");
			exit;
		}
	}
	header("Location:manage-units");exit;
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
                  <h1 class="page-header">Edit Unit </h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($_REQUEST['msg']=='1'){?>
                    <span style="color:#F00;">Please enter unit name.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='2'){?>
                    <span style="color:#F00;">This unit name already exist.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Records has been updated successfully.</span>
                    <?php } ?>
                    
                </div>
              </div>
          </div>
          
          
          <form id="frm_unit" name="frm_unit" method="post" class="form-horizontal" onSubmit="return unitValidation();" enctype="multipart/form-data">
          <input type="hidden" name="operation" value="update">
          <div class="row">
          	<div class="col-lg-12">
            	<div class="col-lg-4">
                  <div class="form-group">
                      <label>Unit Name</label>
                      <input class="form-control" name="unit_name" id="unit_name" value="<?php echo $unit_info['unit_name']; ?>" autocomplete="off" maxlength="155">
                  </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
            
            <div class="col-lg-12">&nbsp;</div>
            <div class="col-lg-12">&nbsp;</div>
            
           <div class="form-group col-lg-12">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="manage-units" class="btn btn-danger"> << Back </a>
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