Instructions for running Monero PHP Website

1.Download latest version of Monero Cli Wallet from https://www.getmonero.org/downloads/#cli

2.Setup a webserver with PHP, for example XMPP, Apache, or NGINX

3.Start the Monero Daemon and syncronise blockchain

monerod

4.Start the Monero wallet RPC interface on the testnet

monero-wallet-rpc --rpc-bind-port 18083 --disable-rpc-login --wallet-dir /path/to/wallet/directory