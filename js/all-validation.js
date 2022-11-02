//START OF RESTRICTING XSS CODE
function chk_xss(xss) {
    var maintainplus = '';
    var numval = xss.value
    curphonevar = numval.replace(/[\\!"£$%^&*+_={};:'#@()~, .¦\/<>?|`¬\]\[]/g, '');
    xss.value = maintainplus + curphonevar;
    var maintainplus = '';
    xss.focus;
}
//END OF RESTRICTING XSS CODE -->

//This function is for price validation
//onBlur="extractNumber(this,2,false)" onKeyUp="extractNumber(this,2,false);" onKeyPress="return blockNonNumbers(this, event, true, false);"
function extractNumber(obj, decimalPlaces, allowNegative)
{
    var temp = obj.value;

    // avoid changing things if already formatted correctly
    var reg0Str = '[0-9]*';
    if (decimalPlaces > 0) {
        reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
    } else if (decimalPlaces < 0) {
        reg0Str += '\\.?[0-9]*';
    }
    reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
    reg0Str = reg0Str + '$';
    var reg0 = new RegExp(reg0Str);
    if (reg0.test(temp))
        return true;

    // first replace all non numbers
    var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
    var reg1 = new RegExp(reg1Str, 'g');
    temp = temp.replace(reg1, '');

    if (allowNegative) {
        // replace extra negative
        var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
        var reg2 = /-/g;
        temp = temp.replace(reg2, '');
        if (hasNegative)
            temp = '-' + temp;
    }

    if (decimalPlaces != 0) {
        var reg3 = /\./g;
        var reg3Array = reg3.exec(temp);
        if (reg3Array != null) {
            // keep only first occurrence of .
            //  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
            var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
            reg3Right = reg3Right.replace(reg3, '');
            reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
            temp = temp.substring(0, reg3Array.index) + '.' + reg3Right;
        }
    }

    obj.value = temp;
}

function Phone(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ((charCode >= 97 && charCode <= 122) || ((charCode >= 65 && charCode <= 90))) {
        return false;
    } else if ((charCode >= 48 && charCode <= 57) || charCode == 43 || charCode == 32 || charCode == 45 || charCode == 40 || charCode == 41 || charCode == 8) {
        return true;
    } else {
        return false;
    }
}


function adminChangePassword() {
    if ($('#old_pwd').val() == "") {
        $("#old_pwd").focus();
        return false;
    } else if ($('#new_pwd').val() == "") {
        $("#new_pwd").focus();
        return false;
    } else if ($('#new_pwd').val() != $('#con_password').val()) {
        $('#new_pwd').val('');
        $('#con_password').val('');
        $("#new_pwd").focus();
        return false;
    }
}



function templateValidation() {
    if ($('#title').val() == "") {
        $('#title').css("borderColor", "#F00");
        $("#title").focus();
        return false;
    } else if (CKEDITOR.instances.contents.getData() == '') {
        $("#contents").focus();
        return false;
    }
}


//This function is only for reset password
function resetPassword() {
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $("#email").val();
    if (email == "" || !email.match(email_regx)) {
        $('#response').html("Please enter a valid email address");
        $('#response').css('color', 'red');
        return false;
    } else {
        $.post("login-process.php", {operation: "forgotpass", mail: email}, function (data, status) {
            //alert(data);
            if (data == 1) {
                $('#forg_pass').hide();
                $('#response').html('Your password has been reset and send to your email address.<a href="./">Click here to login</a>');
                $('#response').css('color', 'green');
            } else {
                $('#response').html("Sorry, This email address does not exist");
                $('#response').css('color', 'red');
            }
        });


    }

}


//This function is only for show,hide login & forgot password form
function forgotPassword(str) {
    var forg_pass = document.getElementById('forg_pass');
    var show_login = document.getElementById('show_login');

    $('#response').html('');

    if (str == "showlogin") {
        forg_pass.style.display = "block";
        $('#header_txt').html('Forgot Password');
        show_login.style.display = "none";
    } else if (str == "showforg") {
        show_login.style.display = "block";
        $('#header_txt').html('Please Sign In');
        forg_pass.style.display = "none";
    }
}


function categoryValidation() {
	var cat_photo_val = $("#category_photo").val();
	var extension = cat_photo_val.split('.').pop().toUpperCase();
		
    if ($('#category_name').val().trim().length < 1 || $('#category_name').val() == '') {
        $('#category_name').css("borderColor", "#F00");
        $('#category_name').focus();
        return false;
    }
    if ($('#category_photo').val().trim().length < 1 || $('#category_photo').val() == '') {
        $('#category_photo').css("borderColor", "#F00");
        $('#category_photo').focus();
        return false;
    }
	if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
		$('#ph_mesg').html('upload only jpg,jpeg,png image format');
		$('#category_photo').css("borderColor", "#F00");
		return false;
	}
}
function editCategoryValidation() {
    if ($('#category_name').val().trim().length < 1 || $('#category_name').val() == '') {
        $('#category_name').css("borderColor", "#F00");
        $('#category_name').focus();
        return false;
    }else if ($('#category_photo').val().trim()!='') {
		var cat_photo_val = $("#category_photo").val();
		var extension = cat_photo_val.split('.').pop().toUpperCase();
		if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
			$('#ph_mesg').html('upload only jpg,jpeg,png format image.');
			$('#category_photo').css("borderColor", "#F00");
			return false;
		}
    }
}


