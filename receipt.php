<!DOCTYPE html>
<!--TODO: 
3-email a receipt
-->
<html lang="en-US">
<head>
	<title>Purchase Receipt</title>
	<link rel="stylesheet" type="text/css" href="cartAll.css">
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
	<script>window.resizeTo(window.screen.availWidth/2,window.screen.availHeight/2);</script>
</head>
<body>
<div id='main'></div>
<?php
// Use HTTP Strict Transport Security to force client to use secure connections only
$use_sts = true;

// is sets HTTPS to 'off' for non-SSL requests
if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=31536000');
} elseif ($use_sts) {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
    // we are in cleartext at the moment, prevent further execution and output
    die();
}

require 'top.php';
//echo $topBar;
if(isset($_POST['oid'])){
	$orderID=$_POST['oid'];
	echo $orderID;
	$r="\n<table><thead><th colspan=2>Thanks for shopping Weird!</th></thead>
	\n\t<tr><th colspan=2>Order Number #$orderID</th></tr>
	\n\t<tr><th colspan=2>Your order will be shipped to:</th></tr>";
	if (!is_numeric($orderID)){
		//die('Failed to get order info.<script>window.close()</script>');
	}else {
		$dsn="mysql:host=localhost;dbname=shopping_clean";
		$pdoShip=new PDO($dsn, "root","");//TODO: add username & password
		$selectShip=$pdoShip->prepare(" SELECT ship_fname AS fname, ship_lname AS lname, street, city, zip, phone FROM orders AS o JOIN shipping AS s ON o.shipping_id=s.id WHERE o.id=:oid;");
		$selectShip->bindValue(':oid',$orderID);
		$resShip=$selectShip->execute();
		if(!$resShip){
			die("ouch");
		} else {
			$ss=$selectShip->fetch(PDO::FETCH_ASSOC);
			$r.="\n\t<tr><td colspan=2>".$ss['fname']." ".$ss['lname']."</td></tr>
			\n\t<tr><td colspan=2>".$ss['street']."</td></tr>
			\n\t<tr><td colspan=2>".$ss['city'].", ".$ss['zip']."</td></tr>";
		}
		$r.="<tr><th colspan=2>Your order is for:</th></td>";
		$pdoOrd=new PDO($dsn, "root","");//TODO: add username & password
		$selectOrders=$pdoOrd->prepare("SELECT p.item, o.qty, p.price FROM orders AS o JOIN products AS p ON o.product_id=p.id WHERE customer_id =(SELECT customer_id FROM orders WHERE id=:oid) and tm>NOW()-INTERVAL 10 SECOND;");
		$selectOrders->bindValue(':oid',$orderID);
		$resOrd=$selectOrders->execute();
		$totalC=0;
		if(!$resOrd){
			die("ouch");
		} else {
			while($qp=$selectOrders->fetch(PDO::FETCH_ASSOC)){
				$itemC=$qp['qty']*$qp['price'];
				$totalC+=$itemC;
				$r.="<tr><td>".$qp['qty']." - ".$qp['item']."</td><td>$".number_format((float)$itemC, 2)."</td></tr>";
			}
		}
		$r.="<tr><th>Total:</th><td>$".number_format((float)$totalC, 2)."</td></tr></table>";
		echo $topBar.$r;
	}
} else {
	//die('Failed to get order info.<script>window.close()</script>');
}
?>
</body></html>