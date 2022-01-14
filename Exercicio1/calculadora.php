<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


try {

    if($_SERVER["REQUEST_METHOD"] != 'POST'){
        throw new \Exception('{"error":"method_not_allowed","message":"apenas requisições HTTP são aceitas"}', 400);
    }
    
    if($_SERVER['CONTENT_TYPE'] != 'application/json'){
        throw new \Exception('{"error":"content_type_not_allowed","message":"apenas requisições do tipo application/json"}', 400);
    }

    $calculadora = json_decode(file_get_contents('php://input'));
    
    if (!$calculadora) {
        throw new \Exception('{"error":"invalid_request","message":"os dados informados não são validos"}', 400);
    }
    if (!isset($calculadora->numero1) || !is_numeric($calculadora->numero1)) {
        throw new \Exception('{"error":"invalid_request","message":"os dados informados não são validos"}', 400);
    }
    if (!isset($calculadora->numero2) || !is_numeric($calculadora->numero2)) {
        throw new \Exception('{"error":"invalid_request","message":"os dados informados não são validos"}', 400);
    }
    if (!$calculadora->operacao || !is_string($calculadora->operacao)) {
        throw new \Exception('{"error":"invalid_request","message":"os dados informados não são validos"}', 400);
    }
    
    $resultado = '';
    switch ($calculadora->operacao) {
        case "somar":
            $resultado = '{"result" : ' . ($calculadora->numero1 + $calculadora->numero2) . '}';
            break;
        case "subtrair":
            $resultado = '{"result" : ' . ($calculadora->numero1 - $calculadora->numero2) . '}';
            break;
        case "multiplicar":
            $resultado = '{"result" : ' . ($calculadora->numero1 * $calculadora->numero2) . '}';
            break;
        case "dividir":
            if($calculadora->numero2 == 0){
                throw new \Exception('{"error":"division_by_zero","message":"o numero 2 não pode ser zero"}', 400);
            }
            $resultado = '{"result" : ' . ($calculadora->numero1 / $calculadora->numero2) . '}';
            break;
        default:
            throw new \Exception('{"error" : "invalid_operator", "message" : "Operador informado é invalido"}', 400);
            break;
    }

    echo $resultado;
} catch (\Exception $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}





