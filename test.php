<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('src/jsonRPCClient.php');
require_once('src/daemonRPC.php');

$daemonRPC = new daemonRPC('127.0.0.1', 28081); // Change to match your daemon (monerod) IP address and port; 18081 is the default port for mainnet, 28081 for testnet, 38081 for stagenet
// $daemonRPC = new daemonRPC(['host' => '127.0.0.1', 'port' => 28081]) // Passing parameters in as array; parameters can be in any order and all are optional.
$getblockcount = $daemonRPC->getblockcount();
$on_getblockhash = $daemonRPC->on_getblockhash(42069);
// $getblocktemplate = $daemonRPC->getblocktemplate('9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 60);
// $submitblock = $daemonRPC->submitblock($block_blob);
$getlastblockheader = $daemonRPC->getlastblockheader();
// $getblockheaderbyhash = $daemonRPC->getblockheaderbyhash('fc7ba2a76071f609e39517dc0388a77f3e27cc2f98c8e933918121b729ee6f27');
// $getblockheaderbyheight = $daemonRPC->getblockheaderbyheight(696969);
// $getblock_by_hash = $daemonRPC->getblock_by_hash('fc7ba2a76071f609e39517dc0388a77f3e27cc2f98c8e933918121b729ee6f27');
// $getblock_by_height = $daemonRPC->getblock_by_height(696969);
$get_connections = $daemonRPC->get_connections();
$get_info = $daemonRPC->get_info();
// $hardfork_info = $daemonRPC->hardfork_info();
// $setbans = $daemonRPC->setbans('8.8.8.8');
// $getbans = $daemonRPC->getbans();

require_once('src/walletRPC.php');

$walletRPC = new walletRPC('127.0.0.1', 28083); // Change to match your wallet (monero-wallet-rpc) IP address and port; 18083 is the customary port for mainnet, 28083 for testnet, 38083 for stagenet
// $daemonRPC = new walletRPC(['host' => '127.0.0.1', 'port' => 28081]) // Passing parameters in as array; parameters can be in any order and all are optional.
// $create_wallet = $walletRPC->create_wallet('monero_wallet', ''); // Creates a new wallet named monero_wallet with no passphrase.  Comment this line and edit the next line to use your own wallet
$open_wallet = $walletRPC->open_wallet('monero_wallet', '');
$get_address = $walletRPC->get_address();
// $get_accounts = $walletRPC->get_accounts();
$get_balance = $walletRPC->get_balance();

function generateAddress() {
	$walletRPC = new walletRPC('127.0.0.1', 28083);
	$open_wallet = $walletRPC->open_wallet('monero_wallet', '');
	$create_address = $walletRPC->create_address(0, 'New subaddress');
	$close_wallet = $walletRPC->close_wallet();
	return $create_address;
}

function getAddress() {
	$walletRPC = new walletRPC('127.0.0.1', 28083);
	$open_wallet = $walletRPC->open_wallet('monero_wallet', '');
	$get_address = $walletRPC->get_address(0, 1);
	$close_wallet = $walletRPC->close_wallet();
	return $get_address;
}

function getBalance() {
	$walletRPC = new walletRPC('127.0.0.1', 28083);
	$open_wallet = $walletRPC->open_wallet('monero_wallet', '');
	$get_balance = $walletRPC->get_balance();
	$close_wallet = $walletRPC->close_wallet();
	return $get_balance;
}	

