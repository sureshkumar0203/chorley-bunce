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

$allo_id=isset($_REQUEST['editId'])?$dbf->checkSqlInjection($_REQUEST['editId']):0;
$testurlkey=$dbf->keyMaker($allo_id);
 
if($testurlkey!=$_GET['token']){ 		
  header("location:manage-allocation");exit;
}
$allo_info=$dbf->fetchSingle('job_allocations','*',"allo_id='$allo_id'");

#############################################################################
##################### EDIT CHEFS ############################################
#############################################################################
if(isset($_POST['operation']) && $_REQUEST['operation']=='update'){
	if($dbf->checkSecurity($_SERVER)){
		$chef_id=$dbf->checkXssSqlInjection(trim($_REQUEST['chef_id']));
		$job_name = $dbf->checkXssSqlInjection(trim($_REQUEST['job_name']));
		$job_location=$dbf->checkXssSqlInjection(trim($_REQUEST['job_location']));
		$allocation_date=$dbf->checkXssSqlInjection(trim($_REQUEST['allocation_date']));
		
		//$chk_allo_ac=$dbf->countRows('job_allocations',"chef_id='$chef_id' AND allocation_date='$allocation_date' AND allo_id!='$allo_id'");
		
		if($chef_id=="" || $job_location=="" || $allocation_date==""){
			header("Location:edit-allocation?msg=1&editId=$allo_id&token=$_GET[token]");
		  	exit;
		}else{
			$string="chef_id='$chef_id',job_name='$job_name',job_location='$job_location',allocation_date='$allocation_date',updated_date=Now()";
			$dbf->updateTable("job_allocations",$string,"allo_id='$allo_id'");
			
			for ($j = 1; $j < $_REQUEST['sup_count']; $j++) {
				$ja_id = $_REQUEST['ja_id'.$j];
				$sup_id = $_REQUEST['sup_id'.$j];
				$sup_ac_no = $_REQUEST['sup_ac_no'.$j];
					
				if($ja_id!=""){
					$string_sup_up="chef_id='$chef_id',sup_id='$sup_id',sup_ac_no='$sup_ac_no',updated_date=Now()";
					$dbf->updateTable("job_allocations_suppliers",$string_sup_up,"ja_id='$ja_id'");
					
					if($ja_id!="" && $sup_id=="" || $sup_ac_no==""){
						$dbf->deleteFromTable("job_allocations_suppliers","ja_id='$ja_id'");
					}
				}
				if($ja_id=="" && $sup_id!="" && $sup_ac_no!=""){
					$string_sup_ins="chef_id='$chef_id',job_allo_id='$allo_id',sup_id='$sup_id',sup_ac_no='$sup_ac_no',created_date=Now(),updated_date=Now()";
					$dbf->insertSet("job_allocations_suppliers",$string_sup_ins);
				}
			}
			header("Location:edit-allocation?msg=3&editId=$allo_id&token=$_GET[token]");exit;
		}
	}
	header("Location:manage-allocation");exit;
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
                  <h1 class="page-header">Edit Allocation</h1>
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                	<?php if($_REQUEST['msg']=='1'){?>
                    <span style="color:#F00;">Please enter all * marked fields value.</span>
					<?php } ?>
                    
                    <?php if($_REQUEST['msg']=='2'){?>
                    <span style="color:#F00;">Allocation already exist in this date.</span>
                    <?php } ?>
                    
                    <?php if($_REQUEST['msg']=='3'){?>
                   <span style="color:#090;">Records has been saved successfully.</span>
                    <?php } ?>
                </div>
              </div>
          </div>
          
          <form id="frm_chefs" name="frm_chefs" method="post" class="form-horizontal" onSubmit="return allocationValidation();" enctype="multipart/form-data">
            <input type="hidden" name="operation" value="update">
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label>Select Chef *</label>
                  <select class="form-control" name="chef_id" id="chef_id">
                    <option value="">--Select--</option>
                    <?php
                    foreach($dbf->fetch("chefs_registration","login_status='1'","chefs_name","","ASC") as $chef_det) { ?>
                      <option value="<?php echo $chef_det['chefs_id']; ?>" <?php if($allo_info['chef_id']==$chef_det['chefs_id']){ echo "selected"; } ?>><?php echo $chef_det['chefs_name']; ?></option>
                    <?php } ?>
                 </select>
                </div>
              </div>
               
              <div class="clearfix"></div>
              <div class="col-lg-12"  style="max-height:350px; overflow:auto;">
                <div class="form-group">
                 <span style="color:red; font-size:12px;" id="msg_chk"></span>
                  <table class="table table-bordered" id="tbl_sup">
                    <tr>
                      <td><strong>Supplier Name*</strong></td>
                      <td><strong>Supplier A/C No.*</strong></td>
                      <td><strong>Supplier Name*</strong></td>
                      <td><strong>Supplier A/C No.*</strong></td>
                    </tr>
                    <?php
                    $sup_count=1;
                    foreach($dbf->fetch("suppliers","","full_name","","ASC") as $sup_det){
                        $sup_allo_info=$dbf->fetchSingle('job_allocations_suppliers','*',"job_allo_id='$allo_id' AND sup_id='$sup_det[sid]'");
                        if($sup_count%2 != 0){ echo '<tr>';}
                    ?>
                    
                      <td height="40">
                      <input type="hidden" name="ja_id<?php echo $sup_count; ?>" id="name<?php echo $sup_count; ?>" value="<?php echo $sup_allo_info['ja_id']; ?>">
                      
                      
                      <input type="checkbox" name="sup_id<?php echo $sup_count; ?>" id="sup_id<?php echo $sup_count; ?>" value="<?php echo $sup_det['sid']; ?>" <?php if($sup_det['sid']==$sup_allo_info['sup_id']){ echo "checked"; } ?> onClick="resetAcno('<?php echo $sup_count; ?>');"> &nbsp; <?php echo $sup_det['full_name']; ?></td>
                      
                      <td><input type="text" class="form-control" name="sup_ac_no<?php echo $sup_count; ?>" id="sup_ac_no<?php echo $sup_count; ?>" autocomplete="off" value="<?php echo $sup_allo_info['sup_ac_no']; ?>" onBlur="chooseSupplier('<?php echo $sup_count; ?>');"></td>
                    
                    <?php
                   if($sup_count%2 == 0){ echo '</tr>';}
                   $sup_count=$sup_count+1; } 
                   ?>
                    <input type="hidden" name="sup_count" id="sup_count" value="<?php echo $sup_count; ?>">
       
                  </table>
                </div>
              </div>
              <div class="clearfix"></div>
                
                
             <div class="col-lg-4">
              <div class="form-group">
                <label>Job Name*</label>
                <input class="form-control" name="job_name" id="job_name" value="<?php echo $allo_info['job_name']; ?>" autocomplete="off">
              </div>
            
              <div class="form-group">
                <label>Location*</label>
                <input class="form-control" name="job_location" id="job_location" value="<?php echo $allo_info['job_location']; ?>" autocomplete="off">
              </div>
              
               <div class="form-group">
                <label>Allocation Date*</label>
                <input class="form-control" name="allocation_date" id="allocation_date" value="<?php echo $allo_info['allocation_date']; ?>" autocomplete="off" readonly>
              </div>
            </div>
              
            <div class="col-lg-12">&nbsp;</div>
            <div class="col-lg-12">&nbsp;</div>
            
            
           <div class="form-group col-lg-12">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="manage-allocation" class="btn btn-danger"> << Back </a>
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
        $("#allocation_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            minDate: new Date(),
            showWeek: true,
         });
    });
    </script>


</body>
</html>