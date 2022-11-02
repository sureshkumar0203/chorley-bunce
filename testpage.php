<?php
//Method overloading
/*class A {
    public function one($para1) {
        echo "Here method one called ".$para1;
    }
    public function one($para1,$para2) {
       	echo "Method two called ".$para1."--".$para2;
    }
}
$obj = new A;
$obj->one('Hello');*/



//Method overriding
/*class cls{
	function one(){
		echo "I am here";
	}
}
class cls1 extends cls{
	function one(){
		echo "I am here ok";
	}
}

$obj = new cls1;
$obj->one();*/


if(isset($_POST['operation']) && $_REQUEST['operation']=='insert'){
	if(!empty($_POST['req_sub'])) {
        foreach ($_POST['req_sub'] as $selected) {
            $total = $_POST['total'][$selected];
            $pass = $_POST['pass'][$selected];
            $var = $selected . ',' . $total . ',' . $pass;
            echo $var . '<br />';
        }
    }
}
?>

<form id="frm_chefs" name="frm_chefs" method="post" action="testpage.php" class="form-horizontal" enctype="multipart/form-data">
<input type="hidden" name="operation" value="insert">
  <?php for($s=1; $s < 3 ; $s++){?>
  <input type="checkbox" name="req_sub[]" value= "<?php echo $s; ?>" />Paper - <?php echo $s; ?>
  <input type="text" name="total[<?php echo $s; ?>]" placeholder="Total Number" />
  <input type="text" name="pass[<?php echo $s; ?>]" placeholder="Pass Number" />
  <br />
  <?php } ?>
  
  <button type="submit" class="btn btn-primary">Save</button>
</form>


<?php
for($i=1;$i<10;$i++){
	for($k=10-$i;$k>1;$k--){
		echo "&nbsp;";
	}
	for($j=1;$j<=$i;$j++){
		echo "&nbsp;".$i."&nbsp;";
	}
	echo "<br>";
}

$x=0;
$y=1;
for($i=1;$i<=10;$i++){
	echo $z=$x+$y."<br>";
	$x=$y;
	$y=$z;
}

for($i=1;$i<=10;$i++){
	$co=0;
	for($j=1;$j<=$i;$j++){
		if($i % $j ==0){
			$co++;
		}
	}
	if($co==2){
		echo $i."is a prime number";
	}
}
?>