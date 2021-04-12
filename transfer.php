<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('src/jsonRPCClient.php');
require_once('src/daemonRPC.php');
require_once('src/walletRPC.php');
require_once "vendor/autoload.php";
require 'C:\xampp\PHPMailer/src/Exception.php';
require 'C:\xampp\PHPMailer/src/PHPMailer.php';
require 'C:\xampp\PHPMailer/src/SMTP.php';

function getBalance() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$get_balance = $walletRPC->get_balance();
	$close_wallet = $walletRPC->close_wallet();
	return $get_balance;
}	

function getTransfers() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$refresh_wallet = $walletRPC->refresh();
	$get_transfers = $walletRPC->get_transfers('all');
	$close_wallet = $walletRPC->close_wallet();
	return $get_transfers;
}

function getBlock($height) {
	$daemonRPC = new daemonRPC('127.0.0.1', 18081);
	$get_block = $daemonRPC->getblock_by_height($height);
	return $get_block;
}

function getOne() {
	$x = 1000000000000;
	return $x;
}

function email($emailAddress, $name, $amount, $txid) {
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'monerodissertation'; 
	$mail->Password = 'monerodissertation123.'; 
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->SetFrom('noreply@monero.com', 'Monero Dissertation', FALSE);
	$mail->addAddress($emailAddress); 
	$mail->Subject = 'Thank you for you donation';
	$mail->isHTML(true);
	$mailContent = "<p>Hi " . $_POST["name"] . ",</p>
	<p>Thank you for your donation of " . ($amount/getOne()) . " XMR.</p>
	<p>We have received your donation and greatly appreciate your generosity.</p>
	<p>All the best,</p>
	<p>Angus</p>
	<p>Your transaction ID is: " . $txid;
	$mail->Body = $mailContent;
	
	return $mail;
}


?>

<html>
	<head>
	
		<meta http-equiv="Cache-control" content="no-cache">
		
		<style>
			.corners {
				border-radius: 25px;
				border: 2px solid;
				padding: 20px;
				margin: 20px;
				width: 90vw;
				max-width: 160vh;			
			}
			
			input[type=text] {
				width: 500px;
			}
		</style>
		
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}
			
			function redirect() {
				var action_src = "http://localhost/monerophp/transfer.php/";
				var form = document.getElementById('form');
				form.action = action_src;
			}
		</script>
	</head>
	<body>
		<p><a href="home.php">Home</a> <a href="using.php">Guide</a> <a href="donate.php">Donate</a> Transfer Lookup</p>
		<h1>Transfer Lookup</h1>
		<p>Enter your TXID below to see the progress of your transfer. Any transfer with more than 10 confirmations is considered complete.</p>
		<p>It can take a few minutes for you transfer to appear.</p>
		
		<?php
		$balanceArray = getBalance();
		$balance = array_values($balanceArray)[5];
		
		echo '<p>We have received a total of ' . $balance/getOne() . ' XMR so far.</p>';
				
		?>
	
		<form id="form" onsubmit="redirect()"> 
			<input type="text" id="txid" name="txid">
			<input type="submit" name="button1" class="button" value="Search"/>
		</form>
		
		<?php
		
		$current = FALSE;
		
		$url = $_SERVER['REQUEST_URI'];
		if(strpos($url, "home.php")) {
			header("Location: http://localhost/monerophp/home.php");
		}
		else if (strpos($url, "using")) {
			header("Location: http://localhost/monerophp/using.php");
		}
		else if (strpos($url, "donate")) {
			header("Location: http://localhost/monerophp/donate.php");
		}
		
		if(isset($_GET["txid"])) {
			$transfers = getTransfers();
			$final =  end($transfers);
			
			for ($x = 0; $x < count($final); $x++) {
				$am = array_values($final)[$x];
				$con = array_values($am)[3];
				$amoun = array_values($am)[1];
				$time = array_values($am)[13];
				$txid = array_values($am)[14];
				$height = array_values($am)[6];
				
				
				if ($txid == $_GET["txid"]) {
					
					$block = getBlock($height);				
					$block_header = $block['block_header'];
					$difficulty = $block_header['difficulty'];
					
					echo '<div class="corners">';
					echo '<p> Amount: ' . ($amoun/getOne()) . ' XMR</p>';
					echo '<p> Confirmations: ' . $con . '</p>';
					echo '<p> Time: ' .date('d F Y H:i:s', $time) . '</p>';
					echo '<p> TXID: ' . $txid . '</p>';
					echo '<p> Height: ' . $height . '</p>';
					echo '<p> Difficulty: ' . $difficulty . '</p>';
					echo '</div>';
					if($con >= 10) {
						echo '<p>Your donation has been received. Thank you for your support.</p>';
						
						?>
						
						<p>If you would like an email confirmation for your transaction please fill in the form below:</p>
						
						<form method="post"> 
							<input type="text" id="name" name="name" placeholder="Name">
							<input type="text" id="email" name="email" placeholder="Email Address">
							<input type="submit" name="button2" class="button" value="Send"/>
						</form>
						
						<?php
						
						if(array_key_exists('button2', $_POST)) {
						
							$mail = email($_POST["email"], $_POST["name"], $amoun, $txid);							
							
							if($mail->send()){
								echo '<p>Your message has been sent</p>';
							}
							else{
								echo 'Message could not be sent.';
								echo 'Mailer Error: ' . $mail->ErrorInfo;
							}
						}
					}
					else {
						echo '<p>Your donation has been detected and will be confirmed once it has 10 confirmations.</p>';
						
						?>
						<button onClick="window.location.reload();">Refresh Page</button>
						<?php
						
					}
					$current = TRUE;
				}		
				
			}
			if ($current == FALSE) {
				echo '<p>Transaction not found. Please check your TXID is correct or wait for your donation to be detected.</p>';
				
				?>
				<button onClick="window.location.reload();">Refresh Page</button>
				<?php
			}
			
		}
		
		?>
		
	</body>
</html>