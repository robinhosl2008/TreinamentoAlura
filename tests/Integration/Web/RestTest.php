<?php

namespace BoasPraticas\Leilao\Tests\Integration\Web;

use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
    public function testApiRestDeveRetornarUmArrayDeLeiloes()
    {
        $resposta = file_get_contents("http://localhost:8000/rest.php");

        self::assertStringContainsString('200 OK', $http_response_header[0]);
        self::assertIsArray(json_decode($resposta));
    }
}