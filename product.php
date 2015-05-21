<!DOCTYPE html>
<html lang="en-US">
<head><title>View Product</title>
<link rel="stylesheet" type="text/css" href="cartAll.css">
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
<script>
	//force back button to do what I say. No asking for page reload confirmaton on index.
	history.replaceState(null, document.title, location.pathname+"#G86F853HF98G86F853HF98");//random string I can match for in hash ancor
	history.pushState(null, document.title, location.pathname);
	window.addEventListener("popstate", function() {
		if(location.hash === "#G86F853HF98G86F853HF98") {
			history.replaceState(null, document.title, location.pathname);
			setTimeout(function(){
			   location.replace("https://localhost/cart/index.php");
			},0);
		}
	}, false);
</script>
</head>
<body>
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
require 'top.php';
echo $topBar;
if(isset($_COOKIE['postadded'])){
	//set postadded cookies to expired if exist so new post is added
	setcookie('postadded',"", time()-60);
}
$selection=(isset($_POST['selection']))? $_POST['selection']:1;
if(is_numeric($selection)){
	$dsn="mysql:host=localhost;dbname=shopping_clean";
	$pdo=new PDO($dsn, "root","");//TODO: add username & password
	$selectProducts=$pdo->prepare("select * from PRODUCTS where id=:select;");
	$selectProducts->bindValue(':select',$selection, PDO::PARAM_INT);
	$result = $selectProducts->execute();
	echo "<table id='producttable'>";
	if ($result){
		while ($rec=$selectProducts->fetch(PDO::FETCH_ASSOC)){
			echo "\n\t<tr>\n\t\t<td colspan='3'>".$rec['item']."</td>\n\t</tr>";
			echo "\n\t<tr>\n\t\t<td rowspan='2'><img src='".$rec['img']."' alt='" .$rec['item']. "'></td>";
			echo($rec['price']>0)? 
			"\n\t\t<td>$" . number_format((float)$rec['price'], 2) ."</td>" 
			: "\n\t\t<td>".$rec['item']."<br /><br />Contact us for details</td>";
			echo ($rec['qty'] > 0)?
			"\n\t\t<td align='right'>
			<form method='post' action='cart.php'>
				<input type='number' id='numinput' name=number min='1' max = '".$rec['qty']."' value='1' oninput='setNumber()'/>
				<input type='submit' value='Add to Cart'/>
				<input type='hidden' name='selection' value='".$rec['id']."'/>
			</form>\n\t\t</td>\n\t</tr>":
			"\n\t\t<td align='right'>
			<form method='post' action='order.php'>
				<input type='submit' value='Contact Us'/>
				<input type='hidden' name='selection' value='".$rec['id']."'/>
			</form>\n\t\t</td>\n\t</tr>";
			echo "\n\t<tr>\n\t\t<td colspan='2'>".$rec['descript']."</td>\n\t</tr>";
			echo "\n\t<tr>\n\t\t<td>\n\t\t\t<form action='index.php' method='post'><input type='submit' id='backbutton' name='backbutton' value='<- Back' /></form>\n\t\t</td>\n\t\t<td></td>\n\t\t<td></td>\n\t</tr>";
		}
	} else {
		echo "<tr><td>Product not found</tr></td>";
	}
	echo "\n</table>";	
}
?>
</body></html>