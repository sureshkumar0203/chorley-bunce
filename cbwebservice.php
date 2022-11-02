<?php
header('Content-type: application/json');
include_once 'includes/class.Main.php';
//Object initialization
$dbf = new Main($connection_db);

if($_SERVER['HTTP_HOST'] == "192.168.0.170" || $_SERVER['HTTP_HOST'] == "localhost"){
	$PATH = $_SERVER['DOCUMENT_ROOT'].'/chorley-bunce/';
	$HOST = 'http://'.$_SERVER['HTTP_HOST'].'/chorley-bunce/';
}else if($_SERVER['HTTP_HOST'] == "ukbestweb-live.co.uk"){
	$PATH = $_SERVER['DOCUMENT_ROOT'].'/chorley-bunce/';
	$HOST = 'http://'.$_SERVER['HTTP_HOST'].'/chorley-bunce/';
}else{
	$PATH = $_SERVER['DOCUMENT_ROOT'].'/';//for live server
	$HOST = 'https://'.$_SERVER['HTTP_HOST'].'/';
}

//echo $PATH.'<br/>'.$HOST;exit;

#####################################################################################################
######################################### CHEFS LOGIN WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=chefsLogin&data={"unique_id":"suresh","password":"suresh"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="chefsLogin" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	//for sql injection
	$unique_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->unique_id)); // Get Trainer userid	
	$pword = $dbf->checkXssSqlInjection(stripslashes($jsonData->password)); // Get password		
	$password = base64_encode(base64_encode($pword)); 
	
	$device_token=$dbf->checkXssSqlInjection($jsonData->device_token);
	$device_type=$dbf->checkXssSqlInjection($jsonData->device_type);
	
	//Store FCM for android devices.
	if($device_type=="android"){
		$fcm_id=$dbf->checkXssSqlInjection($jsonData->fcm_id);
	}else{
		$fcm_id='';
	}
	
	if($unique_id !='' && $password !=''){
		$result=$dbf->fetchSingle("chefs_registration", "*","unique_id='$unique_id' AND chefs_psw='$password'");
		if($result["chefs_id"] > 0){
			if($result['login_status']=='1'){
				$chk_device = $dbf->countRows("chef_token","chef_id='$result[chefs_id]' AND device_token='$device_token'");
				if($chk_device > 0){
					$dbf->deleteFromTable("chef_token","chef_id='$result[chefs_id]' AND device_token='$device_token'");
				}
				$token = microtime();
				if($device_type!=''){
					$dbf->insertSet("chef_token","chef_id='$result[chefs_id]',token='$token',device_token='$device_token',fcm_id='$fcm_id',device_type='$device_type',created_date=now()");
				}
				echo '{"status":"success","chefs_id":"'.$result["chefs_id"].'","user_name":"'.$result["chefs_name"].'","token":"'.$token.'"}';exit;
			}
			
			if($result['login_status']=='0'){
				echo '{"status":"failure","err_msg":"Sorry ! your registration has not accepted by administrator."}';exit;
			}
		}else{
			echo '{"status":"failure","err_msg":"Sorry ! Invalid login credentials."}';exit;
		}
	}
}

#####################################################################################################
######################################### CHEF LOGOUT WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=chefsLogout&data={"chef_id":"6","device_token":"f32588c65c6febac880d90533589433f753d4011fcc70e6ea64e76a0db513da5","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="chefsLogout" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$device_token = $dbf->checkXssSqlInjection($jsonData->device_token);
	
	$chef_id = $dbf->checkXssSqlInjection($jsonData->chef_id);
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dbf->deleteFromTable("chef_token","chef_id='$chef_id' AND token='$token'");
		echo '{"success":"true","suc_msg":"Logout Successfully."}';exit;
	}
}

#####################################################################################################
####################################### SUPPLIER LIST WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierList&data={"chef_id":"6","allocation_id":"1","token":"0.98882900 1483595853"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="supplierList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//echo "<pre>";print_r($jsonData);exit;
	
	$chef_id = $jsonData->chef_id;
	$job_allo_id = $jsonData->allocation_id;
	$token = $jsonData->token;
	
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray = array();
		$resultArray=$dbf->fetch("job_allocations_suppliers","job_allo_id='$job_allo_id' AND chef_id='$chef_id'","ja_id","","");	
		//echo "<pre>"; print_r($resultArray);exit;
			
		foreach($resultArray as $res){
			$sup_info=$dbf->fetchSingle('suppliers','*',"sid='$res[sup_id]'");

			$supplier_photo = $HOST.'supplier-photos/thumb/'.$sup_info['supplier_photo'];
			$resultArray = array("supplier_id" =>$sup_info["sid"],"supplier_photo" => $supplier_photo,"supplier_name" => $sup_info["full_name"]);
			array_push($dataArray,$resultArray);
		}
		
		if(count($resultArray) > 0){
			echo '{"status":"success","suppliers":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","suppliers":'.'[]'.'}';exit;
		}
	}
}

#####################################################################################################
####################################### CATEGORY LIST WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=categoryList&data={"chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="categoryList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$chef_id = $jsonData->chef_id;
	$token = $jsonData->token;
	
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray = array();
		$resultArray=$dbf->fetch("categories","","id","","");		
		foreach($resultArray as $res){
			$category_photo = $HOST.'category-photos/thumb/'.$res['category_photo'];
			$category_name = htmlspecialchars_decode($res["category_name"]);
			
			$resultArray = array("category_id" =>$res["id"],"category_name" => $category_name,"category_photo" => $category_photo);
			array_push($dataArray,$resultArray);
		}
		if(count($resultArray) > 0){
			echo '{"status":"success","categories":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","categories":'.'[]'.'}';exit;
		}
	}
}


#####################################################################################################
######################################## PRODUCT LIST WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierProductList&data={"supplier_id":"1"}
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierProductList&data={"supplier_id":"1","category_id":"1"}
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierProductList&data={"supplier_id":"1","chef_id":"1"}
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierProductList&data={"supplier_id":"1","category_id":"1","chef_id":"1"}
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=supplierProductList&data={"allocation_id":"1","chef_id":"6","supplier_id":"4","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="supplierProductList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	$allocation_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	$supplier_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	$category_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->category_id));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;	
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		if($supplier_id!=''){
			$condition = "supplier_id='$supplier_id' AND prd_status='0'";
		}
		if($supplier_id!='' && $category_id!=''){
			$condition="supplier_id='$supplier_id' AND prd_status='0' AND find_in_set('$category_id',prd_cat_id)";
		}
		//echo $condition;exit;
		
		//Total items in cart
		$total_items_cart=$dbf->countRows("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'");
		
		$dataArray = array();
		$resultArray=$dbf->fetch("products",$condition,"product_id","","");		
		foreach($resultArray as $res){
			$product_price = number_format($res["product_price"],2,'.','');
			//$mea_det=$dbf->fetchSingle("measurment_units", "*","id='$res[prd_unit_id]'");
			//check the item is placed in cart or not
			$numprd_cart=$dbf->countRows("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND product_id='$res[product_id]'");
			$itmcart_status=($numprd_cart>0)?'yes':'no';
			
			$numprd_cart_spe=$dbf->countRows("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND product_id='$res[product_id]' AND special_notes!=''");
			$note_status=($numprd_cart_spe>0)?'yes':'no';
			
			$resultArray = array("allocation_id" => $allocation_id,"product_id" =>$res["product_id"],"category_id" => $res["prd_cat_id"],"product_name" =>$res["product_name"],"qty_dtls" =>$res["qty_details"],"product_price" => $product_price,"cart_status"=>$itmcart_status,"note_status"=>$note_status);
			array_push($dataArray,$resultArray);
		}
		if(count($resultArray) > 0){
			echo '{"status":"success","total_items":'.$total_items_cart.',"productlist":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","productlist":'.'[]'.'}';exit;
		}
	}
}

