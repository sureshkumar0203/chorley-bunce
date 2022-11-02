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
	$del_rec_id=isset($_REQUEST['chefs_id'])?$dbf->checkSqlInjection($_REQUEST['chefs_id']):0;
	$testurlkey=$dbf->keyMaker($del_rec_id);
	
	if($testurlkey!=$_REQUEST['token']){
		header("Location:manage-chefs");
		exit;
	}
	
	$num=$dbf->countRows("job_allocations","chef_id='$del_rec_id'");
	if($num==0){
	  //delete chefs
	  $dbf->deleteFromTable("chefs_registration","chefs_id='$del_rec_id'");
	  $dbf->deleteFromTable("chef_token","chef_id='$del_rec_id'");
	   
	  header("Location:manage-chefs");
	  exit;
	}else{
		header("Location:manage-chefs?msg=1");
	  	exit;
	}
}

//User Login block code starts here
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='declined'){
	$rec_id=isset($_REQUEST['id'])?$dbf->checkSqlInjection($_REQUEST['id']):0;	
	$testurlkey=$dbf->keyMaker($rec_id);
	
	if($testurlkey!=$_REQUEST['token']){ 		
		header("location:manage-chefs");exit;
	}
	$dbf->updateTable("chefs_registration","login_status='0'","chefs_id='$rec_id'");
	/*************MAIL GOES TO CHEFS ABOUT ACCOUNT DECLINED***************/
	$admin_info=$dbf->fetchSingle("core","*","id='1'");
	$admin_email=$admin_info['alt_email']; //From Email
	$admin_name=$admin_info['admin_name'];
	$current_year = date("Y");
	
	$chefs_info =$dbf->fetchSingle('chefs_registration','*',"chefs_id='$rec_id'");
	$chefs_name = $chefs_info['chefs_name'];
	$chefs_email = $chefs_info['chefs_email'];
	
	//subject and content
	$res_email_template_user=$dbf->fetchSingle("email_template","*","id='2'");
	$subject ="Chorley Bunce declined your account";
	
	$headers = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From:".$admin_email."\n";
	$headers .= "Cc:".CC_MAIL. "\n";
	
	
	$input_user=$res_email_template_user['contents'];			
	$body = str_replace(array('%FULLNAME%','%ADMINEMAIL%','%ADMINNAME%',"%CURRENTYEAR%"),array($chefs_name,$admin_email,$admin_name,$current_year),$input_user);
	
	//echo $body;exit;
	$ok=mail($chefs_email,$subject,$body,$headers);
	/*************MAIL GOES TO CHEFS ABOUT ACCOUNT DECLINED***************/
			
	
	header("Location:manage-chefs");
}
//User Login block code ends here
?>
<body>
    <div id="wrapper">
    	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
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
                        <h2 class="panel-title pull-left" style="line-height:34px;">Manage Chefs</h2>
                        <!--<a href="add-chefs" class="btn btn-primary pull-right">Add New</a>-->
                      </div>
                      
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                              <?php if($_REQUEST['msg']==1){ ?>
                               <span style="color:#F00; display:block; margin-bottom:10px;">You can not delete this chef because some allocation asigned to this chef.</span>
                               <?php } ?>
                                <table class="table table-striped table-bordered table-hover" id="tbl_content">
                                    <thead>
                                        <tr>
                                          	<th>Chefs Name</th>
                                            <th>Email</th>
                                            <th>Contact Number</th>
                                            <th>Login ID</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                  
                           <tbody>
                           <?php 
                           $cls='odd gradeX';
                           foreach($dbf->fetch("chefs_registration","","chefs_id","","DESC") as $chefs_det) {
                           ?>
                          <tr>
                            <td  style="color:<?php echo $color; ?>"><a href="edit-chefs?editId=<?php echo $chefs_det['chefs_id'];?>&token=<?php echo $dbf->keymaker($chefs_det['chefs_id']);?>" title="Click here to edit"><?php echo $chefs_det['chefs_name']; ?></a></td>
                            <td><?php echo $chefs_det['chefs_email']; ?></td>
                            <td> <?php echo $chefs_det['chefs_contact_number']; ?></td>
                            <td><?php echo $chefs_det['unique_id']; ?></td>
                            <td class="text-center">
							  <?php if($chefs_det['login_status']==0){ ?>
                              <a href="edit-chefs?editId=<?php echo $chefs_det['chefs_id'];?>&token=<?php echo $dbf->keymaker($chefs_det['chefs_id']);?>" class="btn btn-danger btn-xs" title="CLICK HERE TO APPROVE THE ACCOUNT">&nbsp;&nbsp;Declined&nbsp;&nbsp;</a>
                              <?php } else { ?>
                              <a href="manage-chefs?operation=declined&id=<?php echo $chefs_det['chefs_id'];?>&token=<?php echo $dbf->keyMaker($chefs_det['chefs_id']);?>" class="btn btn-success btn-xs" title="CLICK HERE TO DECLINED THE ACCOUNT" onClick="return confirm('Are you sure you want to declined this account ?')">&nbsp;&nbsp;Approved&nbsp;&nbsp;</a>
                              <?php } ?>
                              
                              <a href="manage-chefs?operation=delete&chefs_id=<?php echo $chefs_det['chefs_id'];?>&token=<?php echo $dbf->keyMaker($chefs_det['chefs_id']);?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure you want to delete this record ?')">&nbsp;&nbsp;Delete&nbsp;&nbsp;</a>
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
