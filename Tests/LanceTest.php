<?php

namespace BoasPraticas\Leilao\Tests;

use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LanceTest extends TestCase
{
    private $lance;

    public function setUp(): void
    {
        $usuario = new Usuario("Robson Lourenço");
        $this->lance = new Lance($usuario, 1000);
    }

    public function testVerificandoSeLanceTemUsuario()
    {
        $usuarioDoLance = $this->lance->recuperaUsuario();

        self::assertEquals("object", gettype($usuarioDoLance));
        self::assertEquals("Robson Lourenço", $usuarioDoLance->recuperaNome());
    }

    public function testVerificandoSeLanceTemValor()
    {
        $valorDoLance = $this->lance->recuperaValor();

        self::assertEquals(1000, $valorDoLance);
    }
}