#####################################################################################################
###################################### PRODUCT DETAILS WEBSERVICE ###################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=productDetails&data={"product_id":"1","chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="productDetails" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	$product_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->product_id));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$result=$dbf->fetchSingle("products","*","product_id='$product_id'");
		$prd_unit_price = number_format($result["product_price"],2,'.','');
		if(count($result) > 0){
			//$mea_det=$dbf->fetchSingle("measurment_units", "*","id='$result[prd_unit_id]'");
			//$prd_unit_name = $mea_det['unit_name'];
			
			//product photo path
			$prd_ph_path = $HOST.'product-photos/thumb/'.$result['product_photo'];
			
			$dataArray = array("product_id" =>$result["product_id"],"supplier_id" =>$result["supplier_id"],"category_id" => $result["prd_cat_id"],"product_name" =>$result["product_name"],"product_code" =>$result["product_code"],"qty_dtls" =>$result["qty_details"],"prd_ph_path" =>$prd_ph_path,"prd_unit_name" =>$result["prd_unit_name"],"product_price" => $prd_unit_price,"product_details" =>$result["product_details"]);
			
			echo '{"status":"success","productdetails":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","productdetails":'.'[]'.'}';exit;
		}
	}
}
#####################################################################################################
###################################### ADD TO CART WEBSERVICE #######################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=addToOrder&data={"allocation_id":"1","chef_id":"6","product_id":"1","supplier_id":"1","qty":"1","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="addToOrder" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	$product_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->product_id));
	$supplier_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	$qty = $dbf->checkXssSqlInjection(stripslashes($jsonData->qty));
	$special_notes = $dbf->checkXssSqlInjection(stripslashes($jsonData->special_notes));
	$allocation_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	
	$chef_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		//Product Detail Information
		$prd_info=$dbf->fetchSingle("products","*","product_id='$product_id'");
		$prd_unit_price = $prd_info['product_price'];
		//Get Total Price
		$total_price=$prd_unit_price*$qty;
		
		//Cart Detail Information
		$cart_info=$dbf->fetchSingle("cart_temp","*","allocation_id='$allocation_id' AND chef_id='$chef_id' AND product_id='$product_id' AND supplier_id='$supplier_id'");
		//Get Total Price
		$cart_total=number_format($dbf->checkXssSqlInjection($total_price+$cart_info['total_price']),2,'.','');
		
		$cart_qty= $dbf->checkXssSqlInjection($cart_info['qty']);
		//Get total Quantity
		$total_qty= $dbf->checkXssSqlInjection($qty+$cart_qty);
		
		$cart_num=$dbf->countRows("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND product_id='$product_id' AND supplier_id='$supplier_id'");
		if($cart_num==0){
		  // insert data in the cart table
		  $string="allocation_id='$allocation_id',chef_id='$chef_id',product_id='$product_id',supplier_id='$supplier_id',unit_price='$prd_unit_price',qty='$qty',total_price='$cart_total',special_notes='$special_notes',dated=now()";
		  $cartid=$dbf->insertSet("cart_temp",$string);
		}else{
		  // update product total quantity & total price in cart table
		  $string="qty='$total_qty',total_price='$cart_total',special_notes='$special_notes'";
		  $dbf->updateTable("cart_temp",$string,"chef_id='$chef_id' AND product_id='$product_id' AND supplier_id='$supplier_id'");
		  $cartid=$dbf->getDataFromTable("cart_temp","cart_id","chef_id='$chef_id' AND product_id='$product_id' AND supplier_id='$supplier_id'");
		}
		echo '{"status":"success","cart_id":'.'"'.$cartid.'"'.'}';exit;
	}
}


#####################################################################################################
####################################### CART ITEM LIST WEBSERVICE ###################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=cartItemList&data={"allocation_id":"1","chef_id":"6","supplier_id":"1","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="cartItemList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	$allocation_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	$supplier_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	
	$chef_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$suplier_accno=$dbf->getDataFromTable("job_allocations_suppliers","sup_ac_no","job_allo_id='$allocation_id' AND sup_id='$supplier_id'");
		//$array_suppl=array();
		$dataArray = array();
		$resultArray=$dbf->fetch("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'","cart_id","","");		
		foreach($resultArray as $res){
			$total_price = number_format($res["total_price"],2,'.','');
			$unit_price = number_format($res["unit_price"],2,'.','');
			
			$prd_info=$dbf->fetchSingle("products","*","product_id='$res[product_id]'");
			
			$resultArray = array("cart_id" =>$res["cart_id"],"product_id" =>$prd_info['product_id'],"product_name" =>$prd_info['product_name'],"qty_details" =>$prd_info['qty_details'],"unit_price" =>$unit_price,"qty" => $res["qty"],"total_price" => $total_price,"special_notes" => $res["special_notes"]);
			array_push($dataArray,$resultArray);
			//array_push($array_suppl,$res['supplier_id']);
		}
		if(count($resultArray) > 0){
			echo '{"status":"success","supplier_accno":'.'"'.$suplier_accno.'"'.',"cartItemList":'.json_encode($dataArray).'}';exit;
		 }else{
			echo '{"status":"success","cartItemList":'.'[]'.'}';exit;
		 }
	}
}
#####################################################################################################
############################### DELETE ITEM FROM THE CART WEBSERVICE ################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=deleteItemFromCart&data={"cart_id":"1","chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="deleteItemFromCart" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	$chef_id = $jsonData->chef_id;
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$cart_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->cart_id));
		$dbf->deleteFromTable("cart_temp","cart_id='$cart_id'");
		echo '{"status":"success"}';exit;
	}
}


#####################################################################################################
#################################### UPDATE CART ITEM WEBSERVICE ####################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=updateCartItem&data={"cart_id":"1","qty":"2","chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="updateCartItem" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	$cart_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->cart_id));
	$qty = $dbf->checkXssSqlInjection(stripslashes($jsonData->qty));
	
	$chef_id = $jsonData->chef_id;
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$cart_info=$dbf->fetchSingle("cart_temp","*","cart_id='$cart_id'");
		$unit_price = $cart_info['unit_price'];
		
		$total_price=$unit_price*$qty;
		$string="qty='$qty',total_price='$total_price'";
		$dbf->updateTable("cart_temp",$string,"cart_id='$cart_id'");
		echo '{"status":"success"}';exit;
	}
}


