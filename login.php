<!-- 
FIXED - sending refesh now - not recognizing topbar on login closed by 'x' out prior to index refresh (ok then)
6 - add lost password functionality
-->

<?php session_start() ?>
<!DOCTYPE html>
<html lang="en-US">
<head><title>Process Order</title>
<style>
*{
	font: 1em Helvetica, Arial, sans-serif;
}
input{
	margin:5% 3%;
	width:85%
}
#submit{
	margin-left:70%;
	width:30%;
}
#outer{
	margin: 3%;
	padding: 3%;
	border: 1px solid #159;
}
</style>
</head>
<body>
<div id="outer">
<?php 
// Use HTTP Strict Transport Security to force client to use secure connections only
$use_sts = true;

// iis sets HTTPS to 'off' for non-SSL requests
if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=31536000');
} elseif ($use_sts) {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
    // we are in cleartext at the moment, prevent further execution and output
    die();
}
$loginForm=<<<EOD
<form method="post" action="login.php">
	<label for="usr">Username:</label>
	<input type="text" id="usr" name="usr" /><br />
	<label for="psw">Password:</label>
	<input type="text" id="psw" name="psw"/>	
	<input type="submit" id="submit" value="Submit" />
</form>
EOD;
if(isset($_POST['usr']) && isset($_POST['psw'])){
	$dsn="mysql:host=localhost;dbname=shopping_clean";
	$pdoCID=new PDO($dsn, "root","");//TODO: add username & password
	if (!$pdoCID) {
		die("Technical issues - please try back 1");
	} else {
		$selectCustomer=$pdoCID->prepare("Select id from customer WHERE username=:username AND password=:password LIMIT 1;");
		$selectCustomer->bindParam(':username', $_POST['usr'], PDO::PARAM_STR, 15);
		$selectCustomer->bindParam(':password', $_POST['psw'], PDO::PARAM_STR, 15);
		$resultCustomer=$selectCustomer->execute();
		$custID=$selectCustomer->fetch(PDO::FETCH_ASSOC)['id'];
		if(!$resultCustomer || !$custID){
			echo "<div>Login Not Found. Try again.</div><br />".$loginForm;
		} else {
			$_SESSION['username']=$_POST['usr'];
			$_SESSION['password']=$_POST['psw'];
			//make new cart if sess doesn't have one yet
			$pdoInsCart=new PDO($dsn, "root","");
			$insertCustomer=$pdoInsCart->prepare("
			INSERT INTO cart (product_id, qty_ordered, customer_id, session_id)
			SELECT NULL, 0, :cID, :sID 
			FROM dual WHERE NOT EXISTS(
				SELECT * FROM cart
				WHERE session_id=:sID
				LIMIT 1
			);");
			$insertCustomer->bindParam(':cID', $custID );
			$insertCustomer->bindParam(':sID', session_id());
			$resultInsert=$insertCustomer->execute();
			if(!$resultInsert){
				echo "<div>There was an error loging in. Please try again. 1</div>".$loginForm;
				
			} else if($insertCustomer->rowCount()==0){//update if sess already had cart
				$pdoUpdateCart=new PDO($dsn, "root","");	
				$updateCart=$pdoUpdateCart->prepare("UPDATE cart SET customer_id=:cID WHERE session_id=:sID");
				$updateCart->bindParam(':cID', $custID );
				$updateCart->bindParam(':sID', session_id());
				$resultUpdate=$updateCart->execute();
				if(!$resultUpdate){
					echo "<div>There was an error loging in. Please try again. 2</div>".$loginForm;					
				} else {//sucessfull login
					echo "<script>window.close();</script>";
					header("Refresh:0");				
				}
			}else {//sucessful login
				echo "<script>window.close();</script>";
				header("Refresh:0");
			}
		}
	}
}
else if(isset($_POST['usr']) && !isset($_POST['psw'])){
	echo "Please provide a password.".$loginForm;
} else if(!isset($_POST['usr']) && isset($_POST['psw'])){
	echo "Please provide a username.".$loginForm;
} else {#first visit
	echo $loginForm;
}
?>
</div></body>