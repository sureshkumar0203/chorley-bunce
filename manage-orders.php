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



################################################################################################
##############DELETE ORDER DETAILS##############################################################
################################################################################################
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='delete'){
	$del_rec_id=isset($_REQUEST['order_id'])?$dbf->checkSqlInjection($_REQUEST['order_id']):0;
	$testurlkey=$dbf->keyMaker($del_rec_id);
	if($testurlkey!=$_REQUEST['token']){
		header("Location:manage-orders");exit;
	}
	$dbf->deleteFromTable("order_items","order_id='$del_rec_id'");
	$dbf->deleteFromTable("master_order","order_id='$del_rec_id'");
	header("Location:manage-orders");exit;
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
                        <h2 class="panel-title pull-left" style="line-height:34px;">Manage Orders</h2>
                      </div>
                      <div class="panel-body">
                        <div class="dataTable_wrapper">
                          <table class="table table-striped table-bordered table-hover" id="tbl_content">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Chefs Name</th>
                                    <th>Supplier Name</th>
                                    <th>Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                          <tbody>
                          <?php 
                          $cls='odd gradeX';
                          foreach($dbf->fetch("master_order","","order_id","","DESC") as $order_details) {
                          $order_status=($order_details['order_status']=='notdelevrd')?"Not Delivered":"Delivered";
                          $supplier_name=$dbf->getDataFromTable("suppliers","full_name","sid='$order_details[supplier_id]'");                          ?>
                          <tr>
                            <td  style="color:<?php echo $color; ?>"><?php echo $order_details['order_id']; ?></td>
                            <td><?php echo date("jS M, Y",strtotime($order_details['order_date']));?></td>
                            <td><?php echo $order_details['chefs_name'];?></td>
                            <td><?php echo $supplier_name;?></td>
                            <td>&pound; <?php echo number_format($order_details['grand_total'],2,'.',''); ?></td>
                            <td class="text-center">
                            <a href="view-orders?oid=<?php echo $order_details['order_id'];?>&token=<?php echo $dbf->keymaker($order_details['order_id']);?>" class="btn btn-info btn-xs">&nbsp;&nbsp;View&nbsp;&nbsp;</a>
                            <a href="manage-orders?operation=delete&order_id=<?php echo $order_details['order_id'];?>&token=<?php echo $dbf->keyMaker($order_details['order_id']);?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this record ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
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
			'aoColumnDefs': [{'bSortable': false,'aTargets': [5]}]
		});
    });
	$('#lbl_3').addClass('active');
    </script>
</body>
</html>
