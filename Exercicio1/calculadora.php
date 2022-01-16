<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json;charset=utf-8');


try {

    if($_SERVER["REQUEST_METHOD"] != 'POST'){
        throw new \Exception('{"error":"method_not_allowed","message":"apenas requisições HTTP são aceitas"}', 400);
    }
    
    if($_SERVER['CONTENT_TYPE'] != 'application/json'){
        throw new \Exception('{"error":"content_type_not_allowed","message":"apenas requisições do tipo application/json"}', 400);
    }

    $calculadora = json_decode(file_get_contents('php://input'));
    
    if (!$calculadora) {
        throw new \Exception('{"error":"invalid_request","message":"os dados informados não são validos"}', 422);
    }
    if (!isset($calculadora->numero1)) {
        throw new \Exception('{"error":"invalid_request","message":"informe o primeiro valor(numero1) "}', 422);
    }
    
    if (!is_numeric($calculadora->numero1)) {
        throw new \Exception('{"error":"invalid_request","message":"o primeiro valor deve ser um numero Ex: numero1 = 10"}', 422);
    }
    
    if (!isset($calculadora->numero2)) {
        throw new \Exception('{"error":"invalid_request","message":"Informe o segundo valor(numero2)"}', 422);
    }
    
    if (!is_numeric($calculadora->numero2)) {
        throw new \Exception('{"error":"invalid_request","message":"O segundo valor deve ser um numero Ex: numero2 = 10"}', 422);
    }
    
    if (!$calculadora->operacao) {
        throw new \Exception('{"error":"invalid_request","message":"Informe a operação(somar, subtrair, multiplicar, dividir)"}', 422);
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
                throw new \Exception('{"error":"division_by_zero","message":"o numero 2 não pode ser zero"}', 422);
            }
            $resultado = '{"result" : ' . ($calculadora->numero1 / $calculadora->numero2) . '}';
            break;
        default:
            throw new \Exception('{"error" : "invalid_operator", "message" : "Operador informado é invalido, operadores validos(somar, subtrair, multiplicar, dividir)"}', 422);
            break;
    }

    echo $resultado;
} catch (\Exception $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}





