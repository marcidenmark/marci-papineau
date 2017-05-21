<?php
/**
 * Returns languages as a JSON object
 */

require_once( 'config.php' );

header('Content-type:application/json;charset=utf-8');

echo json_encode( $langs );
