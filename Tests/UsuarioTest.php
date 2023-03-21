<?php

namespace Alura\Leilao\Tests;

use BoasPraticas\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    public function testVerificaSeAdicionouNomeUsuarioErrado()
    {
        $usuario = new Usuario("Robson");

        self::assertNotEquals("robson", $usuario->recuperaNome());
    }

    public function testVerificaSeAdicionouNomeUsuarioCorreto()
    {
        $usuario = new Usuario("Robson");

        self::assertEquals("Robson", $usuario->recuperaNome());
    }
}