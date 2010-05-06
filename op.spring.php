<?php

// ref: http://gist.github.com/raw/373487/deec0db86b6692536e9e171698e6b8fdf1c9e6ef/gistfile1.php
function yql( $query )
{
    $params = array(
        'q' => $query,
        'debug' => 'true',
        'diagnostics' => 'true',
        'format' => 'json',
        'callback' => ''
    );
    $url = 'https://query.yahooapis.com/v1/public/yql?'.http_build_query( $params );
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $json = curl_exec( $ch );
    $response = json_decode( $json );
    curl_close( $ch );
    return $response;
}

$openid = filter_var( $_GET['openid'], FILTER_SANITIZE_STRING );
$appid = filter_var( $_GET['appid'], FILTER_SANITIZE_STRING );

//look up return to
$return_to = 'http://example.com/';

$query = sprintf( "use '%s' as openid; select * from openid where id='%s' and return_to='%s'",
    'http://gist.github.com/yql/yql-tables/raw/master/openid/openid.xml',
	$openid,
	$return_to
);
$response = yql( $query );
if ( $response && $response->query->results->success ) {
    header( "Location: ".$response->query->results->success );
}
?>