#####################################################################################################
#################################### UPDATE ITEM NOTES WEBSERVICE ###################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=updateItemNotes&data={"cart_id":"21","item_notes":"Send me always best product","chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="updateItemNotes" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print "<pre>";print_r($jsonData);exit;
	
	$cart_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->cart_id));
	$item_notes = $dbf->checkXssSqlInjection(stripslashes($jsonData->item_notes));
	
	$chef_id = $jsonData->chef_id;
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$cart_count=$dbf->countRows("cart_temp","cart_id='$cart_id'");
		if($cart_count!=0){
			$string="special_notes='$item_notes'";
			$dbf->updateTable("cart_temp",$string,"cart_id='$cart_id'");
			echo '{"status":"success"}';exit;
		}else{
			echo '{"status":"failure","err_msg":"Item not found"}';exit;
		}
	}
}

#####################################################################################################
#################################### PLACE ORDER WEBSERVICE #########################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=placeOrderItem&data={"allocation_id":"1","chef_id":"4","supplier_id":"3","job_title":"FoodMaker","delivery_addr":"bhubneswar","delivery_datetime":"5th dec 2016 08:00 AM","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="placeOrderItem"){
	$jsonData=json_decode($_REQUEST['data']);
	//print'<pre>';print_r($jsonData);exit;
	
	$supplier_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	$job_title=$dbf->checkXssSqlInjection(stripslashes($jsonData->job_title));
	$delivery_address=$dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_addr));
	$delivery_datetime=$dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_datetime));
	$additional_info = nl2br($jsonData->additional_info);
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$allocation_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
		
		$suplier_accno=$dbf->getDataFromTable("job_allocations_suppliers","sup_ac_no","job_allo_id='$allocation_id' AND sup_id='$supplier_id'");
		
		$max_orderid=$dbf->fetchSingle("master_order","max(order_id) as oid","");
		$max_orderid=($max_orderid['oid']==0)?'1':$max_orderid['oid']+1;
		//$order_accno=time().$chef_id.$supplier_id.$max_orderid;
		$order_accno = mt_rand(100, 999).mt_rand(100, 999);
		
		
		$grand_total_temp=$dbf->getDataFromTable("cart_temp","sum(total_price)","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'");
		$grand_total = number_format($grand_total_temp,2,'.','');
		//echo $grand_total;exit;
		
		$chef_details=$dbf->fetchSingle("chefs_registration","*","chefs_id='$chef_id'");
		//print'<pre>';print_r($chef_details);exit;
		$string="allocation_id='$allocation_id',chef_id='$chef_details[chefs_id]',supplier_id='$supplier_id',order_accno='$order_accno',chefs_name='$chef_details[chefs_name]',chefs_email='$chef_details[chefs_email]',chefs_contact_number='$chef_details[chefs_contact_number]',job_title='$job_title',delivery_address='$delivery_address',supplier_acno='$suplier_accno',delivery_notes='',grand_total='$grand_total',order_status='notdelevrd',delivery_datetime='$delivery_datetime',additional_info='$additional_info',order_date=NOW()";
		$order_id=$dbf->insertSet("master_order",$string);
		
		foreach($dbf->fetch("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'")as $cart_res){	 
		   $chef_id=$cart_res['chef_id'];
		   $product_id=$cart_res['product_id'];
		   $supplier_id= $cart_res['supplier_id'];
		   $unit_price =$cart_res['unit_price'];
		   $qty =$cart_res['qty'];
		   $total_price =$cart_res['total_price'];
		   $special_notes =$cart_res['special_notes'];
		   
		   $cart_string="allocation_id='$allocation_id',order_id='$order_id',chef_id='$chef_id',product_id='$product_id',supplier_id='$supplier_id',unit_price='$unit_price',qty='$qty',total_price='$total_price',special_notes='$special_notes'";
		   $dbf->insertSet("order_items",$cart_string);
		}
		
		$ydate=date("Y");
		$from=$chef_details['chefs_email'];
		//Get Admin Information
		$admin_info=$dbf->fetchSingle("core","admin_name,alt_email,contact_no","id='1'");
		//Get ORDER Information
		$res_order=$dbf->fetchSingle("master_order","*","order_id='$order_id'");// print'<pre>'; print_r($res_order);exit;
		
		$grandTotalTemp=$dbf->getDataFromTable("order_items","sum(total_price)","order_id='$order_id' AND allocation_id='$allocation_id' AND supplier_id='$supplier_id' AND chef_id='$chef_id'");
		$grandTotal = number_format($grandTotalTemp,2,'.','');
		//Get Allocation A/C Information
		$allo_det=$dbf->fetchSingle("job_allocations","*","allo_id='$allocation_id'");
		//echo "<pre>";print_r($allo_det);exit;
		
		$body='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px grey">
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;background-color:#000;">
		<td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="15">&nbsp;</td>
		<td width="1306" height="35" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">
		<img src="'.$HOST.'/images/cb_logo.png" style=""/></td>
		<td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="10">&nbsp;</td>
		</tr>
		
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<tr>
		<td width="51%" height="30" align="48%" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Chef Information</td>
		<td width="3%">&nbsp;</td>
		<td colspan="2" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Order Information </td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Name : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
		'.$res_order['chefs_name'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Date : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M,Y",strtotime($res_order['order_date'])).'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Email : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_email'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Date & Time : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M, Y g:i A",strtotime($delivery_datetime)).'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Contact Number : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_contact_number'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Account No. : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['order_accno'].'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Address  : : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['delivery_address'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Supplier Account No. : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['supplier_acno'].'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Message  :  <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$chef_details['chefs_message'].'</span></td>
		<td>&nbsp;</td>
		<td><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Grand Total : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.number_format($grandTotal,2,'.','').'</span></span></td>
		</tr>
		
		</table>
		</td>
		<td>&nbsp;</td>
		</tr>
		
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="30">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid #CCC;">
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		  <td height="30"></td>
		  <td>&nbsp;</td>
		  </tr>
          
          <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
              <td height="30" colspan="2">
              <span style="color:#000;">Additional Information</span><br><br>
              <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['additional_info'].'</span>
              </td>
			</tr>
            
            
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		<td height="30"></td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		<td width="51%" height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;padding-left:20px;">Item Details</td>
		<td width="49%">&nbsp;</td>
		</tr>';
		   foreach($dbf->fetch("order_items","order_id='$res_order[order_id]' AND chef_id='$chef_id' AND  supplier_id='$supplier_id'")as $res_item){
			  $item_details=$dbf->fetchSingle("products","product_name,product_photo,product_code","product_id='$res_item[product_id]'");
			  $path= $HOST."product-photos/thumb/".$item_details['product_photo'];
			  
			  $body .='<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
			  <td valign="top" style="border-bottom:1px solid #666;padding: 15px 0;"><span style="width:192px; border:1px solid #CCC; padding:2px; background-color:#FFF; float:left; margin-left:10px; border-radius:8px; text-align:center; padding:10px;"><img style="width:100%; height:auto; Display:block;" src='.$path.'></span></td>
			  <td  valign="top" style="padding-left:20px;border-bottom:1px solid #666;padding: 15px 0;">
			  Item Name : &nbsp; '.$item_details['product_name'].' <br>
              Product Code : &nbsp; '.$item_details['product_code'].' <br>
			  Price : &nbsp; &pound; '.number_format($res_item['unit_price'],2,'.','').' <br>
			  QTY : &nbsp; '.$res_item['qty'].' <br>
			  Total Price : &nbsp; &pound; '.number_format($res_item['total_price'],2,'.','').'<br>
			  <b style="color:#F00;">Notes :</b> &nbsp; <span style="color:#F00;"> '.$res_item['special_notes'].' </span><br><br>
			  </td>
		  </tr>
			  <tr>
			  <td colspan="2" align="right" style="padding-right:30px;"></td>
		  </tr>';
		  }
		  
		  $body .='<tr>
		  <td height="1" colspan="2" align="right">
		  <table width="50%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		  <td bgcolor="#999999"></td>
		  </tr>
		  </table>
		  </td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td height="30">&nbsp;</td>
		  <td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;"></td>
		  </tr>
		  </table>
		  </td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="30">&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="26">Thank You,</td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="23">'.$chef_details['chefs_name'].'</td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="20">Contact Number : '.$chef_details['chefs_contact_number'].'</td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="20">Email : '.$chef_details['chefs_email'].'</td>
		  <td>&nbsp;</td>
		  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="20">&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		  
		  <tr>
		  <td>&nbsp;</td>
		  <td align="center" valign="middle">&copy; Copyright Chorley bunce'.$ydate.'. All Rights Reserved</td>
		  <td>&nbsp;</td>
		  </tr>
		  </table>';
		//echo $body;exit;
		
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From:".$from."\n";
		$headers .= "Cc:".CC_MAIL. "\n";
		
		$subject ="A New Order Placed from ".$chef_details['chefs_name'];
		
		$to_admin=$admin_info["alt_email"];
		$to_supplier = $dbf->getDataFromTable("suppliers","email","sid='$supplier_id'");
		
		@mail($to_admin,$subject,$body,$headers);
		@mail($to_supplier,$subject,$body,$headers);
		
		$dbf->deleteFromTable("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'");
		echo '{"status":"success"}';exit;
	}
}
#####################################################################################################
#################################### VIEW ORDER WEBSERVICE ##########################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=viewOrderList&data={"allocation_id":"1","chef_id":"1","supplier_id":"1","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="viewOrderList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	//print'<pre>';print_r($jsonData);exit;
	
	$allocation_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	$supplier_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray=array();
		$resultArray=$dbf->fetch("cart_temp","allocation_id='$allocation_id' AND chef_id='$chef_id' AND supplier_id='$supplier_id'");
		foreach($resultArray as $val){
		   $product_detail=$dbf->fetchSingle("products","product_name,product_photo","product_id='$val[product_id]'"); 	
		   $product_image=$HOST."product-photos/thumb/".$product_detail['product_photo'];
		   $suplier_name=$dbf->fetchSingle("suppliers","full_name","sid='$supplier_id'"); 	
		   $resultArray=array("cart_id"=>$val['cart_id'],"product_name"=>$product_detail['product_name'],"product_image"=>$product_image,"supplier_name"=>$suplier_name['full_name'],"unit_price"=>$val['unit_price'],"qty"=>$val['qty'],"total_price"=>$val['total_price'],"special_notes"=>$val['special_notes']);
		   array_push($dataArray,$resultArray);
		}
		if(count($dataArray)>0){
		  echo  '{"status":"success","viewOrderList":'.json_encode($dataArray).'}';exit;
		}else{
		  echo '{"status":"success","viewOrderList":'.'[]'.'}';exit;
		}
	}
}