function supplierValidation() {
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	var supplier_photo_val = $("#supplier_photo").val();
	var extension = supplier_photo_val.split('.').pop().toUpperCase();
	
    if ($('#full_name').val().trim().length < 1 || $('#full_name').val() == '') {
        $('#full_name').css("borderColor", "#F00");
        $('#full_name').focus();
        return false;
    } else if ($('#email').val().trim().length < 1 || $('#email').val() == "") {
        $('#email').css("borderColor", "#F00");
        $("#email").focus();
        return false;
    } else if (!$('#email').val().match(email_regx)) {
        $('#email').css("borderColor", "#F00");
        $("#email").focus();
        return false;
    } else if ($('#supp_acc_number').val() == '') {
        $('#supp_acc_number').css("borderColor", "#F00");
        $("#supp_acc_number").focus();
        return false;
    } else if ($('#supplier_photo').val().trim().length < 1 || $('#supplier_photo').val() == "") {
        $('#supplier_photo').css("borderColor", "#F00");
        $("#supplier_photo").focus();
        return false;
    }else if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
		$('#ph_mesg').html('upload only jpg,jpeg,png image format');
		$('#supplier_photo').css("borderColor", "#F00");
		return false;
	}
}

function editSupplierValidation() {
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if ($('#full_name').val().trim().length < 1 || $('#full_name').val() == '') {
        $('#full_name').css("borderColor", "#F00");
        $('#full_name').focus();
        return false;
    } else if ($('#email').val().trim().length < 1 || $('#email').val() == "") {
        $('#email').css("borderColor", "#F00");
        $("#email").focus();
        return false;
    } else if (!$('#email').val().match(email_regx)) {
        $('#email').css("borderColor", "#F00");
        $("#email").focus();
        return false;
    } else if ($('#supp_acc_number').val() == "") {
        $('#supp_acc_number').css("borderColor", "#F00");
        $("#supp_acc_number").focus();
        return false;
    }else if ($('#supplier_photo').val().trim()!='') {
		var supplier_photo_val = $("#supplier_photo").val();
		var extension = supplier_photo_val.split('.').pop().toUpperCase();
		
		if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
			$('#ph_mesg').html('upload only jpg,jpeg,png format image.');
			$('#supplier_photo').css("borderColor", "#F00");
			return false;
		}
    }
}


function unitValidation() {
    if ($('#unit_name').val().trim().length < 1 || $('#unit_name').val() == '') {
        $('#unit_name').css("borderColor", "#F00");
        $('#unit_name').focus();
        return false;
    }
	
}

