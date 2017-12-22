<?php

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

// 認証用のキー
$consumer_key = 'UhuBsj54DbRnnHGQYaPywHmAi';
$consumer_secret = 'e1zSaGoQorVKzyW5W7U8LaAmkUsf3nnjdIHCxh45Y03bnnBj86';
$access_token = '42566916-5CI2BDtmiUQdWFKtTY6ajgxmLlYjURI4ftW2K88h3';
$access_token_secret = 'YHfdFB1RJxIcHQ6XOriX0GZhmZmFpNmI5wegaBIRDflIU';

// 各設定用の定数
$screen_name = 'realDonaldTrump';
$count = 10;
$method = 'GET';
$oauth_version = '1.0';
$oauth_signature_method = "HMAC-SHA1";

$oauth_signature_key = rawurlencode($consumer_secret) . '&' . rawurlencode($access_token_secret);
$oauth_nonce = microtime();
$oauth_timestamp = time();

$oauth_signature_params =
    'count=' . $count .
    '&oauth_consumer_key=' . $consumer_key .
    '&oauth_nonce=' . rawurlencode($oauth_nonce) .
    '&oauth_signature_method=' . $oauth_signature_method .
    '&oauth_timestamp=' . $oauth_timestamp .
    '&oauth_token=' . $access_token .
    '&oauth_version=' . $oauth_version .
    '&screen_name=' . rawurlencode($screen_name);

$oauth_signature_date = rawurlencode($method) . '&' . rawurlencode($url) . '&' . rawurlencode($oauth_signature_params);
$oauth_signature_hash = hash_hmac('sha1', $oauth_signature_date, $oauth_signature_key, true);
$oauth_signature = base64_encode($oauth_signature_hash);

$http_headers = array("Authorization: OAuth " . 'count=' . rawurlencode($count) . 
    ',oauth_consumer_key=' . rawurlencode($consumer_key) . 
    ',oauth_nonce='.str_replace(" ","+",$oauth_nonce) . 
    ',oauth_signature_method='. rawurlencode($oauth_signature_method) . 
    ',oauth_timestamp=' . rawurlencode($oauth_timestamp) . 
    ',oauth_token=' . rawurlencode($access_token) . 
    ',oauth_version=' . rawurlencode($oauth_version) . 
    ',screen_name=' . rawurlencode($screen_name) .
    ',oauth_signature='.rawurlencode($oauth_signature));

$url = $url . '?screen_name=' . rawurlencode($screen_name) . '&count=' . rawurlencode($count);

$status_res_json = submit_data_by_curl($url, array(), "get", $http_headers);
$res_str = json_decode($status_res_json, true);

foreach ($res_str as $twit_result){
    echo $twit_time = date("Y年m月d日 H時i分s秒",strtotime($twit_result['created_at'])) . "\n";
    echo $twit_result['text'] . "\n";
    echo "--------------------------\n";
}

// API実行
function submit_data_by_curl($url, $input_data, $method = "post", $http_headers = [])
{
    // 初期化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);       // URL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL証明書を検証しない
    curl_setopt($ch, CURLOPT_TIMEOUT , 5 ) ; // タイムアウトの秒数設定

    // HTTPヘッダー
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // レスポンスを文字列で受け取る

    // リクエストを実行
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