#####################################################################################################
#################################### PAST ORDER LIST WEBSERVICE ##################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=pastOrderList&data={"chef_id":"6","token":"0.74680800 1485865557"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="pastOrderList" && $_SERVER['REQUEST_METHOD']=='GET'){
    $jsonData=json_decode($_REQUEST['data']);
    //echo "<pre>";print_r($jsonData);exit;
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray=array();
		$resultArray=$dbf->fetch("master_order","chef_id='$chef_id' AND order_status='notdelevrd'","order_id","","DESC");
		foreach($resultArray as $res){
		   $supplier_details=$dbf->fetchSingle("suppliers","full_name,supplier_photo,sid","sid='$res[supplier_id]'");
		   	   
		   $total_orderqty=$dbf->countRows("order_items","chef_id='$chef_id' AND order_id='$res[order_id]'");
		   $total_orderqty= (string)$total_orderqty;
		   
		   $supplier_image=$HOST."supplier-photos/thumb/".$supplier_details['supplier_photo'];
		   $supplier_id= $supplier_details['sid'];
		   
		   $resultArray=array("order_accno"=>$res['order_accno'],"order_id"=>$res['order_id'],"supplier_name"=>$supplier_details['full_name'],"supplier_image"=>$supplier_image,"supplier_id"=>$supplier_id,"total_quantity"=>$total_orderqty,"total_price"=>$res['grand_total']);
		   array_push($dataArray,$resultArray);
		}
		if(count($dataArray)>0){
			echo '{"status":"success","pastOrderList":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","pastOrderList":'.'[]'.'}';exit;
	   }
	}
}



#############################################################################################################
#################################### ONGOING ORDER DETAILS WEBSERVICE #######################################
#############################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=ongoingOrderDetails&data={"chef_id":"6","order_id":"1","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ongoingOrderDetails" && $_SERVER['REQUEST_METHOD']=='GET'){
    $jsonData=json_decode($_REQUEST['data']);
	$order_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_id));    
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray=array();  
		$supplier_accno=$dbf->fetchSingle("master_order m,suppliers s","s.supp_acc_number","m.order_id='$order_id' AND m.chef_id='$chef_id' AND m.supplier_id=s.sid AND m.order_status='notdelevrd'");
		
		$resultArray=$dbf->fetch("order_items","order_id='$order_id' AND chef_id='$chef_id'");
		foreach($resultArray as $val){
			$total_price = number_format($val["total_price"],2,'.','');
			$unit_price = number_format($val["unit_price"],2,'.','');
			
			$prd_details=$dbf->fetchSingle("products","*","product_id='$val[product_id]'");
			$prd_image= $HOST."product-photos/thumb/".$prd_details['product_photo'];
			
			$resultArray = array("order_item_id"=>$val['order_item_id'],"product_id" =>$prd_details['product_id'],"product_name" =>$prd_details['product_name'],"product_image"=>$prd_image,"qty_details" =>$prd_details['qty_details'],"unit_price" =>$unit_price,"qty" => $val["qty"],"total_price" => $total_price,"special_notes" => $val["special_notes"]);
			array_push($dataArray,$resultArray);
		}
		if(count($dataArray)>0){
		  echo '{"status":"success","supplier_accno":'.'"'.$supplier_accno['supp_acc_number'].'"'.',"ongoingOrderDetails":'.json_encode($dataArray).'}'; exit;	
		}else{
		  echo '{"status":"success","ongoingOrderDetails":'.'[]'.'}'; exit;	
		}
	}
}


