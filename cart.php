<!DOCTYPE html>
<!--TODO: 
FIXED -sent to index on empty view error
-->
<html lang="en-US">
<head><title>Shopping Cart</title>
<link rel="stylesheet" type="text/css" href="cartAll.css">
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">

</head>
<body>
<script>
function calcTotal(){
	var items=document.getElementsByClassName("itemtotal");
	var total = document.getElementById("total");
	var sum = 0;
	for(var i=0; i<items.length; i++){
		sum+=parseFloat(items[i].getAttribute("data-total"));
	}
	if (sum==0){
		window.location="index.php";
	}
	total.innerHTML="$"+sum.toFixed(2);
}

function init(){
	calcTotal();
}
document.addEventListener("DOMContentLoaded",init, false);

function updateTotals(id){
	var price=document.getElementById("price"+id).getAttribute("data-price");
	var qty=document.getElementsByName(id)[0].value;
	var newTotal=parseFloat(price)*parseFloat(qty);
	document.getElementById("qty"+id).value = qty;
	var productsForm=document.forms.products;
	var orderForm=document.forms.order;
	productsForm.elements["qty"+id].value=qty;
	orderForm.elements["qty"+id].value=qty;
	var totalElt=document.getElementById(id);
	totalElt.innerHTML=newTotal.toFixed(2);
	totalElt.setAttribute("data-total",newTotal);
	calcTotal();
}
</script>
<div id="wrap"><table id='carttable'><tr><td>Items Ordered</td><td>Price</td><td>Quantity</td><td>Totals</td><td></td></tr>
<?php 
require 'top.php';
function untaint($data, $num=FALSE,  $tic=FALSE){
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	$data=(!$tic)?str_replace("'", "", $data):$data;
	if(($num && !is_numeric($data))||(is_numeric($data) && $data <1)){
		$data=1;
	} 
	return $data;
}
//define vars
$resultInsertCart=FALSE;
$newView=isset($_POST['selection']);
if (isset($_COOKIE['postadded'])){
	$newView=FALSE;
}
$backView=isset($_POST['backbutton']);
if(isset($_POST['loc'])){
	if($_POST['loc']=='order' || $_POST['loc']=='index'){
		header("location:".$_POST['loc'].".php");
	} else{
		header("location:index.php");
	}
}

