<!DOCTYPE html>
<!--TODO: 
FIXED - does username /password get set to new sess when I stop debug output?
FIXED - new shipping record not being inputed - found on logged in customer adding new address - ok on registering cutomer - 
FIXED - error - shipping to customer not shipping name found on no loggin order and save while login order
FIXED - handle many errors. give user feedback on failed input, reset page.
FIXED -much input cleaning and verifying,
FIXED - check before inserting duplicate addresses per customer
0 - I need a CSRF prevention token on order post-back. 
FIXED - prevent form resubmit in back button tap - 
1 - new error from fixing above - leaves current page on index not new receipt tab now
FIXED - need to see if there are products ordered, if not redirect index to prevent second back button error access
2 - js realtime user feedback
-->
<html lang="en-US">
<head><title>Order Details</title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="cartAll.css">
<script>

function init(){
	var textInputs = document.querySelectorAll("input[type=text]");
	console.log("textInputs="+textInputs);
	for(var i=0;i<textInputs.length;i++){
		console.log("textInputs[i]="+textInputs[i]);
		textInputs[i].addEventListener("keypress", textChange, false);
	}
}
document.addEventListener("DOMContentLoaded",init, false); 

function textChange(event){
	var radios = document.getElementsByName("adOpt");
	console.log("In textChange radios ="+radios);
	for(var i=0; i<radios.length; i++){
		console.log("In textChange radios[i] ="+radios[i]);
		if(radios[i].checked){
			console.log("In textChange this was checked ="+radios[i]);
			radios[i].checked=false;
		}
	}
	radioChange();
}

var divs=new Array();
function radioChange(event){
	var checkedRadioValue=0;
	if(event){
	var radio = event.target;
	if(radio.checked){
		checkedRadioValue=radio.value;
		console.log("the checked radio = "+radio+" has radio.value = "+radio.value);
		if(divs[radio.value]){
			var span = radio.parentNode.parentNode;
			var result = span.appendChild(divs[radio.value]);
			document.getElementById(radio.value).checked= true;
		}
	}
	} else {
		checkedRadioValue=1;
	}
	var radios = document.getElementsByName("adOpt");
	for(var i =0; i<radios.length;i++){
		if ((radios[i].value !== checkedRadioValue) && document.getElementById(radios[i].value)){
			divs[radios[i].value]=document.getElementById(radios[i].value);
			divs[radios[i].value].id=document.getElementById(radios[i].value).id;			
			console.log("radios[i].value:"+radios[i].value+"!=="+checkedRadioValue+":checkedRadioValue");
			if(document.getElementById(radios[i].value)&&checkedRadioValue){
				console.log("delete " + radios[i].value);
				document.getElementById(radios[i].value).parentNode.removeChild(document.getElementById(radios[i].value));
			}
		}
	}
}
document.addEventListener ("RadioStateChange", radioChange, false)
</script>
</head>
<body>
<?php 

//######## Force HTTPS
$use_sts = true;
// is sets HTTPS to 'off' for non-SSL requests?
if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=31536000');
} elseif ($use_sts) {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
    // somebody's being funny, we're in cleartext stll
	exit();
}
//Set no caching
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
//header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//######create form elts
require "top.php";
$formStart=<<<EOD
<form action="order.php" accept-charset="utf-8" method="post" id="custInfo">
  <div class="form-all">
    <ul>
	
EOD;
$formTitle="\n\t\t\t<li><h1 class='form-header' id='orderdetails'>Order Details</h1></li>";

$formOr=<<<EOD
		<li class="form-line">
			<label class="form-label-top" id="or"> - OR - </label><div></div>
		</li>
		
EOD;
$formName=<<<EOD
		<li class="form-line">
			<label class="form-label-top" for="name"> Your Information </label><div>
				<label class="form-sub-label"> First Name :</label>
				<input type="text" size="10" id="first" name="first"/>
				<label class="form-sub-label"> Last Name :</label>
				<input type="text" size="25" id="last" name="last"/>
			</div><div>
				<label class="form-sub-label"> Email :</label>
				<input type="text" size="35" id="email" name="email"/>
			</div>
		</li>
		
EOD;