function getTransfers() {
	$walletRPC = new walletRPC('127.0.0.1', 28083);
	$open_wallet = $walletRPC->open_wallet('monero_wallet', '');
	$get_transfers = $walletRPC->get_transfers('all');
	$close_wallet = $walletRPC->close_wallet();
	return $get_transfers;
}
	
	
// $create_address = $walletRPC->create_address(0, 'This is an example subaddress label'); // Create a subaddress on account 0
// $tag_accounts = $walletRPC->tag_accounts([0], 'This is an example account tag');
// $get_height = $walletRPC->get_height();
// $transfer = $walletRPC->transfer(1, '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn'); // First account generated from mnemonic 'gang dying lipstick wonders howls begun uptight humid thirsty irony adept umpire dusted update grunt water iceberg timber aloof fudge rift clue umpire venomous thirsty'
// $transfer = $walletRPC->transfer(['address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'amount' => 1, 'priority' => 1]); // Passing parameters in as array
// $transfer = $walletRPC->transfer(['destinations' => ['amount' => 1, 'address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'amount' => 2, 'address' => 'BhASuWq4HcBL1KAwt4wMBDhkpwseFe6pNaq5DWQnMwjBaFL8isMZzcEfcF7x6Vqgz9EBY66g5UBrueRFLCESojoaHaTPsjh'], 'priority' => 1]); // Multiple payments in one transaction
// $sweep_all = $walletRPC->sweep_all('9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn');
// $sweep_all = $walletRPC->sweep_all(['address' => '9sZABNdyWspcpsCPma1eUD5yM3efTHfsiCx3qB8RDYH9UFST4aj34s5Ygz69zxh8vEBCCqgxEZxBAEC4pyGkN4JEPmUWrxn', 'priority' => 1]);
// $get_transfers = $walletRPC->get_transfers('in', true);
// $incoming_transfers = $walletRPC->incoming_transfers('all');
// $mnemonic = $walletRPC->mnemonic();
$close_wallet = $walletRPC->close_wallet();

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
		<h1>XMR</h1>
		
		<h2>Generate Address</h2>
		
		<p>Please use the button below to generate a unique address for you to make a donation</p>
		
		<form method="post"> 
        <input type="submit" name="button1" class="button" value="Generate Address"/>
		</form>
		
		<?php
        if(array_key_exists('button1', $_POST)) { 
            $addressC = generateAddress(); 
			print_r($addressC);
			$subaddress = array_values($addressC)[0];
			echo $subaddress;
			//echo @implode(' ', $addressC);
			$index = array_values($addressC)[1];
        }     
		?> 
				
		<h2>Convert GBP to XMR</h2>
		
		<p>The tool below allows you to convert British Pounds to Monero</p>
		
		<?php
		//$address = getAddress();
		//echo @implode(" ",$address);
		$priceJSON = file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=XMR&tsyms=GBP");
		$priceOBJ = json_decode($priceJSON);
		$priceArray = (array) $priceOBJ;
		$price =  array_values($priceArray)[0];
		echo '<p>Price per XMR £' . $price . '</p>';
		?>
		
		<form method = "post">
		<label>Enter Amount of XMR</label>
		<input type="text" id="xmr_amount" name="amount">
		<p>OR</p>
		<label>Enter Donation Size in GBP</label>
		<input type="text" id="xmr_price" name="price"><br><br>
		<input type="submit" name = "button2" value="Submit">
		</input>
		
		<?php
		if(array_key_exists('button2', $_POST)) {
			if (empty($_POST["amount"])) {
				echo '<p>Amount = ' . ($_POST["price"]/$price) . ' XMR</p>';
				echo '<p>Cost = £' . $_POST["price"] . '</p>';
			}
			else {
				echo '<p>Amount = ' . $_POST["amount"] . '</p>';
				echo '<p>Cost = £' . ($price * $_POST["amount"]) . '</p>';
			}
		}
		?>
		
		<h2>Incoming Transfers</h2>
		
		<?php
		$one = 1000000000000;
		$transfers = getTransfers();
		print_r($transfers);
		$final =  end($transfers);
		
		?>
				
		<h3>Current Unconfirmed Transactions</h3>
		
		<p>Below are all current unconfirmed transactions. Once they have received 10 confirmations they will be confirmed and unreversible</p>
		
		<?php
		
		$current = FALSE;
		
		for ($x = 0; $x < count($final); $x++) {
			$am = array_values($final)[$x];
			$con = array_values($am)[3];
			$amoun = array_values($am)[1];
			$time = array_values($am)[13];
			if ($con < 10) {
				echo '<div class="corners">';
				echo '<p> Amount: ' . ($amoun/$one) . ' XMR</p>';
				echo '<p> Confirmations: ' . $con . '</p>';
				echo '<p> Time: ' .date('d F Y H:i:s', $time) . '</p>';
				echo '</div>';
				$current = TRUE;
			}
		}
		if ($current == FALSE) {
			echo '<p>No current unconfirmed transactions</p>';
		}
		
		/* echo '<h2>Completed Transactions</h2>';
				
		for ($x = 0; $x < count($final); $x++) {
			$am = array_values($final)[$x];
			$con = array_values($am)[3];
			$amoun = array_values($am)[1];
			$time = array_values($am)[13];
			if ($con >= 10) {
				echo '<div class="corners">';
				echo '<p> Amount: ' . ($amoun/$one) . ' XMR</p>';
				echo '<p> Confirmations: ' . $con . '</p>';
				echo '<p> Time: ' .date('d F Y H:i:s', $time) . '</p>';
				echo '</div>';
			}
		} */
		?>
		
		<h3>Confirmed Transactions in the last 24 hours</h3>
		
		<p>Below are all transaction that have been completed in the previous 24 hours. To view all transactions <a href="history.php">click here</a></p>
		
		<?php
		
		$yesterday = time() - 86400;
		$last = FALSE;
		
		for ($x = 0; $x < count($final); $x++) {
			$am = array_values($final)[$x];
			$con = array_values($am)[3];
			$amoun = array_values($am)[1];
			$time = array_values($am)[13];
			if ($con >= 10) {
				if ($time >= $yesterday) {
					echo '<div class="corners">';
					echo '<p> Amount: ' . ($amoun/$one) . ' XMR</p>';
					echo '<p> Confirmations: ' . $con . '</p>';
					echo '<p> Time: ' .date('d F Y H:i:s', $time) . '</p>';
					echo '</div>';
					$last = TRUE;
				}
			}
		}
		
		if ($last == FALSE) {
			echo 'No transactions in the last 24 hours';
		}
		?>
		
		<h2>Balance</h2>
		
		<?php
		$balanceArray = getBalance();
		
		$balance = array_values($balanceArray)[0];
		echo '<p>Total donated: ' . $balance/$one . ' XMR</p>';
		
		?>
		
	</body>
</html>