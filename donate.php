<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('src/jsonRPCClient.php');
require_once('src/daemonRPC.php');
require_once('src/walletRPC.php');



function generateAddress() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$create_address = $walletRPC->create_address(0, 'New subaddress');
	$close_wallet = $walletRPC->close_wallet();
	return $create_address;
}

function getBalance() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$get_balance = $walletRPC->get_balance();
	$close_wallet = $walletRPC->close_wallet();
	return $get_balance;
}	

function getOne() {
	$x = 1000000000000;
	return $x;
}

function getPrice($currency) {
	$path = "https://min-api.cryptocompare.com/data/price?fsym=XMR&tsyms=";
	$path .= $currency;
	$priceJSON = file_get_contents($path);
	$priceOBJ = json_decode($priceJSON);
	$priceArray = (array) $priceOBJ;
	$price =  array_values($priceArray)[0];
	return $price;
}

function createSignature($address) {
	openssl_sign($address, $signature, getPrivateKey());
	$signature = base64_encode($signature);
	return $signature;
}

function getPrivateKey() {
	$pkeyid = openssl_pkey_get_private("file://C:/xampp/htdocs/monerophp/mykey.pem");
	return $pkeyid;
}

function getPublicKey() {
	$file = file_get_contents("mykey.pub");
	return $file;
}

function getPublicKeyVerify() {
	$pkeyid = openssl_pkey_get_public("file://C:/xampp/htdocs/monerophp/mykey.pub");
	return $pkeyid;
}


?>

<html>
	<head>
		<style>
		
			#container {
				display:inline-block;
				background-color: #BEBEBE;
				padding: 10px;
			}
			.corners {
				border-radius: 25px;
				border: 2px solid;
				padding: 20px;
				margin: 20px;
				width: 90vw;
				max-width: 160vh;			
			}
			
			.greybreak {
				background-color: #BEBEBE;
				width:95%;
				word-wrap:break-word;
				padding: 10px;
			}
			
		</style>
		
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		</script>
	</head>
	<body>
	
		<p><a href="home.php">Home</a> <a href="using.php">Guide</a> Donate <a href="transfer.php">Transfer Lookup</a></p>
		<h1>Donate</h1>
		<p>Use the button below to generate a unique address for you to send your donation to. You should only use this address once, if you want to make a future transaction then you should generate another address. Please do not add a payment ID when making the transfer.</p>
		
		<?php
		$balanceArray = getBalance();		
		$balance = array_values($balanceArray)[0];
		
		echo '<p>We have received a total of ' . $balance/getOne() . ' XMR so far.</p>';
		
		?>
		
		<form method="post"> 
			<input type="submit" name="button1" class="button" value="Generate Address"/>
		</form>
		
		<?php
        if(array_key_exists('button1', $_POST)) { 
            $addressC = generateAddress(); 
			$subaddress = array_values($addressC)[0];
			header("Location: http://localhost/monerophp/donate.php/?add=" . $subaddress);
		}   
		
		$url = $_SERVER['REQUEST_URI'];
		if(strpos($url, "home.php")) {
			header("Location: http://localhost/monerophp/home.php");
		}
		else if (strpos($url, "using")) {
			header("Location: http://localhost/monerophp/using.php");
		}
		else if (strpos($url, "transfer")) {
			header("Location: http://localhost/monerophp/transfer.php");
		}

		if(isset($_GET["add"])) {
			
			$ga = $_GET["add"];
			
			?>
			<p>Address:</p>
			<div id="container">
			<?php
			echo $ga;
			?>
			</div>
			<p>We recommend you use the following RSA signature and our public key to verify the validity of the address</p>			
			<p>RSA Signature:</p>
			
			<div class="greybreak">
			<?php
			echo createSignature($ga);
			?>
			</div>
			
			<p>Public Key:</p>

			<div id="container">
			<?php
			echo nl2br(getPublicKey());
			?>
			</div>
			<?php		
			
		}
		?>
		
		<p>You can track the progress of your transfer <a href="https://xmrchain.net/">here</a> or using our tool <a href="transfer.php">here</a>.</p>
		<p>If you are uncertain how much to send you can use our tool below to convert between Monero and Several Currencies.</p>
		
		<?php
		
		echo '<p>Price per XMR £' . getPrice("GBP") . '</p>';
		
		?>
		
		
		<form method = "post">
			<select name="xmrcurr" id="xmrcurr">
				<option value="to">Convert to Monero</option>
				<option value="from">Convert from Monero</option>
			</select>
			<input type="text" id="amount" name="amount">
			<select name="currency" id="currency">
				<option value="gbp">GBP</option>
				<option value="usd">USD</option>
				<option value="eur">EUR</option>
				<option value="cad">CAD</option>
				<option value="mmk">MMK</option>
				<option value="jpy">JPY</option>
			</select>		
			<input type="submit" name = "button2" value="Submit">
			</input>
		</form>
		
		<?php
		
		if(array_key_exists('button2', $_POST) || array_key_exists('button3', $_POST)) {
			if ($_POST["currency"] == "gbp") {
				$priceC = getPrice("GBP");
				$c = 'GBP';
			}
			else if ($_POST["currency"] == "usd") {
				$c = 'USD';
			}
			else if ($_POST["currency"] == "eur") {
				$c = 'EUR';
			}
			else if ($_POST["currency"] == "cad") {
				$c = 'CAD';
			}
			else if ($_POST["currency"] == "mmk") {
				$c = 'MMK';
			}
			else if ($_POST["currency"] == "jpy") {
				$c = 'JPY';
			}
			$priceC = getPrice($c);
		}
		
		if(array_key_exists('button2', $_POST)) {
			if($_POST['xmrcurr'] == "from") {
				echo '<p>Amount = ' . $_POST["amount"] . ' XMR</p>';
				echo '<p>Cost = ' . ($priceC * $_POST["amount"]) . $c . '</p>';
			}
			else {
				echo '<p>Amount = ' . ($_POST["amount"]/$priceC) . ' XMR</p>';
				echo '<p>Cost = ' . $_POST["amount"] . ' ' . $c . '</p>';
			}
		}
		
		?>
	</body>
</html>