function productExcelValidation() {
	var exl_val = $("#upload_excel").val();
	var extension = exl_val.split('.').pop().toUpperCase();
	
    if ($('#upload_excel').val().trim().length < 1 || $('#upload_excel').val() == "") {
        $('#upload_excel').css("borderColor", "#F00");
        $("#upload_excel").focus();
        return false;
    }
	if (extension!="XLSX" && extension!="XLS"){
		$('#ph_mesg').html('upload only xlsx,xls format');
		$('#upload_excel').css("borderColor", "#F00");
		return false;
	}
	
}

function chefsValidation() {
    var email_regx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ($('#chefs_name').val().trim().length < 1 || $('#chefs_name').val() == '') {
        $('#chefs_name').css("borderColor", "#F00");
        $('#chefs_name').focus();
        return false;
    } else if ($('#chefs_email').val().trim().length < 1 || $('#chefs_email').val() == "") {
        $('#chefs_email').css("borderColor", "#F00");
        $("#chefs_email").focus();
        return false;
    } else if (!$('#chefs_email').val().match(email_regx)) {
        $('#chefs_email').css("borderColor", "#F00");
        $("#chefs_email").focus();
        return false;
    } else if ($('#chefs_acc_number').val() == "") {
        $('#chefs_acc_number').css("borderColor", "#F00");
        $('#chefs_acc_number').focus();
        return false;
    } else if ($('#unique_id').val().trim().length < 1 || $('#unique_id').val() == '') {
        $('#unique_id').css("borderColor", "#F00");
        $('#unique_id').focus();
        return false;
    } else if ($('#chefs_psw').val().trim().length < 1 || $('#chefs_psw').val() == '') {
        $('#chefs_psw').css("borderColor", "#F00");
        $('#chefs_psw').focus();
        return false;
    }
}


function productValidation() {
	var prd_photo_val = $("#product_photo").val();
	var extension = prd_photo_val.split('.').pop().toUpperCase();
	
    if ($('#supplier_id').val() == '') {
        $('#supplier_id').css('borderColor', '#F00');
        $('#supplier_id').focus();
        return false;
    } else if ($('.chkcat:checkbox:checked').length == 0) {
        $('#chk_msg').html(' select at least one check box').css('color', '#F00');
        return false;
    } else if ($('#product_name').val().trim().length < 1 || $('#product_name').val() == '') {
        $('#product_name').css('borderColor', '#F00');
        $('#product_name').focus();
        return false;
    } else if ($('#product_name').val().trim().length < 1 || $('#product_name').val() == '') {
        $('#product_name').css('borderColor', '#F00');
        $('#product_name').focus();
        return false;
    } else if ($('#product_code').val().trim().length < 1 || $('#product_code').val() == '') {
        $('#product_code').css('borderColor', '#F00');
        $('#product_code').focus();
        return false;
    } else if ($('#qty_details').val().trim().length < 1 || $('#qty_details').val() == '') {
        $('#qty_details').css('borderColor', '#F00');
        $('#qty_details').focus();
        return false;
    } else if ($('#product_price').val().trim().length < 1 || $('#product_price').val() == '') {
        $('#product_price').css('borderColor', '#F00');
        $('#product_price').focus();
        return false;
    } else if ($('#prd_unit_name').val().trim().length < 1 || $('#prd_unit_name').val() == '') {
        $('#prd_unit_name').css('borderColor', '#F00');
        $('#prd_unit_name').focus();
        return false;
    } else if ($('#product_photo').val().trim().length < 1 || $('#product_photo').val() == '') {
        $('#product_photo').css('borderColor', '#F00');
        $('#product_photo').focus();
        return false;
    } else if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
		$('#ph_mesg').html('upload only jpg,jpeg,png image format');
		$('#product_photo').css("borderColor", "#F00");
		return false;
	}
}


