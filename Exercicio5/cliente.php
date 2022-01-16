<?php

$postdata = json_encode($_POST);

$opts = array(
    'http' => array(
        'method' => 'POST',
        'header' =>
            'Content-type: application/json'."\r\n",
            
        'content' => $postdata,
        'ignore_errors' => true
    )
);

$context = stream_context_create($opts);

$result = file_get_contents('http://localhost/Exercicio1/calculadora.php', false, $context);

$status_line = $http_response_header[0];
preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $stats);
http_response_code($stats[1]);

$resposta = json_decode($result);

echo isset($resposta->error) ? $resposta->message : "Resultado = ".$resposta->result;

