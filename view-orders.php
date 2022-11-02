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

$oid=(isset($_REQUEST['oid']))?$dbf->checkSqlInjection($_REQUEST['oid']):0;
$token=$dbf->keyMaker($oid);
if($_REQUEST['token']!=$token){
  header("Location:manage-orders");exit;	
}

$order_det=$dbf->fetchSingle("master_order","*","order_id='$oid'");
$allo_det=$dbf->fetchSingle("job_allocations","*","allo_id='$order_det[allocation_id]'");

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
                  <h1 class="page-header">Order Detail</h1>
              </div>
              <?php if($_REQUEST['msg']=='002'){?>
              <div style="text-align:center;padding:0px 0px 5px 0px;color:#090;">Order Status Changed Successfully .</div> 
              <?php }?>
          </div>
          <form id="frm_orders" name="frm_orders" method="post" action="" class="form-horizontal">
           <input type="hidden" name="operation" value="update">
           <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="middle" height="35" class="text1">
                    <i><font color="#006633">Delivery Address</font></i></td>
                    <td class="text1" valign="middle">
                    <i><font color="#006633">Order Information </font></i></td>
                  </tr>
                  <tr>
                    <td width="33%" valign="top"><?php echo $order_det['delivery_address']."<br>"; ?></td>
                    <td width="36%" valign="top">
                    <span class='text1'>Order ID : </span> 
                    <b><?php echo $order_det['order_id']."<br>"; ?> </b>
                    
                    <span class='text1'>Order Acccount.No : </span> 
                    <b><?php echo $order_det['order_accno']."<br>"; ?> </b>
                    
                    <span class='text1'>Order Date : </span>
                    <b><?php  echo date('jS M, Y',strtotime($order_det["order_date"]))."<br>"; ?></b>
                    
                    <span class='text1'>Grand Total : </span> 
                    <b> &pound; <?php echo number_format($order_det['grand_total'],2); ?></b>
                   
                    </td>
                  </tr>
                    </table></td>
              </tr>
              	   <tr>
                    <td >&nbsp;</td>
                  </tr>
                  
                  <tr>
                    <td><strong>Additional Information :</strong><br>
						<?php echo $order_det['additional_info']; ?></td>
                  </tr>
                  <tr>
                    <td  class="text1" height="35" valign="middle"><i><font color="#006633">Order Details </font></i></td>
                  </tr>
                  <tr>
                    <td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  		<?php  $num=$dbf->countRows("order_items","order_id='$order_det[order_id]'");
					 if($num > 0){
				    ?>
					<tr>
                      <td align="center" valign="top" class="newproductborder">
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
					  
					  <tr>
					    <td height="30" align="left" valign="middle" class="text1">Item Photo</td>
						<td align="left" valign="middle" class="text1">Description</td>
						
						<td valign="middle" class="text1">Qty</td>
						<td align="right" valign="right" class="text1">Unit Price</td>
						<td valign="middle" class="text1" align="right" style="padding-right:20px;">Sub Total </td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
						</tr>
					  <tr>
					    <td colspan="7" align="center" height="2" bgcolor="#CCCCCC"></td>
					    </tr>
						<?php
						foreach($dbf->fetch("order_items","order_id='$order_det[order_id]'","","","") as $val) {
						  $item_details=$dbf->fetchSingle("products","product_name,product_photo,product_code","product_id='$val[product_id]'");
					    ?>
					  <tr>
					    <td align="left" height="35" style="padding-top:10px; padding-bottom:10px;">
                        <?php if($item_details['product_photo']!=''){ ?>
                        <img src="product-photos/thumb/<?php echo $item_details['product_photo'];?>" height="100" width="100"><?php } else { ?>No image available<?php } ?>
                        </td>
					    <td align="left" valign="top">
						<span class='text1'>
						<?php  
						echo "Product Name : ".$item_details['product_name']."<br>";
						echo "Product Code : ".$item_details['product_code']."<br>"; 
						echo "Note : ".$val['special_notes']."<br>"; 
						?>
                        </span>
                        </td>
					    
					    <td valign="top" style="padding-left:5px;"><?php echo $val['qty']; ?></td>
					    <td align="right" valign="top" class="text1" style="padding-left:5px;">&pound; <?php echo number_format($val['unit_price'],2); ?></td>
					    <td valign="top" align="right" class="text1" style="padding-right:20px;">&pound; <?php echo number_format($val['total_price'],2); ?>	</td>
                        
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
					  
					    </tr>
						<tr>
                            <td colspan="7" align="center" height="1" bgcolor="#CCCCCC"></td>
                        </tr>
						<?php } ?>
						<tr>
                            <td height="30">&nbsp;</td>
                            <td>&nbsp;</td>
                            
                            <td colspan="2" align="right" class="text1" valign="bottom">Grand Total = </td>
                            <td class="text1" align="right" style="padding-right:20px;" valign="bottom">&pound; <?php echo number_format($order_det['grand_total'],2); ?> </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
					   </tr>
                    </table></td>
                    </tr>
					<?php } ?> 
                  </table></td>
                  </tr>
				 
                  
                  <tr>
                    <td  class="text1" valign="middle">
					<i><font color="#006633">Chef Message</font></i> <strong>:</strong> &nbsp;
					<?php 
					echo $dbf->getDataFromTable("chefs_registration","chefs_message","chefs_id='$order_det[chef_id]'");?>
                    </td>
                  </tr>
                 
                  <tr>
                    <td>
					<a href="manage-orders" style="text-decoration:none;"><input name="submit2" type="button" class="btn btn-danger" id="submit2" value=" << BACK" style="text-decoration:none;"></a></td>
                  </tr>
                </table>
           </form>
      </div>
    </div>
    <?php  include('common-js.php'); ?>
    <script type="text/javascript" src="js/all-validation.js"></script>
	<script>$('#lbl_3').addClass('active');</script>
</body>
</html>