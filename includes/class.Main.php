<?php
error_reporting(1);
$mconf=parse_ini_file("config.message.php",true);
include_once 'class.Pagination.php';
//include_once('db.constants.php');
include_once('db.constants.php');
//*************************Involves Any User operations***********************************
class Main extends Pagination{
	public $dbcon;	
	//Database connect 
	public function __construct(){		
		$this->dbcon = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE) or die('Oops connection error -> ' . mysqli_connect_error());								
	}		
	
	// Login process
	public function checkLogin($emailusername,$password,$tblname="core"){
		//echo "SELECT * FROM $tblname WHERE email = '$emailusername'  AND password = '$password' AND active_status='1'"; exit;
		$result = mysqli_query($this->dbcon,"SELECT * FROM $tblname WHERE email = '$emailusername'  AND password = '$password' AND active_status='1'");
		$admin_data=mysqli_fetch_assoc($result);
		//print_r($admin_data);exit;
		if ($result->num_rows==1){
			$_SESSION['core_login'] = true;
			$_SESSION['admin_id'] = $admin_data['id'];
			$_SESSION['admin_name'] = $admin_data['admin_name'];
			mysqli_free_result($result);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	// Getting session 
	public function checkSession(){
		if(isset($_SESSION['core_login']) && isset($_SESSION['admin_id'])){
		//if(isset($_SESSION['core_login']) && isset($_SESSION['admin_id']) && $_SESSION['admin_id']=='1'){						
			return $_SESSION['core_login'];
		}else{
			return false;
		}
	}
	
	
	// Logout 
	public function userLogout(){
		$_SESSION['core_login'] = FALSE;
		unset($_SESSION['admin_id']);	
	    unset($_SESSION['admin_name']);		    	
		session_destroy();	
	}	
	
	
	# check user login
	public function checkUserLogin($user,$password,$tblname){
		$realPass=md5($password);
		//echo "SELECT * FROM $tblname WHERE email='$user' AND password ='$realPass' AND status='1'";//exit;
		$result = mysqli_query($this->dbcon,"SELECT * FROM $tblname WHERE email='$user' AND password ='$realPass' AND status='1'"); 
		$user_data=mysqli_fetch_assoc($result);						
		if($result->num_rows==1){
			$_SESSION['user_login'] = true;		
			$_SESSION['user_id'] = $user_data['first_name'];
			//$_SESSION['user_name'] = $user_data['first_name'];
			mysqli_free_result($result);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	
	# Check User session
	public function checkUserSession(){
		if(isset($_SESSION['user_login']) && isset($_SESSION['user_id'])){						
			return $_SESSION['user_login'];
		}else{
			return false;
		}
	}	
	
	# User Logout
	public function Logout(){
		$_SESSION['user_login'] = FALSE;
		unset($_SESSION['user_id']);	
		unset($_SESSION['id']);
	    //unset($_SESSION['user_name']);
		session_destroy();	
	}
		
	//check email
	public function checkEmailAddress($email) {
		$valid=preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email);
		return $valid;
    } 
	
	//check url
	public function checkUrl($url){		
		if(!filter_var($url, FILTER_VALIDATE_URL))
  			return false;
		else
  			return true;		 	
	}
	
	//Check ip
	public function checkIp($ip){
		if(filter_var($value01,FILTER_VALIDATE_IP))
			return true;
		else
  			return false;	
	}
	
	//check phone
	public function checkPhone($phone){
		if(preg_match('/^\d{10}{12}$/',$phone) ) {
			return true;
		}else{
			return false;
		}	
	}
	
	//Sanitize String
	public function sanitizeString($string){
		return filter_var($string, FILTER_SANITIZE_STRING);
	}
	
	//Sanitize Encode same as urlencode
	public function sanitizeEncode($string){
		return filter_var($string,FILTER_SANITIZE_ENCODED);
	}
	
	//Sanitize Special character same as htmlentities and htmlspecialchar
	public function sanitizeChar($string){
		return filter_var($string,FILTER_SANITIZE_SPECIAL_CHAR);
	}
	
	//Sanitize any character from number
	public function sanitizeNumber($string){
		return filter_var($string,FILTER_VALIDATE_INT);
	}
	public function sanitizeFloatNumber($string){
		return filter_var($string,FILTER_SANITIZE_NUMBER_FLOAT);
	}	
	
	//Sanitize any character from number but allow decimal
	public function sanitizeNumberWithDecimal($string){
		return filter_var($string,FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}
		 
	//check file extension
	function convertMe($str) {    
		$str = preg_replace("/[^\w\d\.\-]/","-",$str);
		return $str;
	}	
	
	//Check security for hacking
	public function checkSecurity($server){
		if(isset($server['REQUEST_METHOD']) && $server['REQUEST_METHOD']=='POST'){
			if(false !== strpos($server['SERVER_NAME'],SERVER_NAME)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	//Protect form XSS
	public function checkXss($string='',$stripTag=true,$htmlspecialcharacter=true){
		if($stripTag){
			$string=strip_tags($string);
			$string = str_ireplace( '%3Cscript', '', $string );
		}		
		if($htmlspecialcharacter){
			$string=htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}		
		return $string;
	}
	
	//Check xss
	public function checkXssSqlInjection($string='',$stripTag=true,$htmlspecialcharacter=true,$mysql_real_escape=true){
		if($stripTag){
			$string=strip_tags($string);
		}
		if($stripTag){
			$string=trim($string);
		}
		if($htmlspecialcharacter){
			$string=htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}
		if($mysql_real_escape){
			$string=mysqli_real_escape_string($this->dbcon,$string);
		}
		return $string;
	}
	
	//Check sqlinjection
	public function checkSqlInjection($string='',$mysql_real_escape=true){		
		if($mysql_real_escape){
			$string=mysqli_real_escape_string($this->dbcon,$string);
		}
		return $string;
	}
		
	//Clean xss
	function xss_clean($data){
		// Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }while ($old_data !== $data);
        // we are done...
        return $data;
	}
	
	
	//Genrate Key for url passing
	public function keyMaker($id){ 
		//generate the secret key anyway you like. It could be a simple string like in this example or a database 
		//look up of info unique to the user or id. It could include date/time to timeout keys. 
		$secretkey='1HutysK98UuuhDasdfafdCrackThisBeeeeaaaatchkHgjsheIHFH44fheo1FhHEfo2oe6fifhkhs'; 
		$key=md5($id.$secretkey); 
		return $key; 
	} 

	//destructor 
	public function __destruct(){
		//mysqli_close($this->dbcon);						
	}	
	
	public function getDataFromTable($tblName, $fldName,$optCondition){
		//echo "select " . $fldName . " from " . $tblName . " where " . $optCondition;exit;
		$defaultVal="";		
		if(trim($optCondition) != ""){
			$condition = $optCondition ;
		}else{
			$condition = "";
		}		
		$rs = mysqli_query($this->dbcon,"select " . $fldName . " from " . $tblName . " where " . $condition);
		if( (!($rs)) || (!($rec=mysqli_fetch_array($rs))) ){						
			return $defaultVal;
		}else if(is_null($rec[0])){			
			return $defaultVal;
		}else{		
			return $rec[0];
		}
	}
	
	
	########################################################################
	########################## INSERTINTOTABLE##############################
	########################################################################
	public function insertToTable($tblName, $string){
		$rs= mysqli_query($this->dbcon,"INSERT INTO " . $tblName . " VALUES(". $string.")");
		if($rs){
			$lastId=mysqli_insert_id($this->dbcon);
			return $lastId;
		}else{
			return 0;
		}
	}
	
	#######################################################################
	########################## INSERTSET  #################################
	#######################################################################
	public function insertSet($tblName,$string){		
		//echo "INSERT INTO  " . $tblName . " SET " .  $string; //exit;
		$rs= mysqli_query($this->dbcon,"INSERT INTO  " . $tblName . " SET " .  $string);
		if($rs){
			$lastId=mysqli_insert_id($this->dbcon);
			return $lastId;
		}else{
			return 0;
		}
	}
	
	#######################################################################
	########################## DELETEFROMTABLE#############################
	#######################################################################
	public function deleteFromTable($tblName, $condition){	
		if(trim($condition) != ""){
			$condition = " WHERE " . $condition;
		}else{
			$condition = "";
		}
		$rs= mysqli_query($this->dbcon,"DELETE FROM " . $tblName . $condition);
	}
	
	#######################################################################
	########################## fetchDistinct###############################
	#######################################################################
	function fetchDistinct($tblName,$distinctname,$optCondition="",$optorder="",$optlimit="",$optorderType="ASC"){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		if(trim($optlimit) != ""){
			$limit = " ".$optlimit;
		}else{
			$limit = "";
		}		
		if(trim($optorder) != ""){
			$sql="SELECT distinct(".$distinctname.") FROM " . $tblName . $condition ." ORDER BY ". $optorder." ".$optorderType. $limit;
		}else{
						$sql="SELECT distinct(".$distinctname.") FROM " . $tblName . $condition. $limit;
		}
		//echo $sql;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}
	
	#######################################################################
	########################## UPDATETABLE#################################
	#######################################################################
	public function updateTable($tblName,$string, $condition){
		$condition = " WHERE " . $condition;
		$sql="UPDATE " . $tblName . " SET " .  $string . $condition;
		//echo $sql;exit;
		$rs= mysqli_query($this->dbcon,$sql);
	}
	
	#######################################################################
	########################## AUTOINCREMENT ##############################
	#######################################################################
	public function autoIncrement($tblName,$string, $condition){
		$query_next = mysqli_query($this->dbcon,"SHOW TABLE STATUS LIKE '". $tblName."'");
		$row_next = mysqli_fetch_assoc($query_next);
		$next_id = $row_next[Auto_increment] ;//exit;
		return $next_id;
	}
	
	#######################################################################
	########################## FETCH ######################################
	#######################################################################
	public function fetch($tblName,$optCondition="",$optorder="",$optlimit="",$optorderType="ASC"){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		if(trim($optlimit) != ""){
			$limit = " ".$optlimit;
		}else{
			$limit = "";
		}
		if(trim($optorder) != ""){
			$sql="SELECT * FROM " . $tblName . $condition ." ORDER BY ". $optorder." ".$optorderType. $limit;
		}else{
			$sql="SELECT * FROM " . $tblName . $condition. $limit;
		}
	    //echo $sql;//exit;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
	
	################################################################
	######################FETCHING ROWS#############################
	################################################################	
	function fetchOrder($tblName,$optCondition="",$orderby="",$field="",$groupby=""){
		if($field==""){
			$sql = "SELECT * FROM ".$tblName;
		}else{
			$sql = "SELECT ".$field." FROM ".$tblName;
		}		
		if(trim($optCondition) != ""){
			$sql = $sql." WHERE " . $optCondition;
		}
		if($groupby != ""){
			$sql = $sql." group by " . $groupby;
		}
		if(trim($orderby) != "" ){
			$sql = $sql." order by " . $orderby;
		}		
		//echo $sql;//exit;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}  
	
	
	# With Limit
	function fetchDatatfrommultipleTable($tblName,$optCondition="",$orderby="",$field="",$optlimit="",$optorderType="ASC"){
		if($field==""){
			$sql = "SELECT * FROM ".$tblName;
		}else{
			$sql = "SELECT ".$field." FROM ".$tblName;
		}		
		if(trim($optCondition) != ""){
			$sql = $sql." WHERE " . $optCondition;
		}
		
		if(trim($orderby) != "" ){
			$sql = $sql." order by " . $orderby;
		}		
		
		if(trim($optorderType)!=""){
			$sql=$sql." ".$optorderType;
		}
		if(trim($optlimit) != ""){
			$sql = $sql.$optlimit;
		}
		
		
		//echo $sql;exit;
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{
			$default_val=array();
			return $default_val;
		}
	}
	  
	##############################################################
	############### Simple Query #################################
	##############################################################
	public function simpleQuery($sql){	
	    //echo $sql; //exit;			
		$result=mysqli_query($this->dbcon,$sql);					
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
	
	##############################################################
	################ FETCHKEYVALUE ###############################
	##############################################################
	public function fetchKeyValue($tblName,$keycol='id',$valuecol='id',$cond='') {
		$result_array=array();
		if(trim($cond) != ""){
			$condition = " WHERE ".$cond;
		}else{
			$condition='';	
		}
		$sql="SELECT * FROM " . $tblName . $condition;			
		$result = mysqli_query($this->dbcon,$sql);	
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){		
			$key=$row[$keycol];	
			if(trim($row[$valuecol])!=''){
				$result_array[$key] = $row[$valuecol];
			}
		}			
		return $result_array;	
	}
	
	##############################################################
	################ FETCHSINGLECOLUMN  ##########################
	##############################################################
	public function fetchSingleColumn($tblName,$column_name='id',$optCondition="",$optorder="",$optlimit="",$optorderType="ASC"){
		$result_array=array();
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
		if(trim($optlimit) != ""){
			$limit = " ".$optlimit;
		}else{
			$limit = "";
		}
		if(trim($optorder) != ""){
			$sql="SELECT distinct(".$column_name.") FROM " . $tblName . $condition ." ORDER BY ". $optorder." ".$optorderType. $limit;
		}else{
			$sql="SELECT distinct(".$column_name.") FROM " . $tblName . $condition. $limit;
		}	
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row[$column_name];
		}		
		return $result_array;		
	}	
	
	#############################################################
	################ LEFTJOIN  ##################################
	##############################################################
	function leftJoin($tblName1,$tblName2,$tbl1Param,$tbl2Param,$optCondition="",$optDistinct="id"){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}				
		$sql="SELECT DISTINCT ". $tblName1.".".$optDistinct." FROM " . $tblName1 . " LEFT JOIN ". $tblName2 ." ON ".$tblName1.".".$tbl1Param."=".$tblName2.".".$tbl2Param. $condition;
		$result = mysqli_query($this->dbcon,$sql);		
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
	
	##############################################################
	################ LEFTJOINCOUNT  ##############################
	##############################################################
	function leftJoinCount($tblName1,$tblName2,$tbl1Param,$tbl2Param,$optCondition=""){
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}		
	  $sql="SELECT DISTINCT ". $tblName1.".id FROM " . $tblName1 . " LEFT JOIN ". $tblName2 ." ON ".$tblName1.".".$tbl1Param."=".$tblName2.".".$tbl2Param. $condition;
	 	$result = mysqli_query($this->dbcon,$sql);
		return $result->num_rows;	
	}
		
	#############################################################
	################ FETCHMULTICOLUMNS ##########################
	#############################################################
	function fetchMultiColumns($tblName,$field,$optCondition=""){
		if(trim($optCondition) != ""){
			$sql = "SELECT ".$field." from ".$tblName." WHERE " . $optCondition;
		}else{
			$sql = "SELECT ".$field." from ".$tblName;
		}		
		$result = mysqli_query($this->dbcon,$sql);
		if(!$result){
			trigger_error("Problem selecting data");
		}
		while($row = mysqli_fetch_assoc($result)){
			$result_array[] = $row;
		}
		if(count($result_array)>0){
			return $result_array;	
		}else{			
			return array();
		}
	}
		
	#############################################################
	################ STRRECORDID ################################
	#############################################################
	function fetchSingle($tblName,$field='*',$optCondition=""){
		if(trim($optCondition)!= ""){
			$sql = "SELECT ".$field." from ".$tblName." WHERE " . $optCondition;
		}else{
			$sql = "SELECT ".$field." from ".$tblName;
		}			
	    //echo $sql;//exit;
		$result = mysqli_query($this->dbcon,$sql);
		
		return mysqli_fetch_assoc($result);
	}
	
	#############################################################
	################ COUNTROWS ##################################
	#############################################################
	function countRows($tblName,$optCondition="") {
		if(trim($optCondition) != ""){
			$condition = " WHERE " . $optCondition;
		}else{
			$condition = "";
		}
	    $sql="SELECT * FROM " . $tblName . $condition;
		//echo $sql;exit;			
		$result = mysqli_query($this->dbcon,$sql);		
		return $result->num_rows;
	}
	
	#############################################################
	################ formatMyDateTime############################
	#############################################################
	public function formatMyDateTime($a_date, $a_format, $is_time_stamp = 0, $a_default_value = ""){
		if(is_null($a_date)){
			return($a_default_value);
		}else{
			if($is_time_stamp == 1){
				//--- supplied date time is a TimeStamp, so no conversion required
				$tmpdt_stamp = $a_date;
			}else{
				//--- supplied date time is not a TimeStamp, but a string
				$tmpdt_stamp = strtotime($a_date);
			}
			return(date($a_format, $tmpdt_stamp));
		}
	}
		
	#############################################################
	############################ cut ############################
	#############################################################
	public function cut($string, $max_length){
		if(strlen($string) > $max_length){
			$string = substr($string, 0, $max_length);
			$pos = strrpos($string, " ");
			if($pos === false) {
				return substr($string, 0, $max_length)."...";
			}
				return substr($string, 0, $pos)."...";
		}else{
			return $string;
		}
	}	
	
	#############################################################
	############################ cut ############################
	#############################################################
	  public function age($birthday){
			list($year,$month,$day) = explode("-",$birthday);
			$year_diff  = date("Y") - $year;
			$month_diff = date("m") - $month;
			$day_diff   = date("d") - $day;
			if ($day_diff < 0 || $month_diff < 0)
			  $year_diff--;
			return $year_diff;
	  }  
		  
	#############################################################
	######################## createRandomPassword ###############
	#############################################################
	public function createRandomPassword(){
		$chars = "abcdefghijkmnopqrstuvwxyz023456789ABCDEWFGHJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;		
		while ($i <= 6){
			$num = rand() % 70;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		 }
		return $pass;
	} 
	 
	#############################################################
	######################## get_server #########################
	#############################################################
	public function getServer() {
		$protocol = 'http';
		if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
			$protocol = 'https';
		}
		$host = $_SERVER['HTTP_HOST'];
		$baseUrl = $protocol . '://' . $host;
		if (substr($baseUrl, -1)=='/') {
			$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
		}
		return $baseUrl;
	}
	
	#############################################################
	######################## createStars#########################
	#############################################################
	public function createStars($green,$gimagepath,$wimagepath) {  
		$white=5-$green;													
		for($i=1;$i<=$green;$i++){
			echo "<img src=$gimagepath width='16' height='16' align='top'>";
		}
		for($i=1;$i<=$white;$i++){
			echo "<img src=$wimagepath width='16' height='16' align='top'>";
		}
	}
	
	#############################################################
	############ Convert a Numeric Number into words ############
	#############################################################
	function trim_all( $str , $what = NULL , $with = ' ' ){
		if( $what === NULL ){
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
	
	function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
		return $str;
	}
	
	
	function number_to_word( $num = '' ){
		$num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) ){
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
           
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
           
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
           
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
           
            foreach( $num_levels as $num_part ){
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
               
                if( $tens < 20 ){
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }else{
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }
           
            $commas = count( $words );
            if( $commas > 1 ){
                $commas = $commas - 1;
            }
           
            $words  = implode( ', ' , $words );
           
            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas ){
                $words  = $this->str_replace_last( ',' , ' and' , $words );
            }
            return $words;
        }else if( ! ( ( int ) $num ) ){
            return 'Zero';
        }
        return '';
    }
	#############################################################
	############ Convert a Numeric Number into words#############
	#############################################################
	
	
	function humanTiming ($time){
		$time = time() - $time; // to get the time since that moment
		$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
		);
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
			}
		}
		
		
		function seoUrl($string) {
		//Lower case everything
    	$string = strtolower($string);
    	//Make alphanumeric (removes all other characters)
    	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
   		//Clean up multiple dashes or whitespaces
    	$string = preg_replace("/[\s-]+/", " ", $string);
    	//Convert whitespaces and underscore to dash
    	$string = preg_replace("/[\s_]/", "-", $string);
    	return $string;
	}
	
	
	
	//Create a sub domain function
	function create_subdomain($subDomain,$cPanelUser,$cPanelPass,$rootDomain){
	 //Go daddy server
	//$buildRequest = "/frontend/x3/subdomain/doadddomain.html?rootdomain=" . $rootDomain . "&domain=" . $subDomain . "&dir=public_html/".$rootDomain."/". $subDomain;
	//Site Ground Server
	$buildRequest = "/frontend/Crystal/subdomain/doadddomain.html?domain=" . $subDomain . "&rootdomain=" . $rootDomain . "&dir=public_html/".$rootDomain."/".$subDomain;



	$openSocket = fsockopen('localhost',2082);
	if(!$openSocket) {
		return "Socket error";
		exit();
	}
	$authString = $cPanelUser . ":" . $cPanelPass;
	$authPass = base64_encode($authString);
	$buildHeaders  = "GET " . $buildRequest ."\r\n";
	$buildHeaders .= "HTTP/1.0\r\n";
	$buildHeaders .= "Host:localhost\r\n";
	$buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
	$buildHeaders .= "\r\n";

	fputs($openSocket, $buildHeaders);
	while(!feof($openSocket)){
		fgets($openSocket,128);
	}
	fclose($openSocket);

	$newDomain = "http://" . $subDomain . "." . $rootDomain . "/";
	}

	//Delete a sub domain function
	function delete_subdomain($subDomain,$cPanelUser,$cPanelPass,$rootDomain){
	//Go daddy Server
	//$buildRequest = "/frontend/x3/subdomain/dodeldomain.html?domain=" . $subDomain . "_" . $rootDomain;
	//Site Ground Server
	$buildRequest = "/frontend/Crystal/subdomain/dodeldomain.html?domain=".$subDomain."_".$rootDomain."&domaindisplay=".$subDomain.$rootDomain;


	$openSocket = fsockopen('localhost',2082);
	if(!$openSocket) {
		return "Socket error";
		exit();
	}

	$authString = $cPanelUser . ":" . $cPanelPass;
	$authPass = base64_encode($authString);
	$buildHeaders  = "GET " . $buildRequest ."\r\n";
	$buildHeaders .= "HTTP/1.0\r\n";
	$buildHeaders .= "Host:localhost\r\n";
	$buildHeaders .= "Authorization: Basic " . $authPass . "\r\n";
	$buildHeaders .= "\r\n";

	fputs($openSocket, $buildHeaders);
	while(!feof($openSocket)){
		fgets($openSocket,128);
	}
	fclose($openSocket);
	//Change the primary folder name.Here it is and.democrat
	//$passToShell = "rm -rf /home/" . $cPanelUser . "/public_html/and.democrat/" . $subDomain;
	$passToShell = "rm -rf /home/" . $cPanelUser . "/public_html/".$rootDomain."/". $subDomain;
	system($passToShell);
	}
	
	
	// Login process
	public function checkTokenSecurity($chef_id,$token){
		//echo "SELECT * FROM chef_token WHERE chef_id='$chef_id' AND token='$token'"; exit;
		$result = mysqli_query($this->dbcon,"SELECT * FROM chef_token WHERE chef_id = '$chef_id' AND token = '$token'");
		$admin_data=mysqli_fetch_assoc($result);
		//echo $result->num_rows;exit;
		if ($result->num_rows==1){
			return 1;
		}else{
			return 0;
		}
	}
	
	
}
?>