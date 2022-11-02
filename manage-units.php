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
	$del_rec_id=isset($_REQUEST['id'])?$dbf->checkSqlInjection($_REQUEST['id']):0;
	$testurlkey=$dbf->keyMaker($del_rec_id);
	if($testurlkey!=$_REQUEST['token']){
		header("Location:manage-units");
		exit;
	}
	
	$num=$dbf->countRows("products","prd_unit_id='$del_rec_id'");
	if($num==0){
	  $dbf->deleteFromTable("measurment_units","id='$del_rec_id'");
	  header("Location:manage-units");
	  exit;
	}else{
		header("Location:manage-units?msg=1");
		exit;
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
             <!--<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"></h1>
                </div>
             </div>-->
			 
             <div class="row">  
				 <div class="row">
                    <div class="col-lg-12">&nbsp;</div>
				 </div>
                 
                 
                <div class="col-lg-12">
                    <div class="panel panel-default">
                       
                      <div class="panel-heading clearfix">
                        <h2 class="panel-title pull-left" style="line-height:34px;">Manage Measurment Units</h2>
                        <a href="add-unit" class="btn btn-primary pull-right">Add New</a>
                      </div>
                      
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                            <?php if($_REQUEST['msg']==1){ ?>
                               <span style="color:#F00; display:block; margin-bottom:10px;">You can not delete this measurment unit because some product associated with this measurment unit.</span>
                               <?php } ?>
                                <table class="table table-striped table-bordered table-hover" id="tbl_content">
                                    <thead>
                                        <tr>
                                            <th>Unit ID</th>
                                          	<th>Unit Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
									 <?php 
                                     $cls='odd gradeX';
                                     foreach($dbf->fetch("measurment_units","","id","","DESC") as $mea_det) {
                                     ?>
                                      <tr>
                                        <td  style="color:<?php echo $color; ?>" align="center"><?php echo $mea_det['id'];?></td>
                                        <td  style="color:<?php echo $color; ?>">
										 <?php echo $mea_det['unit_name'];?>
                                        </td>
                                        
                                        <td class="text-center">
                                        <a href="edit-unit?editId=<?php echo $mea_det['id'];?>&token=<?php echo $dbf->keymaker($mea_det['id']);?>" class="btn btn-info btn-xs">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
                                       
                                       
                                        
                                        <a href="manage-units?operation=delete&id=<?php echo $mea_det['id'];?>&token=<?php echo $dbf->keyMaker($mea_det['id']);?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this record ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
                                        
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
			'aoColumnDefs': [{'bSortable': false,'aTargets': [2]}]
		});
    });
	$('#lbl_1').addClass('active');
    </script>

</body>
</html>