#############################################################################################################
#################################### ONGOING ORDER PLACE ####################################################
#############################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=ongoingOrderPlace&data={"allocation_id":"1","chef_id":"6","order_id":"1","order_item_id":"5,6,7","job_title":"DAHI-BARAA","delivery_addr":"Cuttack,Ranihat","delivery_datetime":"5th dec 2015 09:15 AM","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="ongoingOrderPlace" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$allocation_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	$order_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_id));
	$order_item_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_item_id));
	$job_title=$dbf->checkXssSqlInjection(stripslashes($jsonData->job_title));
	$delivery_addr=$dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_addr));
	$delivery_datetime = $dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_datetime));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$grand_total_temp=$dbf->getDataFromTable("order_items","sum(total_price)","order_item_id IN($order_item_id)");
		$grand_total = number_format($grand_total_temp,2,'.','');
		
		$supplier_id = $dbf->getDataFromTable("master_order","supplier_id","order_id='$order_id'");
		$max_orderid=$dbf->fetchSingle("master_order","max(order_id) as oid","");
		$max_orderid=($max_orderid['oid']==0)?'1':$max_orderid['oid']+1;
		//echo $max_orderid['oid'];exit;
		//$order_accno=time().$chef_id.$supplier_id.$max_orderid;
		$order_accno = mt_rand(100, 999).mt_rand(100, 999);
		
		$order_details=$dbf->fetchSingle("master_order","*","order_id='$order_id'");
		$stringor="allocation_id='$allocation_id',chef_id='$order_details[chef_id]',supplier_id='$order_details[supplier_id]',order_accno='$order_accno',chefs_name='$order_details[chefs_name]',chefs_email='$order_details[chefs_email]',chefs_contact_number='$order_details[chefs_contact_number]',job_title='$job_title',delivery_address='$delivery_addr',supplier_acno='$order_details[supplier_acno]',delivery_notes='$order_details[delivery_notes]',grand_total='$grand_total',order_status='notdelevrd',delivery_datetime='$delivery_datetime',order_date=NOW()";
		$ongoing_order_id=$dbf->insertSet("master_order",$stringor);
		$orderItemArray=explode(",",$order_item_id); // print'<pre>';print_r($orderItemArray);exit;
		foreach($orderItemArray as $val){
			$order_item_details=$dbf->fetchSingle("order_items","*","order_item_id='$val'");
			$stringoi="order_id='$ongoing_order_id',allocation_id='$allocation_id',chef_id='$order_item_details[chef_id]',product_id='$order_item_details[product_id]',supplier_id='$order_item_details[supplier_id]',unit_price='$order_item_details[unit_price]',qty='$order_item_details[qty]',total_price='$order_item_details[total_price]',special_notes='$order_item_details[special_notes]'";
			$dbf->insertSet("order_items",$stringoi);	  
		}
		$ydate=date("Y");
		//Get Admin Information
		$admin_info=$dbf->fetchSingle("core","admin_name,alt_email,contact_no","id='1'");
		//Get ORDER Information
		$res_order=$dbf->fetchSingle("master_order","*","order_id='$ongoing_order_id'");
		$from=$res_order['chefs_email'];
		
		//Get Allocation A/C Information
		$allo_det=$dbf->fetchSingle("job_allocations","*","allo_id='$allocation_id'");
		 
		$body='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px grey">
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;background-color:#000;">
		<td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="15">&nbsp;</td>
		<td width="1306" height="35" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;background-color:#000;">
		<img src="'.$HOST.'/images/cb_logo.png" style=""/></td>
		<td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="10">&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="51%" height="30" align="48%" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Chef Information</td>
		<td width="3%">&nbsp;</td>
		<td colspan="2" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Order Information </td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Name : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
		'.$res_order['chefs_name'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Date : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M, Y",strtotime($res_order['order_date'])).'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Email : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_email'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Date & Time : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M, Y g:i A",strtotime($delivery_datetime)).'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Contact Number : 
		<span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_contact_number'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Account No. : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['order_accno'].'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Message : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_message'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Allocation Account No : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$allo_det['allocation_ac_no'].'</span></span></td>
		</tr>
		<tr>
		<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Address  : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['delivery_address'].'</span></td>
		<td>&nbsp;</td>
		<td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Grand Total : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.number_format($grand_total,2,'.','').'</span></span></td>
		</tr>
		</table>
		</td>
		<td>&nbsp;</td>
		</tr>
		
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="30">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid #CCC;">
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		<td height="30"></td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		<td width="51%" height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;padding-left:20px;">Item Details</td>
		<td width="49%">&nbsp;</td>
		</tr>';
		 foreach($dbf->fetch("order_items","order_id='$res_order[order_id]' AND chef_id='$chef_id'")as $res_item){
			$item_details=$dbf->fetchSingle("products","product_name,product_photo","product_id='$res_item[product_id]'");
			$path= $HOST."product-photos/thumb/".$item_details['product_photo'];
			
			$body .='<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
			<td valign="top" style="border-bottom:1px solid #666;padding: 15px 0;"><span style="width:192px; border:1px solid #CCC; padding:2px; background-color:#FFF; float:left; margin-left:10px; border-radius:8px; text-align:center; padding:10px;"><img style="width:100%; height:auto; Display:block;" src='.$path.'></span></td>
			<td  valign="top" style="padding-left:20px;border-bottom:1px solid #666;padding: 15px 0;">
			Item Name : &nbsp; '.$item_details['product_name'].' <br>
			Price : &nbsp; &pound; '.number_format($res_item['unit_price'],2,'.','').' <br>
			QTY : &nbsp; '.$res_item['qty'].' <br>
			Total Price : &nbsp; &pound; '.number_format($res_item['total_price'],2,'.','').'<br><br>
			</td>
		</tr>
			<tr>
			<td colspan="2" align="right" style="padding-right:30px;"></td>
		</tr>';
		}
		
		$body .='<tr>
		<td height="1" colspan="2" align="right">
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td bgcolor="#999999"></td>
		</tr>
		</table>
		</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td height="30">&nbsp;</td>
		<td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;"></td>
		</tr>
		</table>
		</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="30">&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="26">Thank You,</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="23">'.$res_order['chefs_name'].'</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">Contact Number : '.$res_order['chefs_contact_number'].'</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">Email : '.$res_order['chefs_email'].'</td>
		<td>&nbsp;</td>
		</tr>
		<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		<td>&nbsp;</td>
		<td height="20">&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		
		<tr>
		<td>&nbsp;</td>
		<td align="center" valign="middle">&copy; Copyright Chorley bunce'.$ydate.'. All Rights Reserved</td>
		<td>&nbsp;</td>
		</tr>
		</table>';
		  
		//echo $body;exit; 
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From:".$from."\n";
		$headers .= "Cc:".CC_MAIL. "\n";
		
		$subject ="A New Order Placed from ".$res_order['chefs_name'];
		
		$to_admin=$admin_info["alt_email"];
		$to_supplier = $dbf->getDataFromTable("suppliers","email","sid='$res_order[supplier_id]'");
		//echo $to_supplier;exit;
		//echo $from.'------'.$subject.'------'.$to_admin.'-------'.$to_supplier.'---------'.$body;exit;
		@mail($to_admin,$subject,$body,$headers);
		@mail($to_supplier,$subject,$body,$headers);
		
		echo '{"status":"success"}';exit;
	}
}
 

