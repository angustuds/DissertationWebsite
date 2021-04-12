<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('src/jsonRPCClient.php');
require_once('src/daemonRPC.php');

$daemonRPC = new daemonRPC('127.0.0.1', 18081); // Change to match your daemon (monerod) IP address and port; 18081 is the default port for mainnet, 28081 for testnet, 38081 for stagenet
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

$walletRPC = new walletRPC('127.0.0.1', 18083); // Change to match your wallet (monero-wallet-rpc) IP address and port; 18083 is the customary port for mainnet, 28083 for testnet, 38083 for stagenet
// $daemonRPC = new walletRPC(['host' => '127.0.0.1', 'port' => 28081]) // Passing parameters in as array; parameters can be in any order and all are optional.
// $create_wallet = $walletRPC->create_wallet('DissertationWallet', ''); // Creates a new wallet named monero_wallet with no passphrase.  Comment this line and edit the next line to use your own wallet
$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
$get_address = $walletRPC->get_address();
// $get_accounts = $walletRPC->get_accounts();
$get_balance = $walletRPC->get_balance();

function generateAddress() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$create_address = $walletRPC->create_address(0, 'New subaddress');
	$close_wallet = $walletRPC->close_wallet();
	return $create_address;
}

function getAddress() {
	$walletRPC = new walletRPC('127.0.0.1', 18083);
	$open_wallet = $walletRPC->open_wallet('DissertationWallet', 'Password');
	$get_address = $walletRPC->get_address(0, 1);
	$close_wallet = $walletRPC->close_wallet();
	return $get_address;
}

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
	
	<h2>Previous Transactions</h2>
	
	<p>Below is a list of all completed transactions</p>
	
	<?php
		$one = 1000000000000;
		$transfers = getTransfers();
		//print_r($transfers);
		$final =  end($transfers);
		
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
		}
	?>
	
	</body>
	
</html>