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

?>

<html>
	<head>
		<style>
			.corners {
				border-radius: 25px;
				border: 2px solid;
				padding: 20px;
				margin: 20px;
				width: 90vw;
				max-width: 160vh;			
			}
		</style>
		
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
		</script>
	</head>
	<body>
		<p>Home <a href="using.php">Guide</a> <a href="donate.php">Donate</a> <a href="transfer.php">Transfer Lookup</a></p>
		<p>We have an onion service available <a href="http://ursxhdkliddlnypm7eenul5uhnb5aqqxbz6oj2zalivjfaacim2id5ad.onion/monerophp/home.php">here</a></p>
		<h1>Welcome to the Political Party Donation Website</h1>
		
		<p>Our website allows supporters to anonymously donate to the party without risking your public image or safety.</p>
		<p>We only accept Monero (XMR) for donations as this offers you a high level of privacy and security.</p>
		<p>To increase your privacy we recommend accessing our website using a VPN and/or <a href="https://www.torproject.org/download/">Tor browser</a> and our <a href="http://ursxhdkliddlnypm7eenul5uhnb5aqqxbz6oj2zalivjfaacim2id5ad.onion/monerophp/home.php">onion link</a>.</p>
		<p>If you are unfamiliar with acquiring or transfering Monero please see our guide <a href="using.php">here</a>.</p>
		<p>To make a donation please <a href="donate.php">click here</a>.</p>
		
		<h2>Balance</h2>
		
		<?php
		$balanceArray = getBalance();		
		$balance = array_values($balanceArray)[0];
		
		echo '<p>We have received a total of ' . $balance/getOne() . ' XMR so far.</p>';
		
		?>
		
	</body>
</html>