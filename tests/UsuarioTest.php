<?php

namespace Alura\Leilao\Tests;

use BoasPraticas\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    private $nome;
    
    public function setUp(): void
    {
        $usuario = new Usuario("Robson");
        $this->nome = $usuario->recuperaNome();
    }

    public function testVerificaSeAdicionouNomeUsuarioCorreto()
    {
        self::assertEquals("Robson", $this->nome);
    }
}