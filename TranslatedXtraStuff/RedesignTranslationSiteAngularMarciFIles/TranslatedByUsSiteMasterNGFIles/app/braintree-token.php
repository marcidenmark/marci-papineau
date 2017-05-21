<?php
/**
 * Returns Braintree token
 */

require_once( 'config.php' );
require_once( 'Braintree/lib/Braintree.php' );

header('Content-type:application/json;charset=utf-8');

Braintree_Configuration::environment( $braintree['environment'] );
Braintree_Configuration::merchantId( $braintree['merchantId'] );
Braintree_Configuration::publicKey( $braintree['publicKey'] );
Braintree_Configuration::privateKey( $braintree['privateKey'] );

echo json_encode( Braintree_ClientToken::generate() );