#############################################################################################################
#################################### PAST ORDER DETAILS WEB-SERVICE #########################################
#############################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=pastOrderDetails&data={"chef_id":"6","order_id":"2","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=='pastOrderDetails' && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$order_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_id));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		//TO Get Supplier Account Number,Job Title and Delivery address
		$other_details=$dbf->fetchSingle("master_order m,suppliers s","m.job_title,m.delivery_address,m.supplier_acno","m.order_id='$order_id' AND m.chef_id='$chef_id' AND m.supplier_id=s.sid");
		//To Get  Total Amount of the order
		$grand_total_temp=$dbf->getDataFromTable("order_items","sum(total_price)","chef_id='$chef_id' AND order_id='$order_id'");
		$grand_total = number_format($grand_total_temp,2,'.','');
		
		$dataArray=array();
		$resultArray=$dbf->fetch("order_items","chef_id='$chef_id' AND order_id='$order_id'");
		foreach($resultArray as $val){
			$total_price = number_format($val["total_price"],2,'.','');
			$unit_price = number_format($val["unit_price"],2,'.','');
			
			$prd_details=$dbf->fetchSingle("products","*","product_id='$val[product_id]'");
			$prd_image= $HOST."product-photos/thumb/".$prd_details['product_photo'];
			
			$resultArray = array("order_item_id"=>$val['order_item_id'],"product_id" =>$prd_details['product_id'],"product_name" =>$prd_details['product_name'],"product_image"=>$prd_image,"qty_details" =>$prd_details['qty_details'],"unit_price" =>$unit_price,"qty" => $val["qty"],"total_price" => $total_price,"special_notes" => $val["special_notes"]);
			array_push($dataArray,$resultArray);
		}
		if(count($dataArray)>0){
		  echo '{"status":"success","job_title":'.'"'.$other_details['job_title'].'"'.',"delivery_address":'.'"'.$other_details['delivery_address'].'"'.',"supplier_accno":'.'"'.$other_details['supplier_acno'].'"'.',"grand_total":'.'"'.$grand_total.'"'.',"pastOrderDetails":'.json_encode($dataArray).'}'; exit;	
		}else{
		  echo '{"status":"success","pastOrderDetails":'.'[]'.'}'; exit;	
		}
	}
}


#############################################################################################################
#################### Duplicate ORDER Place From Past Order in WEB-SERVICE ###################################
#############################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=duplicateOrderPlace&data={"allocation_id":"1","chef_id":"6","order_id":"6","order_item_id":"17,18","job_title":"Red Star","delivery_addr":"Delhi","delivery_datetime":"5th dec 2016 08:00 AM","token":"0.74680800 1485865557"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=='duplicateOrderPlace'){
	$jsonData=json_decode($_REQUEST['data']);
	//print'<pre>';print_r($jsonData);exit;
	
	$allocation_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->allocation_id));
	$order_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_id));
	$order_item_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->order_item_id));
	$job_title=$dbf->checkXssSqlInjection(stripslashes($jsonData->job_title));
	$delivery_addr=$dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_addr));
	$delivery_datetime = $dbf->checkXssSqlInjection(stripslashes($jsonData->delivery_datetime));
	$additional_info = nl2br($jsonData->additional_info);
	
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$chef_details=$dbf->fetchSingle("chefs_registration","*","chefs_id='$chef_id'");
	
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$grand_total_temp=$dbf->getDataFromTable("order_items","sum(total_price)","order_item_id IN($order_item_id)");
		$grand_total = number_format($grand_total_temp,2,'.','');
		
		$supplier_id = $dbf->getDataFromTable("master_order","supplier_id","order_id='$order_id'");
		$max_orderid=$dbf->fetchSingle("master_order","max(order_id) as oid","");
		$max_orderid=($max_orderid['oid']==0)?'1':$max_orderid['oid']+1;
		//echo $max_orderid['oid'];exit;
		//$order_accno=time().$chef_id.$supplier_id.$max_orderid;
		$order_accno = mt_rand(100, 999).mt_rand(100, 999);
		
		$order_details=$dbf->fetchSingle("master_order","*","order_id='$order_id'");
		$stringor="allocation_id='$allocation_id',chef_id='$order_details[chef_id]',supplier_id='$order_details[supplier_id]',order_accno='$order_accno',chefs_name='$order_details[chefs_name]',chefs_email='$order_details[chefs_email]',chefs_contact_number='$order_details[chefs_contact_number]',job_title='$job_title',delivery_address='$delivery_addr',supplier_acno='$order_details[supplier_acno]',delivery_notes='$order_details[delivery_notes]',grand_total='$grand_total',order_status='notdelevrd',delivery_datetime='$delivery_datetime',additional_info='$additional_info',order_date=NOW()";
		$past_order_id=$dbf->insertSet("master_order",$stringor);
		
		$orderItemArray=explode(",",$order_item_id); // print'<pre>';print_r($orderItemArray);exit;
		foreach($orderItemArray as $val){
		  $order_item_details=$dbf->fetchSingle("order_items","*","order_item_id='$val'");
		  if($past_order_id!='' && $order_item_details['chef_id']!='' && $order_item_details['supplier_id']!=''){
			$stringoi="allocation_id='$allocation_id',order_id='$past_order_id',chef_id='$order_item_details[chef_id]',product_id='$order_item_details[product_id]',supplier_id='$order_item_details[supplier_id]',unit_price='$order_item_details[unit_price]',qty='$order_item_details[qty]',total_price='$order_item_details[total_price]',special_notes='$order_item_details[special_notes]'";
			$dbf->insertSet("order_items",$stringoi);
		  }
		}
		$ydate=date("Y");
		//Get Admin Information
		$admin_info=$dbf->fetchSingle("core","admin_name,alt_email,contact_no","id='1'");
		//Get ORDER Information
		$res_order=$dbf->fetchSingle("master_order","*","order_id='$past_order_id'");
		$from=$res_order['chefs_email'];
		// print'<pre>'; print_r($res_order);exit;
		
		//Get Allocation A/C Information
		$allo_det=$dbf->fetchSingle("job_allocations","*","allo_id='$allocation_id'");
		 
		$body='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px grey">
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;background-color:#000;">
	  <td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="15">&nbsp;</td>
	  <td width="1306" height="35" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">
	  <img src="'.$HOST.'/images/cb_logo.png" style=""/></td>
	  <td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="10">&nbsp;</td>
	  </tr>
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
	  <td>&nbsp;</td>
	  <td height="20">&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
	  <td>&nbsp;</td>
	  <td height="20">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
		  <td width="51%" height="30" align="48%" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Chef Information</td>
		  <td width="3%">&nbsp;</td>
		  <td colspan="2" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">Order Information </td>
		  </tr>
		  <tr>
		  <td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Name : 
		  <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
		  '.$res_order['chefs_name'].'</span></td>
		  <td>&nbsp;</td>
		  <td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Date : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M, Y",strtotime($res_order['order_date'])).'</span></span></td>
		  </tr>
		  <tr>
		  <td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Email : 
		  <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_email'].'</span></td>
		  <td>&nbsp;</td>
		  <td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Date & Time : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.date("jS M, Y g:i A",strtotime($delivery_datetime)).'</span></span></td>
		  </tr>
		  <tr>
		  <td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Contact Number : 
		  <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['chefs_contact_number'].'</span></td>
		  <td>&nbsp;</td>
		  <td colspan="2"><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Order Account No. : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['order_accno'].'</span></span></td>
		  </tr>
		  <tr>
			<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Delivery Address : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['delivery_address'].'</span></td>
			<td>&nbsp;</td>
			<td><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Supplier Account No. : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['supplier_acno'].'</span></span></td>
		  </tr>
		  <tr>
			<td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Chef Message  :  <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$chef_details['chefs_message'].'</span></td>
			<td>&nbsp;</td>
			<td><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">Grand Total : <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.number_format($grand_total,2,'.','').'</span></span></td>
		  </tr>
		  </table>
		  </td>
		  <td>&nbsp;</td>
		  </tr>
		  
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
		  <td>&nbsp;</td>
		  <td height="30">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid #CCC;">
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		    <td height="30"></td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
              <td height="30" colspan="2">
              Additional Information<br><br>
              <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">'.$res_order['additional_info'].'</span>
              </td>
			</tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		    <td height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;padding-left:20px;">&nbsp;</td>
		    <td>&nbsp;</td>
		    </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color:#4E4E4E; font-weight: bold;" bgcolor="#FFFFFF">
		  <td width="51%" height="30" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;padding-left:20px;">Item Details</td>
		  <td width="49%">&nbsp;</td>
		  </tr>';
			 foreach($dbf->fetch("order_items","order_id='$res_order[order_id]' AND chef_id='$chef_id'")as $res_item){
				$item_details=$dbf->fetchSingle("products","product_name,product_photo,product_code","product_id='$res_item[product_id]'");
				$path= $HOST."product-photos/thumb/".$item_details['product_photo'];
				
				$body .='<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;">
				<td valign="top" style="border-bottom:1px solid #666;padding: 15px 0;"><span style="width:192px; border:1px solid #CCC; padding:2px; background-color:#FFF; float:left; margin-left:10px; border-radius:8px; text-align:center; padding:10px;"><img style="width:100%; height:auto; Display:block;" src='.$path.'></span></td>
				<td  valign="top" style="padding-left:20px;border-bottom:1px solid #666;padding: 15px 0;">
				Item Name : &nbsp; '.$item_details['product_name'].' <br>
                Product Code : &nbsp; '.$item_details['product_code'].' <br>
				Price : &nbsp; &pound; '.number_format($res_item['unit_price'],2,'.','').' <br>
				QTY : &nbsp; '.$res_item['qty'].' <br>
				Total Price : &nbsp; &pound; '.number_format($res_item['total_price'],2,'.','').'<br><br>
				</td>
			</tr>
				<tr>
				<td colspan="2" align="right" style="padding-right:30px;"></td>
			</tr>';
			}
			
			$body .='<tr>
			<td height="1" colspan="2" align="right">
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td bgcolor="#999999"></td>
			</tr>
			</table>
			</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td height="30">&nbsp;</td>
			<td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: normal;"></td>
			</tr>
			</table>
			</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="30">&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="26">Thank You,</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="23">'.$res_order['chefs_name'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">Contact Number : '.$res_order['chefs_contact_number'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">Email : '.$res_order['chefs_email'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			
			<tr>
			<td>&nbsp;</td>
			<td align="center" valign="middle">&copy; Copyright Chorley bunce'.$ydate.'. All Rights Reserved</td>
			<td>&nbsp;</td>
			</tr>
			</table>';
		//echo $body;exit;
			
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From:".$from."\n";
		$headers .= "Cc:".CC_MAIL. "\n";
		
		$subject ="A New Order Placed from ".$res_order['chefs_name'];
		
		$to_admin=$admin_info["alt_email"];
		
		$to_supplier = $dbf->getDataFromTable("suppliers","email","sid='$res_order[supplier_id]'");
		//echo $to_supplier;exit;
		//echo $from.'------'.$subject.'------'.$to_admin.'-------'.$to_supplier.'---------'.$body;exit;
		@mail($to_admin,$subject,$body,$headers);
		@mail($to_supplier,$subject,$body,$headers);
		
		echo '{"status":"success"}';exit;
	}
}

