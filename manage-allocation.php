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

if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$del_rec_id=isset($_REQUEST['allo_id'])?$dbf->checkSqlInjection($_REQUEST['allo_id']):0;
	$testurlkey=$dbf->keyMaker($del_rec_id);
	if($testurlkey!=$_REQUEST['token']){
		header("Location:manage-allocation");
		exit;
	}
	//delete chefs
	$dbf->deleteFromTable("job_allocations_suppliers","job_allo_id='$del_rec_id'");
	$dbf->deleteFromTable("job_allocations","allo_id='$del_rec_id'");
	header("Location:manage-allocation");
	exit;
}
?>
<body>
  <div id="wrapper">
  	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
		<?php include('admin-header.php'); ?>
        <?php include('left-menu.php'); ?>
    </nav>
       
    <div id="page-wrapper">
      <div class="row">  
        <div class="row">
          <div class="col-lg-12">&nbsp;</div>
        </div>
       
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading clearfix">
              <h2 class="panel-title pull-left" style="line-height:34px;">Manage Allocation</h2>
              <a href="add-allocation" class="btn btn-primary pull-right">Add Allocation</a>
            </div>
          
            <div class="panel-body">
              <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="tbl_content">
                  <thead>
                      <tr>
                          <th>Chef Name</th>
                          <th>Job Name</th>
                          <th>Location</th>
                          <th>Allocation Date</th>
                          <th class="text-center">Action</th>
                      </tr>
                  </thead>
                  
                  <tbody>
					<?php 
                    $cls='odd gradeX';
                    foreach($dbf->fetch("job_allocations","","allo_id","","DESC") as $allo_det) {
                       $chefs_info=$dbf->fetchSingle('chefs_registration','*',"chefs_id='$allo_det[chef_id]'");
                    ?>
                    <tr>
                      <td style="color:<?php echo $color; ?>"><?php echo $chefs_info['chefs_name']; ?></td>
                      <td> <?php echo $allo_det['job_name']; ?></td>
                      <td><?php echo $allo_det['job_location']; ?></td>
                      <td><?php echo date("jS M, Y",strtotime($allo_det['allocation_date'])); ?></td>
                      <td class="text-center">
                         <a href="edit-allocation?editId=<?php echo $allo_det['allo_id'];?>&token=<?php echo $dbf->keymaker($allo_det['allo_id']);?>" class="btn btn-info btn-xs">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
                        
                        <a href="manage-allocation?operation=delete&allo_id=<?php echo $allo_det['allo_id'];?>&token=<?php echo $dbf->keyMaker($allo_det['allo_id']);?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this record ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php  include('common-js.php'); ?>
  <!-- DataTables JavaScript -->
  <script src="data-tables/jquery.dataTables.min.js"></script>
  <script src="data-tables/dataTables.bootstrap.min.js"></script>
  <script>
	$(document).ready(function() {
		$('#tbl_content').DataTable({
			responsive: true,
			/* Disable initial sort */
			"aaSorting": [],
			/*Stay in same page*/
			"stateSave": true,
			/* Disable sorting columns */
			'aoColumnDefs': [{'bSortable': false,'aTargets': [4]}]
		});
	});
	$('#lbl_3').addClass('active');
  </script>

</body>
</html>