$formAddress=<<<EOD
		<li class="form-line">
			<label class="form-label-top" for="contact"> Shipping Address </label><div>
				<label class="form-sub-label"> First Name :</label>
				<input type="text" size="10" id="first" name="adFirst"/>
				<label class="form-sub-label"> Last Name :</label>
				<input type="text" size="25" id="last" name="adLast"/>
			</div><div>
				<label class="form-sub-label"> Street Address :</label>
				<input type="text" size="50" id="street" name="street"/>
			</div>
			<div>
				<label class="form-sub-label"> City :</label>
				<input type="text" size="15" id="city" name="city"/>
				<label class="form-sub-label"> State :</label>
					<select name="state">
						<option value="AL">Alabama</option>
						<option value="AK">Alaska</option>
						<option value="AZ">Arizona</option>
						<option value="AR">Arkansas</option>
						<option value="CA">California</option>
						<option value="CO">Colorado</option>
						<option value="CT">Connecticut</option>
						<option value="DE">Delaware</option>
						<option value="DC">DC</option>
						<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
						<option value="HI">Hawaii</option>
						<option value="ID">Idaho</option>
						<option value="IL">Illinois</option>
						<option value="IN">Indiana</option>
						<option value="IA">Iowa</option>
						<option value="KS">Kansas</option>
						<option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option>
						<option value="ME">Maine</option>
						<option value="MD">Maryland</option>
						<option value="MA">Massachusetts</option>
						<option value="MI">Michigan</option>
						<option value="MN">Minnesota</option>
						<option value="MS">Mississippi</option>
						<option value="MO">Missouri</option>
						<option value="MT">Montana</option>
						<option value="NE">Nebraska</option>
						<option value="NV">Nevada</option>
						<option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option>
						<option value="NM">New Mexico</option>
						<option value="NY">New York</option>
						<option value="NC">North Carolina</option>
						<option value="ND">North Dakota</option>
						<option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option>
						<option value="OR">Oregon</option>
						<option value="PA">Pennsylvania</option>
						<option value="RI">Rhode Island</option>
						<option value="SC">South Carolina</option>
						<option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option>
						<option value="TX">Texas</option>
						<option value="UT">Utah</option>
						<option value="VT">Vermont</option>
						<option value="VA">Virginia</option>
						<option value="WA">Washington</option>
						<option value="WV">West Virginia</option>
						<option value="WI">Wisconsin</option>
						<option value="WY">Wyoming</option>
					</select>
				<label class="form-sub-label"> Zip Code :</label>
				<input type="text" size="6" id="zip" name="zip"/>
			</div><div>
				<label class="form-sub-label">Contact Phone :</label>
				<input type="text" size="12" id="phone" name="phone"/>
			</div>
		</li>
		
EOD;
$formNewUser=<<<EOD
		<li class="form-line">
			<label class="form-label-top" for="name"> Save Me For Later</label><div>
			</div>
				<label class="form-sub-label"> * Optional</label>
			<div>
				<label class="form-sub-label"> Username :</label>
				<input type="text" size="20" id="newuser" name="newuser"/>*&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="form-sub-label"> Password :</label>
				<input type="password" size="20" id="newpass" name="newpass"/>*
			</div>
		</li>	
		
EOD;
$formButtons=<<<EOD
		<li class="form-line" data-type="control_button" id="id_13">
			<div class="form-bottom-button-wrapper">
				<input type="submit" id="backbutton" name="backbutton" value="<- Back to Cart" formaction="cart.php" />
				<input type="submit" id="submitbutton" value="Submit Order ->"/>
			</div>
		</li>
		
EOD;
$formEnd=<<<EOD
	</ul>
  </div>
</form>

EOD;
$formAlert="";
//#############end form elts

//#############functions
function capitalize($dataStr){
$matched=preg_match('/^[a-z]|[0-9\s][a-z]/', $dataStr, $matches);
if ($matched)
	for ($i=0;$i<count($matches);$i++){
		$mpos=strpos($dataStr, $matches[$i]);
		if(strlen($matches[$i])>1){	
			$mpos++;
			$cap=strtoupper(str_split($matches[$i])[1]);
		} else {
			$cap=strtoupper($matches[$i]);
		}
		$dataStr[$mpos]=$cap;
	}
	return $dataStr;
}

function untaint($data, $num=FALSE,  $tic=FALSE){
	//echo "test 1: " . $data;
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	$data=(!$tic)?str_replace("'", "", $data):$data;
	if(($num && !is_numeric($data))||(is_numeric($data) && $data <1)){//to do: add param $max=100
		$data=1;
	} 
	//echo "test 2: " . $data;
	return $data;
}

