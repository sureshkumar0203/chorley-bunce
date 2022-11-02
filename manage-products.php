<?php
ob_start();
session_start();
include_once "includes/class.Main.php";
//Object initialization
$dbf = new Main();
if (!$dbf->checkSession()) {
    header('location:./');
    exit;
}

$page_title = 'Administrator';
include 'application-top.php';

if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'delete') {
    $del_rec_id = isset($_REQUEST['product_id']) ? $dbf->checkSqlInjection($_REQUEST['product_id']) : 0;
    $testurlkey = $dbf->keyMaker($del_rec_id);

    if ($testurlkey != $_REQUEST['token']) {
        header("Location:manage-products");
        exit;
    }

    //Unlink existing Photo
    $prd_info = $dbf->fetchSingle('products', '*', "product_id='$del_rec_id'");
    $path = "product-photos/thumb/" . $prd_info['product_photo'];
    unlink($path);
    //Unlink existing Photo
    //delete supplier
    $dbf->deleteFromTable("products", "product_id='$del_rec_id'");
    header("Location:manage-products");
    exit;
}

//Product Block/Unblock code starts here
if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'act_product') {
    $rec_id = isset($_REQUEST['prd_id']) ? $dbf->checkSqlInjection($_REQUEST['prd_id']) : 0;
    $testurlkey = $dbf->keyMaker($rec_id);

    if ($testurlkey != $_REQUEST['token']) {
        header("location:manage-products");
        exit;
    }
    $dbf->updateTable("products", "prd_status='0'", "product_id='$rec_id'");
    header("Location:manage-products");
}

if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'block_product') {
    $rec_id = isset($_REQUEST['prd_id']) ? $dbf->checkSqlInjection($_REQUEST['prd_id']) : 0;
    $testurlkey = $dbf->keyMaker($rec_id);

    if ($testurlkey != $_REQUEST['token']) {
        header("location:manage-products");
        exit;
    }

    $dbf->updateTable("products", "prd_status='1'", "product_id='$rec_id'");
    header("Location:manage-products");
}
//Product Block/Unblock code ends here
?>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php include('left-menu.php'); ?>
            <?php include('admin-header.php'); ?>
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
                            <h2 class="panel-title pull-left" style="line-height:34px;">Manage Products</h2>

                            <div class="pull-right">
                                <a href="add-product" class="btn btn-primary ">Add New</a>
                                &nbsp;&nbsp;
                                <a href="upload-excel" class="btn btn-primary">Upload Excel</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="tbl_content">
                                    <thead>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <th>Product Category</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $cls = 'odd gradeX';
                                        foreach ($dbf->fetch("products", "", "product_id", "", "DESC") as $prd_det) {
                                            $supplier_info = $dbf->fetchSingle('suppliers', '*', "sid='$prd_det[supplier_id]'");
                                            ?>
                                            <tr>
                                                <td  style="color:<?php echo $color; ?>"><?php echo $supplier_info['full_name']; ?></td>

                                                <td>
                                                    <?php
                                                    $categories = "";
                                                    $cats = explode(",", $prd_det['prd_cat_id']);
                                                    foreach ($cats as $val) {
                                                        $cat_info = $dbf->fetchSingle('categories', '*', "id='$val'");
                                                        $categories = $categories . $cat_info['category_name'] . ",";
                                                    }
                                                    ?>
                                                    <?php echo substr($categories, 0, -1); ?></td>

                                                <td>
                                                    <?php echo $dbf->cut($prd_det['product_name'], 50); ?><br>
                                                    <?php echo $prd_det['product_code']; ?><br>
                                                    <?php echo $dbf->cut($prd_det['qty_details'], 50); ?>

                                                </td>

                                                <td>&pound; <?php echo number_format($prd_det['product_price'], 2, '.', ''); ?></td>
                                                <td class="text-center">
                                                    <?php if ($prd_det['prd_status'] == 1) { ?>
                                                        <a href="manage-products?operation=act_product&prd_id=<?php echo $prd_det['product_id']; ?>&token=<?php echo $dbf->keyMaker($prd_det['product_id']); ?>" class="btn btn-danger btn-circle" title="Blocked Product"></a>

                                                    <?php } else { ?><a href="manage-products?operation=block_product&prd_id=<?php echo $prd_det['product_id']; ?>&token=<?php echo $dbf->keyMaker($prd_det['product_id']); ?>" class="btn btn-success btn-circle" title="Active Product"></a><?php } ?>


                                                    <a href="edit-product?editId=<?php echo $prd_det['product_id']; ?>&token=<?php echo $dbf->keymaker($prd_det['product_id']); ?>" class="btn btn-info btn-xs">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>

                                                    <a href="manage-products?operation=delete&product_id=<?php echo $prd_det['product_id']; ?>&token=<?php echo $dbf->keyMaker($prd_det['product_id']); ?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this suppliers ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>

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

    <?php include('common-js.php'); ?>


    <!-- DataTables JavaScript -->
    <script src="data-tables/jquery.dataTables.min.js"></script>
    <script src="data-tables/dataTables.bootstrap.min.js"></script>
    <script>
	//Set up url :: https://datatables.net/examples/advanced_init/length_menu.html
	$(document).ready(function () {
		$('#tbl_content').DataTable({
			/*No. of record display per page by default*/
			pageLength: 20,
			/*Display 1st last option*/
			//pagingType: "full_numbers",
			/*for disable search box*/
			//bFilter :false,
			responsive: true,
			/* Disable initial sort */
			"aaSorting": [],
			/*Stay in same page*/
			"stateSave": true,
			/* Disable sorting columns */
			'aoColumnDefs': [{'bSortable': false, 'aTargets': [4]}],
			
			/*Adding default pagination*/
			"language": {
			   "lengthMenu": 'Display <select>'+
				 '<option value="10">10</option>'+
				 '<option value="20">20</option>'+
				 '<option value="30">30</option>'+
				 '<option value="40">40</option>'+
				 '<option value="50">50</option>'+
				 '<option value="100">100</option>'+
				 '<option value="-1">All</option>'+
			   '</select> records'
		   }
		});
				 
	});
	$('#lbl_1').addClass('active');		 
    </script>

</body>
</html>
