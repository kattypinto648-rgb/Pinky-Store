<?php

class ApiController
{
    public function dolar(array $params = []): void
    {
        header('Content-Type: application/json');

        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 5,
                'user_agent' => 'PinkyStore/1.0'
            ]
        ]);

        $json = @file_get_contents('https://dolarapi.com/v1/dolares/oficial', false, $ctx);

        if ($json === false) {
            echo json_encode(['venta' => 0, 'error' => 'No disponible']);
            return;
        }

        echo $json;
    }
}