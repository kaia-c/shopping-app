<!DOCTYPE html>
<!--TODO: 
0-change db credentials on completion
3-convert into function to accept array of products, make item & price column sortable by adding order by to the queries
3-change mysqli to PDO on this page (static statement so less priority)
4-add featured item on right
5-add search above it on right and catagory menus on left
-->
<html lang="en-US">
<head>
	<title>Browse Products</title>
	<link rel="stylesheet" type="text/css" href="cartAll.css">
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
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
function untaint($data){
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	$data=str_replace("'", "", $data);
	return $data;
}

if(isset($_POST['un'])&&isset($_POST['pw']) && $_POST['un']!=""&& $_POST['un']!=""){
	$confirmned=untaint($_POST['un']);
	$_SESSION["username"]=($confirmned==$_POST['un'])?$confirmned:"";
	$confirmned=untaint($_POST['pw']);
	$_SESSION["password"]=($confirmned==$_POST['pw'])?$confirmned:"";
}

$con=mysqli_connect("localhost","root","","shopping_clean");
$sql="SELECT * FROM products;";
$result = mysqli_query($con, $sql);
echo $topBar;
echo "<table id='indextable'><tr><td>Item</td><td></td><td>Price</td></tr>\n";
//echo "<table><tr><td>Item</td><td></td><td>Price</td><td>Number Stocked</td></tr>\n";

while ($rec=mysqli_fetch_assoc($result)){
	$formStart="\n\t\t<td><form method='post' action='product.php'>
		\t<input type='hidden' name='selection' value='".$rec['id']."'/>\n\t\t\t";
	echo "\t<tr>
			".$formStart."<input type='submit' class='linput' value ='".$rec['item']."' />\n\t\t</form></td>";
	echo $formStart;		
	echo "<input type='submit' style='background:url(".$rec['img'].");border:1px solid 		#159;display:block;width:200px;height:120px;background-size: 200px 120px;' value='' alt='" .$rec['item']. "'/>
	\t</form></td>";
	echo ($rec['price']>0)? 
	$formStart."<input type='submit' class='rinput' value ='$".number_format((float)$rec['price'], 2)."' />\n\t\t</form></td>" 
	: $formStart."<input type='submit' class='rinput' value ='Inquire' />\n\t\t</form></td>";
	echo "\n\t</tr>\n";
}
echo "</table>";
?></body></html>