######################################################################################################
#################################### UPDATE PLACE ITEM NOTES WEBSERVICE ##############################
######################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=updatePlaceItemNotes&data={"order_item_id":"1","item_notes":"Send me always best product","chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="updatePlaceItemNotes" && $_SERVER['REQUEST_METHOD']=='GET'){
	//print "<pre>";print_r(json_decode($_GET['data']));exit;
	$jsonData=json_decode($_REQUEST['data']); 
	$order_item_id = $dbf->checkXssSqlInjection(stripslashes($jsonData->order_item_id));
	$item_notes = $dbf->checkXssSqlInjection(stripslashes($jsonData->item_notes));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$item_count=$dbf->countRows("order_items","order_item_id='$order_item_id'");
		//echo $item_count;exit;
		if($item_count!=0){
			$string="special_notes='$item_notes'";
			$dbf->updateTable("order_items",$string,"order_item_id='$order_item_id'");
			echo '{"status":"success"}';exit;
		}else{
			echo '{"status":"failure","err_msg":"Item not found"}';exit;
		}
	}
}

######################################################################################################
#################################### GET PROFILE INFO OF CHEF WEBSERVICE #############################
######################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=getChefProfileInfo&data={"chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="getChefProfileInfo" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray=array();
		$chef_details=$dbf->fetchSingle("chefs_registration","*","chefs_id='$chef_id'");
		//print'<pre>';print_r($chef_details);exit;
		$resultArray=array("chef_name"=>$chef_details['chefs_name'],"chef_email"=>$chef_details['chefs_email'],"chef_contactno"=>$chef_details['chefs_contact_number'],"chef_message"=>$chef_details['chefs_message']);
		//array_push($dataArray,$resultArray);
		echo '{"status":"success","chefProfileInfo":'.json_encode($resultArray).'}';exit;
	}
}
######################################################################################################
#################################### UPDATE PROFILE SETTING WEBSERVICE ###############################
######################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=updateProfileInfo&data={"chef_id":"6","chef_name":"Prakash nayak","chef_email":"praksh@gmail.com","chef_contactno":"9861845555","chef_message":"Always live in services.","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=='updateProfileInfo' && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$chef_name=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_name));
	$chef_email=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_email));	
	$chef_contactno=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_contactno));
	$chef_message=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_message));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$string="chefs_name='$chef_name',chefs_email='$chef_email',chefs_contact_number='$chef_contactno',chefs_message='$chef_message',updated_date=NOW()";
		$dbf->updateTable("chefs_registration",$string,"chefs_id='$chef_id'");
		echo '{"status":"success"}';exit;
	}
}
#####################################################################################################
#################################### REQUEST ITEMS WEBSERVICE #######################################
#####################################################################################################
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=requestItem&data={"chef_id":"6","supplier_id":"2","request_item_notes":"SSSSSSSFFFFFFFFFFFFMMMMMMMMMMMM","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=='requestItem' && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$supplier_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->supplier_id));
	$request_item_notes=$dbf->checkXssSqlInjection(stripslashes($jsonData->request_item_notes));
	
	$chef_id=$dbf->checkXssSqlInjection(stripslashes($jsonData->chef_id));
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$chef_details=$dbf->fetchSingle("chefs_registration","chefs_name,chefs_email,chefs_contact_number","chefs_id='$chef_id'");
		$from=$chef_details['chefs_email'];
		$supplier=$dbf->fetchSingle("suppliers","full_name,email","sid='$supplier_id'");
		$to_supplier=$supplier['email'];
		$ydate=date("Y");	
		
		if($chef_id!="" && $supplier_id!="" && $request_item_notes!=""){
			$body='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px grey">
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;background-color:#000;">
	  <td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="15">&nbsp;</td>
	  <td width="1306" height="35" align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;">
	  <img src="'.$HOST.'/images/cb_logo.png" style=""/></td>
	  <td align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #000000; font-weight: bold;" width="10">&nbsp;</td>
	  </tr>
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
	  <td>&nbsp;</td>
	  <td height="20">Dear '.$supplier['full_name'].',</td>
	  <td>&nbsp;</td>
	  </tr>
	  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
	  <td>&nbsp;</td>
	  <td height="20">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr> </tr>
		<tr>
		  <td height="30" colspan="4" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">'.$request_item_notes.'</td>
		</tr>
		 <tr>
		  <td height="30" colspan="4" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bold;">&nbsp;</td>
		</tr>
		
	</table>
		</td>
		  <td>&nbsp;</td>
	  </tr>
		  <tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="26">Thank You,</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="23">'.$chef_details['chefs_name'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">Contact Number : '.$chef_details['chefs_contact_number'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">Email : '.$chef_details['chefs_email'].'</td>
			<td>&nbsp;</td>
			</tr>
			<tr style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; font-weight: bold;">
			<td>&nbsp;</td>
			<td height="20">&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td align="center" valign="middle">&copy; Copyright Chorley bunce'.$ydate.'. All Rights Reserved</td>
			<td>&nbsp;</td>
			</tr>
			</table>';
			
			$headers .= 'MIME-Version: 1.0' . "\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From:".$from."\n";
			$headers .= "Cc:".CC_MAIL. "\n";
			
			
			$subject ="Request Items by ".$chef_details['chefs_name'];
			
			//$to_admin=$admin_info["alt_email"];
			//echo $to_supplier;exit;
			//echo $from.'------'.$subject.'------'.$to_supplier.'---------'.$body;exit;
			@mail($to_supplier,$subject,$body,$headers);
			echo '{"status":"success"}';exit;
		}
	}
}