function verify($data,$type){//regex data by type, return data | 1
	global $formEnd;
	if(strlen($data)>512||strlen($data)<2){
		return 1;
	}
	if ($type==="first" || $type==="last" || $type=="city"){
		#echo" name/city data=$data|";
		$data=untaint($data, FALSE, TRUE);
		$data=capitalize($data);
		$matched=preg_match('/[A-Z]*[a-z]*[\s\'\(\)\-\.0-9]{0,3}[a-zA-Z]*/', $data, $matches);
		$m="";
		foreach ($matches as $i){
			$m.=$i;
		}
		#echo" name/city data=$m|";
		return (strlen($data)>2)?$m:1;
		
	} else if ($type === "phone"){
		#echo" phone data=$data|";
		$matched7=preg_match('/([0-9]{7})/', $data, $matches7);
		$matched10=preg_match('/([0-9]{10})/', $data, $matches10);
		$matched3=preg_match('/([0-9]{3})/', $data, $matches3);
		$ph="";
		foreach($matches3 as $i){
			$ph.="$i-";
		}
		#echo "ph1=$ph";
		if(strlen($ph)==8){
			if ($matched10){
				$ph=substr($matches10[0], 0, 3)."-".substr($matches10[0], 3, 3)."-".substr($matches10[0], 6);
			} else {
				$ph.=untaint(substr($data, -4), TRUE);
			}
		} else if (strlen($ph==4)){
			if ($matched7){
				$ph.=substr($matches7[0], 0, 3)."-".substr($matches7[0], 3);
			}
		}
		#echo "ph2=$ph strlen(ph)=".strlen($ph);
		$accepted=(strlen($ph)==12)?TRUE:FALSE;
		return ($accepted)? $ph :1;
	} else if ($type==="email"){
		#echo" email data=$data|";
		$data=untaint($data);
		$matched=preg_match('#[A-Za-z0-9_]{3,50}[@][A-Za-z0-9_\.\\\/]{2,30}[\.][A-Za-z]{2,5}#', $data);
		#echo" email2 data=$data, matched=$matched|";
		return ($matched)?$data:1;//or isset($matched)?
	}else if ($type==="street"){//to do- street line 1 regex
		#echo" street data=$data|";
		$data=capitalize($data);
		$data=untaint($data, FALSE, TRUE);
		#echo" street2 data=$data|";
		return $data;
	}else if ($type==="state"){
		return (preg_match('/[A-Z]{2}/', $data))? $data :1;
	}else if ($type==="zip"){
		#echo" zip data=$data";
		if (preg_match('/[0-9]{5}[\s\-]{0,3}([0-9]{4})?/', $data)){
			$data=str_replace("-","",str_replace(" ","",$data));
			#echo"zip data2=$data|";
			if(strlen($data)==5||strlen($data)==9){
				return $data;
			}
		}
		return 1;
	}
	else if ($type==="newuser"||$type==="username"|| $type==="newpass"||$type==="password"){
		$confirmned=untaint($data);
		#echo" confirmned=$confirmned =? data=$data|";
		return ($confirmned==$data)?$confirmned:1;
	}
	else if ($type==="sessID"){
		return(preg_match('/^[A-Z0-9a-z,\-]{22,40}/', $data))?$data:1;
	} else {
		$formEnd.=" Error passing type $type to verify";
		return untaint($data);
	}
}
function handleInputError($strInputFld){
	echo '<script>alert("Error on entering '+$strInputFld+'. \nPlease try again.")</script>';
	header("Refresh:0.1");
}
//#######end functions

