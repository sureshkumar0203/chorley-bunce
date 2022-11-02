<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
function chkValidation(){
	if($(".chk").not(':checked')){
		alert('here');
		return false;
	}
}
</script>
</head>

<body>
<?php
//Factorial
$fac = 1;
for($f=5;$f>1;$f--){
  $fac=$fac*$f;
}
echo $fac;
?>

<br />
<br />

<?php
//Fibonanci 
$x=0;
$y=1;
for($i=0;$i<=10;$i++){
	$z = $x+$y;
	echo $z."<br>";
	$x= $y;
	$y= $z;
}
?>

<br />
<br />



<?php
// Number Pyramid
for($i=1;$i<=10;$i++){
	for ($k=10-$i; $k > 0; $k--)  {
        echo "&nbsp;&nbsp;&nbsp;";
    }
	
	for($j=1;$j<=$i;$j++){
		echo "&nbsp;&nbsp;".$j."&nbsp;&nbsp;";
		//$k++;
	}
	echo "<br>";
}
?>

<br />
<br />
<br />

<?php
// Number Pyramid
$k=1;
for($i=1;$i<=9;$i++){
	for ($k=10-$i; $k > 0; $k--)  {
        echo "&nbsp;&nbsp;";
    }
	
	for($j=1;$j<=$i;$j++){
		echo "&nbsp;&nbsp;".$i."&nbsp;";
		//$k++;
	}
	echo "<br>";
}
?>
<br />
<br />
<br />


<?php
//Even Number
for($eo=1;$eo<=10;$eo++){
	if($eo % 2 == 0){
		echo $eo."<br>";
	}
}
?>

<br />
<br />

<?php
echo strrchr("hello.abc.jpeg",'.');
?>
<br />
<br />

<?php
//Prime Number
for($i=1;$i<=10;$i++){
	$counter = 0;
	
	//all divisible factors
	for($j=1;$j<=$i;$j++){
		if($i % $j==0){
			$counter++;
		}
	}
	//prime requires 2 rules ( divisible by 1 and divisible by itself)
	if($counter==2){
		print $i." is Prime <br/>";
	}
}
?>


<br />
<br />
<?php
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='save'){
	//print_r($_REQUEST[sup_id]);exit;
	foreach($_REQUEST['sup_id'] as $sup_det){
		echo $sup_det."--".$_REQUEST['su_ac_no'][$sup_det];
	}
}
?>
<form method="post" name="frm_test" id="frm_test" action="test.php" onsubmit="return chkValidation();">
<input type="hidden" name="operation" value="save" />
<?php for($i=1;$i<=5;$i++){ ?>
  <input class="chk" type="checkbox" name="sup_id[]" id="sup_id<?php echo $i; ?>" value="<?php echo $i; ?>" /> <?php echo $i; ?>
  &nbsp;<input type="text" name="su_ac_no[<?php echo $i; ?>]" id="su_ac_no<?php echo $i; ?>" /><br />
<?php } ?>
<input type="submit" name="Submit" id="Submit" value="Submit" />
</form>
</body>
</html>