#CHEFS REGISTRATION FROM MOBILE
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=chefRegistration&data={"chefs_name":"suresh Kumar","chefs_email":"suresh@bletindia.com","chefs_contact_number":"9861245555","chefs_address":"Bhubaneswar","unique_id":"suresh","chefs_psw":"suresh"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="chefRegistration"){
	$jsonData=json_decode($_REQUEST['data']);
	$unique_id = $jsonData->unique_id;
	
	if($unique_id!=''){
		$chk_login=$dbf->countRows('chefs_registration',"unique_id='$unique_id'");
		if($chk_login > 0){
			echo '{"status":"failure","err_msg":"This User ID already exist."}';exit;
		}else{
			$chefs_name=$jsonData->chefs_name;
			$chefs_email=$jsonData->chefs_email;
			$chefs_contact_number = $jsonData->chefs_contact_number;
			$chefs_address=$jsonData->chefs_address;
			
			$unique_id=$jsonData->unique_id;
			$chefs_psw=$jsonData->chefs_psw;
			$chefs_psw_encode = base64_encode(base64_encode(trim($chefs_psw)));
			
			$string="chefs_name='$chefs_name',chefs_email='$chefs_email',chefs_contact_number='$chefs_contact_number',chefs_acc_number='$chefs_acc_number',chefs_address='$chefs_address',unique_id='$unique_id',chefs_psw='$chefs_psw_encode',created_date=Now(),updated_date=Now()";
			$dbf->insertSet("chefs_registration",$string);
			echo '{"status":"success"}';exit;
		}
	}
}



#ALLOCATION LIST CHEF WISE
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=allocationList&data={"chef_id":"6","token":"0.03249700 1481524260"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="allocationList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$today = date("Y-m-d");
	
	$chef_id = $jsonData->chef_id;
	$token = $jsonData->token;
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray = array();
		$resultArray=$dbf->fetch("job_allocations","chef_id='$chef_id' AND allocation_date >= '$today'","allo_id","","DESC");		
		foreach($resultArray as $res){
			$resultArray = array("allocation_id" =>$res["allo_id"],"chef_id" => $res["chef_id"],"job_name" => $res["job_name"],"job_location" => $res["job_location"],"allocation_date" => $res["allocation_date"]);
			array_push($dataArray,$resultArray);
		}
		if(count($resultArray) > 0){
			echo '{"status":"success","allocation_list":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","allocation_list":'.'[]'.'}';exit;
		}
	}
}





#ALLOCATION LIST CHEF WISE
//http://192.168.0.170/chorley-bunce/cbwebservice.php?method=reOrderAllocationList&data={"chef_id":"6","supplier_id":"4","token":"0.95946800 1485855323"}
if(isset($_REQUEST['method']) && $_REQUEST['method']=="reOrderAllocationList" && $_SERVER['REQUEST_METHOD']=='GET'){
	$jsonData=json_decode($_REQUEST['data']);
	$today = date("Y-m-d");
	
	$chef_id = $jsonData->chef_id;
	$supplier_id = $jsonData->supplier_id;
	$token = $jsonData->token;
	
	
	if($dbf->checkTokenSecurity($chef_id,$token)==0){
		echo '{"success":"false","err_msg":"Sorry ! you are not a authenticate user."}';exit;	
	}else{
		$dataArray = array();
		$resultArray=$dbf->fetch("job_allocations_suppliers","chef_id='$chef_id' AND sup_id='$supplier_id'","ja_id","","");		
		foreach($resultArray as $res){
			$allo_info=$dbf->fetchSingle('job_allocations','*',"allo_id='$res[job_allo_id]' AND allocation_date >= '$today'");
			if($allo_info["allo_id"]!=''){
			  $resultArray = array("allocation_id" =>$allo_info["allo_id"],"chef_id" => $allo_info["chef_id"],"job_name" => $allo_info["job_name"],"job_location" => $allo_info["job_location"],"allocation_date" => $allo_info["allocation_date"]);
			  array_push($dataArray,$resultArray);
			}
		}
		if(count($resultArray) > 0){
			echo '{"status":"success","allocation_list":'.json_encode($dataArray).'}';exit;
		}else{
			echo '{"status":"success","allocation_list":'.'[]'.'}';exit;
		}
	}
}