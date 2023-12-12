<?php
include("functions/connectdb.php");
include("default_values.php");

function currentDate(){
    return date("Y-m-d h:i:s");
}

function test_input($con,$data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($con,$data);
    return $data;
}

function Get_user_info ($id){
    $con = connectdb();
    $query = 'SELECT * FROM users WHERE user_id = "'.$id.'"';
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);
    return $row;
}

function Fast_encrypt($message_to_encrypt) {
    $secret_key = "H4atkufCUYZ1ydyozLlxoJKgBcf0vp";
    $method = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted_message = openssl_encrypt($message_to_encrypt, $method, $secret_key, 0, $iv);
    return $encrypted_message;
}
function Fast_decrypt($encrypted_message) {
    $secret_key = "H4atkufCUYZ1ydyozLlxoJKgBcf0vp";
    $method = "AES-256-CBC";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $decrypted_message = openssl_decrypt($encrypted_message, $method, $secret_key, 0, $iv);
    return $decrypted_message;
}

?>