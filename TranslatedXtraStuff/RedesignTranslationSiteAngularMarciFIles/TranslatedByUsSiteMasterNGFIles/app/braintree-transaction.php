<?php
/**
 * Returns Braintree token
 */

$postdata = json_decode( file_get_contents( 'php://input' ) );

header('Content-type:application/json;charset=utf-8');

require_once( 'config.php' );
require_once( 'Braintree/lib/Braintree.php' );

Braintree_Configuration::environment( $braintree['environment'] );
Braintree_Configuration::merchantId( $braintree['merchantId'] );
Braintree_Configuration::publicKey( $braintree['publicKey'] );
Braintree_Configuration::privateKey( $braintree['privateKey'] );

$result = Braintree_Transaction::sale( [
	'amount' => round( $postdata->total, 2 ),
	'paymentMethodNonce' => $postdata->nonce,
	'options' => array(
		'submitForSettlement' => true
	)
] );

if ( $result->success == true ) {
	$answer = true;
} else {
	$answer = false;
}

echo json_encode( $answer );