//######set vars for all page versions
$dsn="mysql:host=localhost;dbname=shopping_clean";
$username=FALSE;
$password=FALSE;
if(isset($_POST['username'])&&isset($_POST['password'])&&$_POST['username']!==''&&$_POST['password']!=''){
	$username=verify($_POST['username'], 'username');
	$password=verify($_POST['password'], 'password');
} else if(isset($_POST['newuser'])&&isset($_POST['newpass'])&&$_POST['newuser']!==""&&$_POST['newpass']!=""){
	$username=verify($_POST['newuser'], 'newuser');
	$password=verify($_POST['newpass'], 'newpass');
} else if(isset($_SESSION['username'])&&isset($_SESSION['password']) && $_SESSION['username']!=="" && $_SESSION['password']!==""){
	$username=$_SESSION['username'];
	$password=$_SESSION['password'];
}
if ($username==1 || $password==1){
$username=FALSE;
$password=FALSE;
}
//#######if page sumitted with all order data fields START
if(((isset($_POST['first'])&&isset($_POST['last'])&&isset($_POST['email']))||((isset($_POST['adFirst']))&&(isset($_POST['adLast']))))&&isset($_POST['phone'])&&isset($_POST['street'])&&isset($_POST['city'])&&isset($_POST['state'])&&isset($_POST['zip'])){
	$oldSess=session_ID();
	$customerID=-1;
	if(isset($_POST['first'])&&isset($_POST['last'])||(isset($_POST['adFirst']))&&(isset($_POST['adLast']))){//HERE!!!
	//if posting first and last name it's a new customer with new address or logged  customer in with new address
		if(isset($_SESSION['username'])&&isset($_SESSION['password'])){//if logged in with new address
			$pdoSCID=new PDO($dsn, "root", "");
			$selectCID=$pdoSCID->prepare("SELECT id FROM customer WHERE username=:usn AND password=:psw LIMIT 1;");
			$selectCID->bindValue(':usn',$_SESSION['username']);
			$selectCID->bindValue(':psw',$_SESSION['password']);
			$resCID=$selectCID->execute();
			if(!$resCID){
				die('very painfully');//handle
			} else {
				$customerID=$selectCID->fetch(PDO::FETCH_ASSOC)['id'];
				//echo '$_SESSION["username"]'.$_SESSION['username'].'$_SESSION["password"]'.$_SESSION['password'].'$customerID'.$customerID;//test
			}
		} else {//if new customer + new address
			if($username && $password){//customer wants remembered
				if(isset($_POST['email'])){
					$f=verify($_POST['first'], 'first');
					$l=verify($_POST['last'], 'last');
					$e=verify($_POST['email'], 'email');
					$emTest=$_POST['email'];
					//echo "f=$f, l=$l, e=$e on $emTest, username=$username, password=$password";//test
					$pdoIC=new PDO($dsn, "root","");//TODO: add username & password
					$insertCustomer=$pdoIC->prepare("
					INSERT INTO customer (fname, lname, email, username, password) SELECT :fname, :lname, :email, :username, :password FROM dual WHERE NOT EXISTS(SELECT * FROM customer WHERE (username=:user AND password=:pass) OR email=:em);");
						//NOTE: mysql conditional on insert - do da dual
					$resultIC=$insertCustomer->execute(array(//this works if all bindings can be interprited as strings
						':fname' => $f,
						':lname' => $l,
						':email' => $e,
						':username' => $username,
						':password' => $password,
						':user'=>$username,
						':pass'=>$password,
						':em'=>$e
					));
					//echo "first|".$f."|last|".$l."|email|".$e."|uname|".$username."|ps|".$password;
					if ($resultIC){
						$lastICID=$pdoIC->lastInsertId('id');
						$customerID=$lastICID;
					}
					//echo '$customerID:'.$customerID;
					if(!$customerID){//either already reg email OR username/password combo taken
						$pdoDupEm=new PDO($dsn, "root","");//TODO: add username & password
						$selectDupEmail=$pdoDupEm->prepare("SELECT * FROM customer WHERE email=:email");
						$selectDupEmail->bindValue(':email',verify($_POST['email'], 'email'));
						$selectDupEmail->execute();
						$emailIssue=$selectDupEmail->fetch(PDO::FETCH_ASSOC)['email'];
						if(!$emailIssue){//then it's a username/password combo taken issue
							echo "<script>alert('Sorry - this username and password combination is taken. Please try again or login.');</script>";
						} else{//if dup $emailIssue
							echo "<script>alert('Email ".verify($_POST['email'], 'email')." is already registered. Please login.');</script>";
						}
						header("Refresh:0.1");
					}
				} else {//left email blank
					echo "<script>alert('Email is a required field.');</script>";
					$customerID=0;
				}
			} else {//customer didn't post username/password & not logged in - one time order
				$customerID=-1;//-1=default customer for one time order
			}
		}//end email / login issue feedbak with $customerID set | 0 on fail
	}//end received posts for first & last for new customer with new address or logged  customer in with new address
	if($customerID!==0){//if $customerID is found |created for new customer with new address or logged  customer in with new address|left "NULL" for one time order:  insert shipping address if we don't already have that shipping address and regexes pass 
		$cleanFirst=(isset($_POST['first']))?(strlen($_POST['first'])>1)?verify($_POST['first'], 'first'): verify($_POST['adFirst'], 'first'): verify($_POST['adFirst'], 'first');
		//echo "|||cleanFirst=$cleanFirst|";
		if ($cleanFirst==1){
			handleInputError("a first name field");
		}
		$cleanLast=(isset($_POST['last']))?verify($_POST['last'], 'last'): verify($_POST['adLast'], 'last');
		//echo "|||cleanLast=$cleanLast|";
		if ($cleanLast==1){
			handleInputError("a last name field");
		}
		$cleanSt=verify($_POST['street'], 'street');	
		//echo "|||cleanSt=$cleanSt|";
		if ($cleanSt==1){
			handleInputError("street address");
		}
		$cleanCity=verify($_POST['city'], 'city');
		//echo "|||cleanCity=$cleanCity|";
		if ($cleanCity==1){
			handleInputError("city name");
		}	
		$cleanState=verify($_POST['state'], 'state');
		//echo "|||cleanState=$cleanState|";
		if ($cleanState==1){
			handleInputError("state");
		}	
		$cleanZip=verify($_POST['zip'], 'zip');
		//echo "|||cleanZip=$cleanZip|";
		if ($cleanZip==1){
			handleInputError("zip code");
		}
		$cleanPhone=verify($_POST['phone'], 'phone');
		//echo "|||cleanPhone=$cleanPhone|";
		if ($cleanPhone==1){
			handleInputError("phone number");
		}
		if ($cleanFirst!=1&&$cleanLast!=1&&$cleanSt!=1&&$cleanCity!=1&&$cleanState!=1&&$cleanZip!=1&&$cleanPhone!=1){//if all input good
			$insertNew=TRUE;
			$pdoSSD=new PDO($dsn, "root");#TODO
			$SelectShipDup=$pdoSSD->prepare("SELECT id FROM shipping WHERE customer_id=:CID AND ship_fname=:f AND ship_lname=:l AND street=:s AND city=:c AND zip=:z LIMIT 1;");
			$SelectShipDup->bindValue(':CID', $customerID);
			$SelectShipDup->bindValue(':f',$cleanFirst);
			$SelectShipDup->bindValue(':l',$cleanLast);
			$SelectShipDup->bindValue(':s', $cleanSt);
			$SelectShipDup->bindValue(':c', $cleanCity);		
			$SelectShipDup->bindValue(':z', $cleanZip);
			$resSSD=$SelectShipDup->execute();
			if($resSSD){
				$shipDup=$SelectShipDup->fetch(PDO::FETCH_ASSOC);
				if (isset($shipDup['id'])){
					$insertNew=FALSE;
					$shippingID=$shipDup['id'];
				}			
			} //else {insert new address on unspec query error that may or may not be dup}
			if($insertNew){
				$pdo3=new PDO($dsn, "root","");//TODO: add username & password
				$insertContact=$pdo3->prepare("INSERT INTO shipping (customer_id, ship_fname, ship_lname, street, city, state, zip, phone) VALUES (:custID, :sfname ,:slname ,:street, :city, :state, :zip, :phone);");
				$insertContact->bindValue(':custID', $customerID);
				$insertContact->bindValue(':sfname',$cleanFirst);
				$insertContact->bindValue(':slname',$cleanLast);
				$insertContact->bindValue(':street', $cleanSt);
				$insertContact->bindValue(':city', $cleanCity);
				$insertContact->bindValue(':state', $cleanState);
				$insertContact->bindValue(':zip', $cleanZip);
				$insertContact->bindValue(':phone', $cleanPhone);
				$resIC=$insertContact->execute();
				$shippingID=($pdo3->lastInsertId())?$pdo3->lastInsertId():0;//set $shippingID
				if(!$shippingID || !$resIC){
					die ("<script>alert('Error on inserting shipping address. \nPlease try again')</script>");
					header("Refresh:0.1");
				}
			}
		} else {
			echo '<script>alert("Error on entering order. \nPlease try again.")</script>';
			header("Refresh:0.1");
		}
	} else {
		if (isset($_POST['adOpt'])){
			$shippingID=$_POST['adOpt'];
		} else {
			header('Refresh:0');//unspec error
		}
	}//END error on retreiving above | logged in customer with existing shipping address - $shippingID now set or false
	if ($customerID!==0 && isset($shippingID) && $shippingID){//if all found or created both $customerID && $shippingID
		//$formEnd.= "<script>alert('customerID=$customerID && shippingID=$shippingID');</script>";//test
		$pdoSQP=new PDO($dsn, "root","");//TODO: add username & password
		$selectQtyPID=$pdoSQP->prepare("SELECT qty_ordered, p.id, p.item FROM Cart AS c JOIN Products AS p ON c.product_id = p.id WHERE session_id=:sessid;");
		$selectQtyPID->bindParam(':sessid',verify(session_id(), "sessID"), PDO::PARAM_STR, 32);//check len
		$resultQtyPID=$selectQtyPID->execute();
		$insertedOrders=FALSE;
		$dialogQtyItem="";
		while($qp=$selectQtyPID->fetch(PDO::FETCH_ASSOC)){//go through qty & pid array and insert each product into orders
			$insertedOrders=TRUE;
			$pdoIO=new PDO($dsn, "root", "");
			$insertOrders=$pdoIO->prepare("INSERT INTO orders (product_id, qty, customer_ID, shipping_id, tm) VALUES (:pid, :qty, :cid, :sid, NOW());");
			$insertOrders->bindValue(':pid',$qp['id']);
			$insertOrders->bindValue(':qty',$qp['qty_ordered']);
			$insertOrders->bindValue(':cid',$customerID);
			$insertOrders->bindValue(':sid',$shippingID);
			$resultOrders=$insertOrders->execute();
			if(!$resultOrders){
				die("Product ". $qp['id']. " failed - please try later<br />");
			}else{
				$lastOrderId = $pdoIO->lastInsertId();
			}
		}//end while $qp
		if(!$insertedOrders){
			die("<script>alert('Error inserting orders - please confirm shopping cart and retry.')</script>");
			header('Location:cart.php');
		} else {
			//clear cart;
			$pdoDC=new PDO($dsn, "root", "");
			$deleteCart=$pdoDC->prepare("DELETE FROM cart WHERE session_id=:osess;");
			$deleteCart->bindParam(':osess',$oldSess);
			$resultCart=$deleteCart->execute();
			if(!$resultCart || $deleteCart->rowCount()<1){
				echo '<script>alert("Error removing items from shopping cart after order was placed. Verify your order and make sure not to reorder duplicate items.")</script>';
			}
			//empty session vars
			$_SESSION = array();
			//  also delete the session cookie.
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			//die i say
			session_destroy();
			//start new sess with same username and password vars
			session_start();
			if ($username && $password && $username != '' && $password != ''){
				$_SESSION["username"]=$username;
				$_SESSION["password"]=$password;
				//echo"\n<script>alert('" . session_id() ."' + ' = ' + document.cookie +' is set to: ".$_SESSION['username']."' +' '+ '".$_SESSION['password']."')</script>";//test
			}
			//post receipt sess info back to index to ensure fully processed.
			$addForms= "
			<form action='receipt.php' method='post' target='_blank' id='postreceipt'>
				<input type='hidden' name='oid' value='$lastOrderId'/>	
				<input type='submit' style='opacity:0;' />
			</form>		
			<form action='index.php' method='post' id='postindex'>
					<input type='submit' style='opacity:0;'/>
					<input type='hidden' name='os' value='$oldSess'/>
					<input type='hidden' name='un' value='$username'/>
					<input type='hidden' name='pw' value='$password'/>
			</form>
			<script>
				//get the order page out of the history, replace with index with no reshesh prompted

				window.addEventListener('popstate', function() {
					alert('replace me dammit');
					if(location.hash === '#G86F853HF98G86F853HF98') {
						history.replaceState(null, document.title, location.pathname);
						setTimeout(function(){
						   location.replace('https://localhost/cart/index.php');
						},0);
					}
				}, false);
				function sendData(){
					document.getElementById('postreceipt').submit();
					document.getElementById('postindex').submit();
					history.replaceState(null, document.title, 'https://localhost/cart/index.php');
					history.pushState(null, document.title, location.pathname);
				}
				document.addEventListener('DOMContentLoaded',sendData, false); 
			</script>
			";
			$noGoingBack="
			<script>
			//get the order page out of the history, replace with index with no reshesh prompted
			//history.replaceState(null, document.title, location.pathname+'#G86F853HF98G86F853HF98');//random string I can match for in hash ancor
			//alert('document.title='+document.title+'location.pathname'+location.pathname+'#G86F853HF98G86F853HF98');
			//history.pushState(null, document.title, location.pathname);
			window.addEventListener('popstate', function() {
				alert('replace me dammit');
				if(location.hash === '#G86F853HF98G86F853HF98') {
					history.replaceState(null, document.title, location.pathname);
					setTimeout(function(){
					   location.replace('https://localhost/cart/index.php');
					},0);
				}
			}, false);
			</script>";
			echo $noGoingBack.$addForms.$formEnd;
			exit;
		}
	}
} else { //newview to page / no complete order post
	$pdoCC=new PDO($dsn, 'root');
	$checkCart=$pdoCC->prepare('SELECT COUNT(id) AS product_count FROM cart WHERE session_id=:sess;');
	$checkCart->bindValue(':sess',session_id());
	$resCC=$checkCart->execute();
	if (!$resCC){
		header('Location: index.php');
	}
	$numProducts=$checkCart->fetch(PDO::FETCH_ASSOC)['product_count'];
	if ($numProducts>0){
		$pdo=new PDO($dsn, "root","");//TODO: add username & password
		if (!$pdo) {
			die("Technical issues - please try back 1");
		} else {
			if ((isset($_SESSION['username'])&&isset($_SESSION['password'])&&$_SESSION['username']!==''&&$_SESSION['password']!=='')){//customer logged in
				//$formEnd.=" customer logged in ".$_SESSION['username']." ". $_SESSION['password'];//test
				$selectCustomer=$pdo->prepare("SELECT ship_fname as fname, ship_lname as lname, email, s.id AS shipping_id, customer_id, street, city,  state, zip, phone FROM customer AS c JOIN shipping AS s ON c.id=s.customer_id WHERE username=:username AND password=:password;");
				$selectCustomer->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR, 15);
				$selectCustomer->bindParam(':password', $_SESSION['password'], PDO::PARAM_STR, 15);
				$resCustomer=$selectCustomer->execute();
				if(!$resCustomer){
					die("Technical Issues - Please Try Back - 01");
				} 
				$formAlert="";//clear alerts
				$formAddressOpts="<li>";
				$i=0;
				$foundCustomer=FALSE;
				while($sc=$selectCustomer->fetch(PDO::FETCH_ASSOC)){
					$foundCustomer=TRUE;
					$i++;
					$formAddressOpts.="
					<span>
						<div>
							<input type='radio' name='adOpt' value='".$sc['shipping_id']."' />
						</div><div>
							Address ".$i.") ".$sc['fname']." ".$sc['lname']."
						</div>
						<div>
							".$sc['street']."
						</div>
						<div>
							".$sc['city'].", ".$sc['state'].", ".$sc['zip']."
						</div>
						<div>
							Contact Number: ".$sc['phone']."
						</div>";
			

					$formAddressOpts.="<div id = '".$sc['shipping_id']."'>
							<input type='hidden' name='username' value='".$username."'/>
							<input type='hidden' name='password' value='".$password."'/>
							<input type='hidden' name='first' value='".$sc['fname']."'/>
							<input type='hidden' name='last' value='".$sc['lname']."'/>
							<input type='hidden' name='email' value='".$sc['email']."'/>
							<input type='hidden' name='street' value='".$sc['street']."'/>
							<input type='hidden' name='city' value='".$sc['city']."'/>
							<input type='hidden' name='state' value='".$sc['state']."'/>
							<input type='hidden' name='zip' value='".$sc['zip']."'/>
							<input type='hidden' name='phone' value='".$sc['phone']."'/>
						</div>";
					$formAddressOpts.="</span>
					";
				}//end while $sc
				$formAddressOpts.="</li>";
				echo $topBar.$formAlert.$formStart.$formTitle.$formAddress.$formOr.$formAddressOpts.$formButtons.$formEnd;
				if(!$foundCustomer){//unspec error
					header('Refresh:0');
				}
			}else {//customer not logged in
				echo $topBar.$formAlert.$formStart.$formTitle.$formName.$formAddress.$formNewUser.$formButtons.$formEnd;
			}
		}
	} else {//no products in cart - second back button from index after order
		header('Location: index.php');
	}
}

?>
</body></html>

