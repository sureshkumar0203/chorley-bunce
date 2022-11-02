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
###################### ADD UNIT #############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){ 		
  if($dbf->checkSecurity($_SERVER)){
	  $unit_name=$dbf->checkXssSqlInjection($_REQUEST['unit_name']);     	
	  $num=$dbf->countRows('measurment_units',"unit_name='$unit_name'");
	  
	  if($unit_name==""){
		  $msg=1; 
	  }else if($num > 0){
		  $msg=2;
	  }else{
		  $string="unit_name='$unit_name',created_date=Now(),updated_date=Now()";
		  $dbf->insertSet("measurment_units",$string);
		  header("Location:add-unit?msg=3");
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
                  <h1 class="page-header">Add Unit</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($msg=='1'){?>
                      <span style="color:#F00;">
                      Please enter unit name.</span>
					<?php } ?>
                    
                    <?php if($msg=='2'){?>
                    <span style="color:#F00;">This unit name already exist.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                    <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          
          <form id="frm_unit" name="frm_unit" method="post" action="add-unit" class="form-horizontal" onSubmit="return unitValidation();" enctype="multipart/form-data">
            <input type="hidden" name="operation" value="insert">
            <div class="row">
              <div class="col-lg-12">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>Unit Name</label>
                      <input class="form-control" name="unit_name" id="unit_name" value="<?php echo $_REQUEST['unit_name']; ?>" autocomplete="off" maxlength="155">
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
</body>
</html>