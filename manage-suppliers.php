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
	$del_rec_id=isset($_REQUEST['sid'])?$dbf->checkSqlInjection($_REQUEST['sid']):0;
	$testurlkey=$dbf->keyMaker($del_rec_id);
	
	if($testurlkey!=$_REQUEST['token']){
		header("Location:manage-suppliers");
		exit;
	}
	
	$num=$dbf->countRows("products","supplier_id='$del_rec_id'");
	$num_allo=$dbf->countRows("job_allocations_suppliers","sup_id='$del_rec_id'");
	
	if($num==0 && $num_allo==0){
	  //Unlink existing Photo
	  $supplier_info=$dbf->fetchSingle('suppliers','*',"sid='$del_rec_id'");
	  $path="supplier-photos/thumb/".$supplier_info['supplier_photo'];
	  unlink($path);
	  //Unlink existing Photo
			  
	  //delete supplier
	  $dbf->deleteFromTable("suppliers","sid='$del_rec_id'");
	  header("Location:manage-suppliers");
	  exit;
	}else{
		header("Location:manage-suppliers?msg=1");
		exit;
	}
}
?>
<body>
    <div id="wrapper">
    	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0;">
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
                        <h2 class="panel-title pull-left" style="line-height:34px;">Manage Suppliers</h2>
                        <a href="add-supplier" class="btn btn-primary pull-right">Add New</a>
                      </div>
                      
                      
                      
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                               
                               <?php if($_REQUEST['msg']==1){ ?>
                               <span style="color:#F00; display:block; margin-bottom:10px;">
                               You can not delete this supplier because some product/job allocation associated with this supplier.</span>
                               <?php } ?>
                            
                                <table class="table table-striped table-bordered table-hover" id="tbl_content">
                                    <thead>
                                        <tr>
                                          <th>Supplier ID</th>
                                          	<th>Supplier Name</th>
                                            <th>Email</th>
                                            <th>Contact Number</th>
                                            <th>Photo</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                  
                                    <tbody>
                                     <?php 
                                     $cls='odd gradeX';
                                     foreach($dbf->fetch("suppliers","","sid","","DESC") as $supplier_det) {
									 ?>
                                      <tr>
                                        <td  style="color:<?php echo $color; ?>" align="center"><?php echo $supplier_det['sid']; ?></td>
                                        <td  style="color:<?php echo $color; ?>"><?php echo $supplier_det['full_name']; ?></td>
                                        
                                        <td><?php echo $supplier_det['email']; ?></td>
                                        
                                        <td> <?php echo $supplier_det['contact_number']; ?></td>
                                       
                                        <td>
                                        <img src="supplier-photos/thumb/<?php echo $supplier_det['supplier_photo']; ?>" height="30"/></td>
                                        <td class="text-center">
                                        <a href="edit-supplier?editId=<?php echo $supplier_det['sid'];?>&token=<?php echo $dbf->keymaker($supplier_det['sid']);?>" class="btn btn-info btn-xs">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
                                       
                                        <a href="manage-suppliers?operation=delete&sid=<?php echo $supplier_det['sid'];?>&token=<?php echo $dbf->keyMaker($supplier_det['sid']);?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this suppliers ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
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
			'aoColumnDefs': [{'bSortable': false,'aTargets': [4,5]}]
		});
    });
	$('#lbl_1').addClass('active');
    </script>

</body>
</html>