if ($newView){ //on first page visit
	$CID="NULL";
	if(isset($_SESSION['username'])){ //if logged in already
		$pdoSCID=new PDO($dsn, "root","");//TODO: add username & password
		$selectCID=$pdoSCID->prepare("SELECT MAX(id) AS id FROM customer WHERE username=:un AND password =:ps;");
		$selectCID->bindValue(':un',$_SESSION['username']);
		$selectCID->bindValue(':ps',$_SESSION['password']);
		$resultCID = $selectCID->execute();
		$CID=$selectCID->fetch(PDO::FETCH_ASSOC)['id'];
		if(!isset($CID) || $CID ==-1){
			$CID='NULL';
		}
	}//$CID now = (logged in)? customer.id : "NULL"
	//check for existing selections of a product in cart
	$pdoSCD=new PDO($dsn, "root","");//TODO: add username & password
	$selectCartDup=$pdoSCD->prepare("SELECT qty_ordered FROM cart WHERE session_id =:sess AND product_id =:pid LIMIT 1;");
	$selectCartDup->bindValue(':sess',session_id());
	$selectCartDup->bindValue(':pid',untaint($_POST['selection'], TRUE));
	$resultDup=$selectCartDup->execute();
	$lastNumOrderedArr=$selectCartDup->fetch(PDO::FETCH_ASSOC);
	if ($resultDup){
		if (!isset($lastNumOrderedArr['qty_ordered'])){//if there are no dup products in cart, insert into cart
			//TODO: need to verify not posting more then avail here!!!
			$pdoIC=new PDO($dsn, "root","");//TODO: add username & password
			$insertCart=$pdoIC->prepare("INSERT INTO cart (product_id, qty_ordered, session_id, customer_id) VALUES (:pid,:qty,:sess, $CID)");
			$insertCart->bindValue(':pid',untaint($_POST['selection'], TRUE));
			$insertCart->bindValue(':qty',untaint($_POST['number'], TRUE));		
			$insertCart->bindValue(':sess',session_id());	
			$resultInsertCart=$insertCart->execute();
			if(!$resultInsertCart){
				die ('error !$resultInsertCart');
				header("Location:index.php");
			}
		}
		else {//there's already a product in cart=posted selection
			$lastNumOrdered=$lastNumOrderedArr['qty_ordered'];
			$numOrdered=$lastNumOrdered + untaint($_POST['number'], TRUE);
			$pdoNS=new PDO($dsn, "root","");
			$selectNumStocked=$pdoNS->prepare("SELECT qty FROM products WHERE id=:pid;");
			$selectNumStocked->bindValue(':pid', untaint($_POST['selection'], TRUE));
			$selectNumStocked->execute();
			$numStocked=$selectNumStocked->fetch(PDO::FETCH_ASSOC)['qty'];
			if($numOrdered<=$numStocked){//assuming there are still products available to sell
				$pdoUC=new PDO($dsn, "root","");
				$updateCart=$pdoUC->prepare("UPDATE cart SET qty_ordered=".$numOrdered." WHERE session_id=:sess AND product_id=:pid;");
				$updateCart->bindValue(':sess',session_id());
				$updateCart->bindValue(':pid',untaint($_POST['selection'], TRUE));
				$resultUpdateCart=$updateCart->execute();
				if (!$resultUpdateCart){
					header("Location:Index.php");
				} else {
					//set 2 min cookie to ignore post to not add more items on page refresh
					setcookie("postadded", "Y", time()+120);
				}
			} else {//user post more then avail
				header("Location: index.php");
			}
		}
	}
} else if (!$newView && !$backView){//user deleted or changed qty then moved to next page, which refreshed
	if (count($_POST)>0){
		foreach($_POST as $id=>$qty){
			if($id!=="loc"){
				$pdoSCLQ=new PDO($dsn, "root","");
				$selectCartLastQty=$pdoSCLQ->prepare("SELECT qty_ordered FROM Cart WHERE session_id =:sess AND product_id=:pid LIMIT 1;");
				$selectCartLastQty->bindValue(':sess',session_id());
				$selectCartLastQty->bindValue(':pid',$id);
				$resultLastQty=$selectCartLastQty->execute();
				if(!$resultLastQty){
					header("Location:index.php");
				}
				$lastQty=$selectCartLastQty->fetch(PDO::FETCH_ASSOC)['qty_ordered'];
				$diffQty=$qty-$lastQty;
				$pdoSNS=new PDO($dsn, "root", "");
				$selectNumStocked=$pdoSNS->prepare("SELECT qty FROM Products WHERE id=:pid LIMIT 1;");
				$selectNumStocked->bindValue(':pid', $id);
				$resultNumStocked=$selectNumStocked->execute();
				$numStocked=$selectNumStocked->fetch(PDO::FETCH_ASSOC)['qty'];
				if($qty > $numStocked){//user post more then avail
					header("Location:index.php");
				} else {
					$newNum=$numStocked-$diffQty;
					//echo $numStocked . " - " . $diffQty . " = " . $newNum ."<br />";//TEST
					$pdoUNS=new PDO($dsn, "root", "");
					$updateNumStocked=$pdoUNS->prepare("UPDATE Products SET qty =:num WHERE id =:pid;");
					$updateNumStocked->bindValue(':num', $newNum);
					$updateNumStocked->bindValue(':pid',$id);
					$resultUpdateNumStocked=$updateNumStocked->execute();
					if(!$resultUpdateNumStocked){
						header("Location:index.php");
					}
				}
				if($qty>0 && $qty < $numStocked){
					$pdoUCN=new PDO($dsn, "root", "");
					$updateCartNum=$pdoUCN->prepare("UPDATE cart SET qty_ordered =:qty WHERE product_id=:pid AND session_id=:sess;");
					$updateCartNum->bindValue(':qty',$qty);
					$updateCartNum->bindValue(':pid',$id);
					$updateCartNum->bindValue(':sess',session_id());
					$resultUpdateCart=$updateCartNum->execute();
					if (!$resultUpdateCart){
						header("Location:index.php");
					}
				} else {
					$pdoDC=new PDO($dsn, "root", "");
					$deleteCart=$pdoDC->prepare("DELETE FROM cart WHERE product_id=:pid AND session_id=:sess;");
					$deleteCart->bindValue(':pid',$id);
					$deleteCart->bindValue(':sess', session_id());
					$resultDeleteCart=$deleteCart->execute();
					if (!$resultDeleteCart){
						header("Location:index.php");
					}
				}
			}
		}
	} //else no post/ visit from other part of site {pass}
}
if ($newView && !isset($resultInsertCart) && !isset($resultUpdateCart)) {
	die("Technical issues - please try back 2");		
} else {//if new page view and we've either sucessfully inserted new item or update qty on an item
	$pdoSC=new PDO($dsn, "root", "");
	$selectCart=$pdoSC->prepare("SELECT * FROM cart WHERE session_id =:sess;");
	$selectCart->bindValue(':sess',session_id());
	$resultCart=$selectCart->execute();
	if (!$resultCart) {
		header("Location:index.php");
	} else {
		$resultUpdateProduct=FALSE;
		$elts="";
		$hidden="";
		while($rc = $selectCart->fetch(PDO::FETCH_ASSOC)) {
			$pdoSP=new PDO($dsn, "root", "");
			$selectProduct=$pdoSP->prepare("SELECT id, item, price, qty FROM Products WHERE id=:pid;");
			$selectProduct->bindValue(':pid',$rc["product_id"]);
			$resultProduct=$selectProduct->execute();
			if (!$resultProduct) {
				//var_dump($resultProduct->fetch_assoc()[id]);
				header("Location:index.php");
			} else {
				while($rp = $selectProduct->fetch(PDO::FETCH_ASSOC)) {
					if($newView && isset($_POST['selection']) && untaint($_POST['selection'], TRUE) === $rp['id']){
						if(untaint($_POST['number']) > $rp['qty']){//user post more then avail
							die("Error 1<br/><div><form method='post' action='cart.php' id='products' name='products'><input type='submit' value='<- Continue Shopping'/></form></div>");
							//todo:investigate logging from the php
							header("Location:product.php");
						} else {//update number of product available
							$numAvail=$rp['qty']-untaint($_POST['number'], TRUE);
							$pdoUP=new PDO($dsn, "root", "");
							$updateProduct=$pdoUP->prepare("UPDATE PRODUCTS SET qty=:qty WHERE id=:pid;");
							$updateProduct->bindValue(':qty',$numAvail);
							$updateProduct->bindValue(':pid',untaint($_POST['selection'], TRUE));
							$resultUpdateProduct=$updateProduct->execute();
						}
					}
					if(!$newView || $resultUpdateProduct===TRUE || ($newView && untaint($_POST['selection'], TRUE) !== $rp['id'] )){//put html to show products in cart in elts						
						$elts.= "<tr><td>" . $rp['item']. "</td>
						\n\t<td id ='price".$rp['id']."' data-price='".$rp['price']."'>$" . $rp['price']. " X</td>
						\n\t<td>
							<form>
								<input type='number' name='".$rp['id']."' min='1' max='".$rp['qty']."' value='" . $rc['qty_ordered']. "' oninput='updateTotals(".$rp['id'].")' />
							</form></td>\n\t
						<td>$<div id='".$rp['id']."' class='itemtotal' data-total='".($rp['price']*$rc['qty_ordered'])."'>".number_format($rp['price']*$rc['qty_ordered'], 2)."</div></td>\n\t
						<td>
							<form method='post' action='#'>\n\t\t
								<input type='submit' value='Delete Item' />\n\t\t<input type='hidden' id='qty".$rp['id']."' name='".$rp['id']."' value ='0'/>
							</form></td></tr>\n";
						
						$hidden.="\n\t<input type='hidden' id='qty".$rp['id']."' name='".$rp['id']."' value ='".$rc['qty_ordered']."'/>";
						
					} else {//unspec error
						echo"<script>alert('unspec error');</script>";
						//header('Location: index.php');	
					}
				}//end while $rp
			}
		}//end while $rc
		//echo page
		echo $topBar.$elts;
		echo "<tr><td></td>\n\t<td></td>\n\t<td>Cart Total:</td>\n\t<td><div id = 'total'></div></td><td></td>\n</tr>\n<tr>\n\t<td><form method='post' action='cart.php' id='products' name='products'><input type='hidden' name='loc' value='index'/><input type='submit' value='<- Continue Shopping'/>";
		echo $hidden;
		echo "</form></td>\n\t<td></td>\n\t<td></td>\n\t<td></td>\n\t<td><form  action='cart.php' method='post' id='order' name='order'><input type='submit' value='Place Order ->'/><input type='hidden' name='loc' value='order'/>";
		echo $hidden;
		echo "</form></td></tr>";
	}
}

?>

</table></div>
</body></html>