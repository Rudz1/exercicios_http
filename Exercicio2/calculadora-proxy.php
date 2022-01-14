<?php

header("Access-Control-Allow-Methods: *");
header("Content-Type: application/x-www-form-urlencoded");

$curl = curl_init();

$arrayParaJson = json_encode($_POST);

curl_setopt_array($curl, [
 CURLOPT_URL => 'http://localhost/Exercicio1/calculadora.php',
 CURLOPT_RETURNTRANSFER => true,
 CURLOPT_CUSTOMREQUEST => 'POST',
 CURLOPT_POSTFIELDS => $arrayParaJson,
 CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
]);

$resposta = json_decode(curl_exec($curl));
http_response_code(curl_getinfo($curl, CURLINFO_HTTP_CODE));
echo !$resposta->error ? $resposta->result : $resposta->message;

curl_close($curl);


    

