<?php
/*TODO - 
0-turn back off xss ability / test again on completion / https://www.owasp.org/index.php/List_of_useful_HTTP_headers
3-logout on product.php page needs to repost selection:~selection; currently not dying by redirecting to index, but should be better. 
3- make checkout link a cart ico and counter
6- make lost password page
6- make temp page to eliminate blank moment on close login window redirect
*/
session_start();
$topbarStart=<<<EOD
<script>
var loginWin;

function alertLog(login, optPage){
	console.log("login="+login+"optPage="+optPage);
	if (login){
		loginWin=window.open("login.php", "_blank", "width=300, height=300, top=10%, left=1000%");
		var timer = setInterval(function() {checkChildWin(optPage);}, 500);
		function checkChildWin(optPage){
			if(loginWin.closed){
				var inprocess=document.getElementById("hiddenlogout");
				console.log(inprocess);
				if (typeof inprocess != 'undefined'){
					//window.location.reload(); //gives confirm refresh to user in context,
					var f = document.createElement("form");
					f.setAttribute('method',"post");
					f.setAttribute('action',optPage);
					var i = document.createElement("input"); 
					i.setAttribute('type',"hidden");
					i.setAttribute('name',"refresh");// so let server refresh
					i.setAttribute('type',"hiddenrefresh");
					var s = document.createElement("input"); 
					s.setAttribute('type',"submit");
					s.setAttribute('value',"Submit");
					s.setAttribute('visibility',"hidden");
					f.appendChild(i);
					f.appendChild(s);
					document.getElementsByTagName('body')[0].appendChild(f);
					f.submit();
					clearInterval(timer);
				}
			}
		}
	} else {
		var inprocess=document.getElementById("hiddenlogout");
		if (!inprocess){
			console.log("in  page" + optPage);
			var f = document.createElement("form");
			f.setAttribute('method',"post");
			f.setAttribute('action',optPage);
			var i = document.createElement("input"); 
			i.setAttribute('type',"hidden");
			i.setAttribute('name',"logout");
			i.setAttribute('id',"hiddenlogout");
			var s = document.createElement("input"); 
			s.setAttribute('type',"submit");
			s.setAttribute('style',"opacity:0;");
			s.setAttribute('value',"Submit");
			f.appendChild(i);
			f.appendChild(s);
			document.getElementsByTagName('body')[0].appendChild(f);
			f.submit();
		}
	} 
}
</script>

<div id="top">
	<div id="toptitle">ShopWeird.com</div>
	<div id="linksurround" style="display:inline">
EOD;
$topbarEnd="\n\t</div>\n</div>";
$link="";
$cart="";
$dsn="mysql:host=localhost;dbname=shopping_clean";
$pdoSC=new PDO($dsn, "root", "");
$isCart=$pdoSC->prepare("SELECT EXISTS(SELECT id from cart where session_id=:sess) AS iscart;");
$isCart->bindValue(':sess',session_id());
$resCart=$isCart->execute();
$isCart=(bool)($isCart->fetch(PDO::FETCH_ASSOC)['iscart']);
if($resCart){
	if($isCart){
		$cart.="<a href='cart.php'>Checkout</a>";
	}
}
if (isset($_SESSION['username'])&&isset($_SESSION['password'])&& !isset($_POST['logout'])){//show logout if they're logged in
	$pdoName=new PDO($dsn, "root","");//TODO: add username & password
	$selectFName=$pdoName->prepare("SELECT fname FROM customer WHERE username=:usr AND password=:psw LIMIT 1;");
	$selectFName->bindParam(':usr', $_SESSION['username'], PDO::PARAM_STR, 15);
	$selectFName->bindParam(':psw', $_SESSION['password'], PDO::PARAM_STR, 15);
	$resultFName=$selectFName->execute();
	if(!$resultFName){//unspec error
		$link.='<a href="#" id="loglink" onclick="alertLog(0, \''.$_SERVER['PHP_SELF'].'\')">Log Out</a>';
	}else{//success
	$fname=$selectFName->fetch(PDO::FETCH_ASSOC)["fname"];
	$link.='<span>Hello '.$fname.'!</span><a href="#" id="loglink" onclick="alertLog(0, \''.$_SERVER['PHP_SELF'].'\')">Log Out</a>';
	}
	$topBar=$topbarStart.$link.$cart.$topbarEnd;
} else if(isset($_POST['logout'])){//if they logged out destroy sess and refresh
	//$link.=$_SERVER['PHP_SELF'];//test
	/*if($_SERVER['PHP_SELF']=='/cart/product.php'){
		echo "<form id='repostselection' action='product.php' method='post'><input type='hidden' name='selection' value='".$_POST['selection']."'/ ><input type='submit'/ ></form><script>document.forms['repostselection'].submit()</script>";
	} remove - i would have to dynamically change js to post selection with logout*/
	$topBar=$topbarStart.$link.$cart.$topbarEnd;
	session_destroy();
	if($_SERVER['PHP_SELF']=='/cart/product.php'){//just redirect to index on logout on cart for now
		header("Location:index.php");
	}
	header("Refresh:0");
} else if(isset($_POST['refresh'])){//if they visited login page
	header("Refresh:0");
}else {//if not logged in yet, no post data, show login link
	$link.='<a href="#" id="loglink"  onclick="alertLog(1, \''.$_SERVER['PHP_SELF'].'\')">Log In</a>';
	$topBar=$topbarStart.$link.$cart.$topbarEnd;
}
//before echoing page, all other pages should:
//require top.php; echo $topBar;


?>