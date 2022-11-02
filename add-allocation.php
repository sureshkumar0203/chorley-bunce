<?php
ob_start();
session_start();
include_once "includes/class.Main.php";
$dbf = new Main();


include_once 'includes/class.Notification.php';
$dbn = new Notification();

//Object initialization

if (!$dbf->checkSession()) {
    header('location:./');
    exit;
}
$page_title = 'Administrator';
include 'application-top.php';



#############################################################################
############################## ADD CHEFS ####################################
#############################################################################
if (isset($_POST['operation']) && $_REQUEST['operation'] == 'insert') {
    if ($dbf->checkSecurity($_SERVER)) {
        $chef_id = $dbf->checkXssSqlInjection(trim($_REQUEST['chef_id']));
        $job_name = $dbf->checkXssSqlInjection(trim($_REQUEST['job_name']));
        $job_location = $dbf->checkXssSqlInjection(trim($_REQUEST['job_location']));
        $allocation_date = $dbf->checkXssSqlInjection(trim($_REQUEST['allocation_date']));
        $allocation_date_for_mail = date("jS M, Y", strtotime($allocation_date));

        //$chk_allo_dt = $dbf->countRows('job_allocations', "chef_id='$chef_id' AND allocation_date='$allocation_date'");

        //echo "<pre>"; print_r($_REQUEST['sup_id']);exit;


        if ($chef_id == "" || $job_name == "" || $job_location == "" || $allocation_date == "") {
            $msg = 1;
        } else {
            $string = "chef_id='$chef_id',job_name='$job_name',job_location='$job_location',allocation_date='$allocation_date',created_date=Now(),updated_date=Now()";
            $ins_id = $dbf->insertSet("job_allocations", $string);

            for ($j = 1; $j < $_REQUEST['sup_count']; $j++) {
                $sup_id = $_REQUEST['sup_id' . $j];
                $sup_ac_no = $_REQUEST['sup_ac_no' . $j];
                if ($sup_id != "" && $sup_ac_no != "") {
                    $string_sup_ins = "chef_id='$chef_id',job_allo_id='$ins_id',sup_id='$sup_id',sup_ac_no='$sup_ac_no',created_date=Now(),updated_date=Now()";
                    $dbf->insertSet("job_allocations_suppliers", $string_sup_ins);
                }
            }

            /*************MAIL GOES TO ALLOCATED CHEF************** */
            $admin_info = $dbf->fetchSingle("core", "*", "id='1'");
            $admin_email = $admin_info['alt_email']; //From Email
            $admin_name = $admin_info['admin_name'];
            $current_year = date("Y");

            $chefs_info = $dbf->fetchSingle('chefs_registration', '*', "chefs_id='$chef_id'");
            $chefs_name = $chefs_info['chefs_name'];
            $chefs_email = $chefs_info['chefs_email'];

            //subject and content
            $res_email_template_user = $dbf->fetchSingle("email_template", "*", "id='3'");
            $subject = "A new job allocated to you";
			
            $headers = "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers .= "From:" . $admin_email . "\n";
			$headers .= "Cc:".CC_MAIL. "\n";
			
			
            $input_user = $res_email_template_user['contents'];
            $body = str_replace(array('%FULLNAME%', '%JOBNAME%', '%LOCATION%', '%DATE%', '%ADMINNAME%', '%ADMINEMAIL%', '%CURRENTYEAR%'), array($chefs_name, $job_name, $job_location, $allocation_date_for_mail, $admin_name, $admin_email, $current_year), $input_user);

            //echo $body;exit;
            $ok = mail($chefs_email, $subject, $body, $headers);
            /*************MAIL GOES TO ALLOCATED USER***************/

            /*************Notification Start***************/
            $message = "A new allocation named " . $job_name . " allocated on " . date("jS M, Y", strtotime($allocation_date));
            foreach ($dbf->fetch("chef_token", "chef_id='$chef_id'")as $token_info) {
                $deviceToken = $token_info['device_token'];
                //check for ios or android device
                if ($token_info['device_token'] != '' && $token_info['device_type'] == 'ios') {
					//echo $token_info['device_token']."".$token_info['device_type']."<br>";
                    $dbn->send_ios_notification($deviceToken,$message);
                }

                if ($token_info['fcm_id'] != '' && $token_info['device_type'] == 'android') {
                    //$dbn->send_android_notification($token_info['fcm_id'],$message);
                }
            }
            /*************Notification end************** */
			
			
            header("Location:add-allocation?msg=3");
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
                    <h1 class="page-header">Add Allocation</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <?php if ($msg == '1') { ?>
                            <span style="color:#F00;">Please enter all * marked fields value.</span>
                        <?php } ?>

                        <?php if ($msg == '2') { ?>
                            <span style="color:#F00;">This allocation date already exist.</span>
                        <?php } ?>

                        <?php if ($_REQUEST['msg'] == '3') { ?>
                            <span style="color:#090;">Records has been saved successfully.</span>
                        <?php } ?>
                    </div>
                </div>
            </div>


            <form id="frm_chefs" name="frm_chefs" method="post" action="add-allocation" class="form-horizontal" onSubmit="return allocationValidation();" enctype="multipart/form-data">
                <input type="hidden" name="operation" value="insert">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Select Chef *</label>
                                <select class="form-control" name="chef_id" id="chef_id">
                                    <option value="">--Select--</option>
                                    <?php foreach ($dbf->fetch("chefs_registration", "login_status='1'", "chefs_name", "", "ASC") as $chef_det) { ?>
                                        <option value="<?php echo $chef_det['chefs_id']; ?>" <?php
                                        if ($chef_det['chefs_id'] == $_REQUEST['chef_id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $chef_det['chefs_name']; ?></option>
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
                                    $sup_count = 1;
                                    foreach ($dbf->fetch("suppliers", "", "full_name", "", "ASC") as $sup_det) {
                                        if ($sup_count % 2 != 0) {
                                            echo '<tr>';
                                        }
                                        ?>
                                        <td><input type="checkbox" name="sup_id<?php echo $sup_count; ?>" id="sup_id<?php echo $sup_count; ?>" value="<?php echo $sup_det['sid']; ?>" onClick="resetAcno('<?php echo $sup_count; ?>');"> &nbsp; <?php echo $sup_det['full_name']; ?></td>

                                        <td><input type="text" class="form-control" name="sup_ac_no<?php echo $sup_count; ?>" id="sup_ac_no<?php echo $sup_count; ?>" autocomplete="off" onKeyUp="chooseSupplier('<?php echo $sup_count; ?>');"></td>
                                        <?php
                                        if ($sup_count % 2 == 0) {
                                            echo '</tr>';
                                        }
                                        $sup_count = $sup_count + 1;
                                    }
                                    ?>



                                    <input type="hidden" name="sup_count" id="sup_count" value="<?php echo $sup_count; ?>">
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Job Name*</label>
                                <input class="form-control" name="job_name" id="job_name" value="<?php echo $_REQUEST['job_name']; ?>" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Location*</label>
                                <input class="form-control" name="job_location" id="job_location" value="<?php echo $_REQUEST['job_location']; ?>" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>Allocation Date*</label>
                                <input class="form-control" name="allocation_date" id="allocation_date" value="<?php echo $_REQUEST['allocation_date']; ?>" autocomplete="off" readonly>
                            </div>
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

<?php include('common-js.php'); ?>
    <script type="text/javascript" src="js/all-validation.js"></script>
    <script>$('#lbl_3').addClass('active');</script>


    <!--date picker-->
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    <script src="datepicker/jquery-ui.js"></script>
    <script type="text/javascript">
                $(function () {
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