function editProductValidation() {
    if ($('#supplier_id').val() == '') {
        $('#supplier_id').css('borderColor', '#F00');
        $('#supplier_id').focus();
        return false;
    } else if ($('.chkcat:checkbox:checked').length == 0) {
        $('#chk_msg').html(' select at least one check box').css('color', '#F00');
        ;
        return false;
    } else if ($('#product_name').val().trim().length < 1 || $('#product_name').val() == '') {
        $('#product_name').css('borderColor', '#F00');
        $('#product_name').focus();
        return false;
    } else if ($('#product_name').val().trim().length < 1 || $('#product_name').val() == '') {
        $('#product_name').css('borderColor', '#F00');
        $('#product_name').focus();
        return false;
    } else if ($('#product_code').val().trim().length < 1 || $('#product_code').val() == '') {
        $('#product_code').css('borderColor', '#F00');
        $('#product_code').focus();
        return false;
    } else if ($('#qty_details').val().trim().length < 1 || $('#qty_details').val() == '') {
        $('#qty_details').css('borderColor', '#F00');
        $('#qty_details').focus();
        return false;
    } else if ($('#product_price').val().trim().length < 1 || $('#product_price').val() == '') {
        $('#product_price').css('borderColor', '#F00');
        $('#product_price').focus();
        return false;
    } else if ($('#prd_unit_name').val().trim().length < 1 || $('#prd_unit_name').val() == '') {
        $('#prd_unit_name').css('borderColor', '#F00');
        $('#prd_unit_name').focus();
        return false;
    } else if ($('#product_photo').val().trim()!='') {
		var prd_photo_val = $("#product_photo").val();
		var extension = prd_photo_val.split('.').pop().toUpperCase();
		
		if (extension!="PNG" && extension!="JPG" && extension!="GIF" && extension!="JPEG"){
			$('#ph_mesg').html('upload only jpg,jpeg,png format image.');
			$('#product_photo').css("borderColor", "#F00");
			return false;
		}
    }
}



$('#tbl_sup').find('input[type="checkbox"]').each(function () {
    $(this).click(function () {
        if ($(this).is(':checked')) {
            //alert('checked');
            $(this).attr('checked', true);

        } else {
            //alert('unchecked');
            $(this).removeAttr('checked');
        }
    });
});

function allocationValidation() {
	var x = $('#tbl_sup').find('input[type="checkbox"]').is(':checked');
    if($('#chef_id').val() == '') {
        $('#chef_id').css('borderColor', '#F00');
        $('#chef_id').focus();
        return false;
    }
    if(x == false) {
        status = 0;
        $("#msg_chk").html('Please choose atleast one supplier & enter supplier A/c Number.');
        return false;
    }
    if (x === true) {
		var checked_field = $('#tbl_sup').find('input[type="checkbox"]:checked');
        $(checked_field).each(function () {
            var z = $(this).parent().next().find('input[type="text"]').val();
            if (z == "") {
                status = 0;
                $(this).parent().next().find('input[type="text"]').focus();
                return false;
            } else {
				  status = 1;
            }
        });
		
        if (status == 1) {
            if ($('#job_name').val().trim().length < 1 || $('#job_name').val() == '') {
                $('#job_name').css('borderColor', '#F00');
                $('#job_name').focus();
                return false;
            } else if ($('#job_location').val().trim().length < 1 || $('#job_location').val() == '') {
                $('#job_location').css('borderColor', '#F00');
                $('#job_location').focus();
                return false;
            } else if ($('#allocation_date').val().trim().length < 1 || $('#allocation_date').val() == '') {
                $('#allocation_date').css('borderColor', '#F00');
                $('#allocation_date').focus();
                return false;
            } else {
                status = 2;
            }
        }

    }

    if (status == 2) {
        return true;
    } else {
        return false;
    }


}


function resetAcno(rid) {
    if ($('#sup_id' + rid).not(':checked')) {
        $("#sup_ac_no" + rid).val('');
    }
}

function chooseSupplier(rid) {
    if ($('#sup_ac_no' + rid).val()!='') {
        $('#sup_id' + rid).prop('checked',true);
    }
}
