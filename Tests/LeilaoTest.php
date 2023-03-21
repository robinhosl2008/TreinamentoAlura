<?php

namespace Alura\Leilao\Tests;

use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    private $leilao;

    public function setUp(): void
    {
        $this->leilao = new Leilao("Fiat 147 0Km");
    }

    public function testVerificaDescricaoDoLeilao()
    {
        $descricaoLeilao = $this->leilao->recuperaDescricao();

        self::assertEquals("Fiat 147 0Km", $descricaoLeilao);
    }

    public function testVerificarSeUmLanceFoiParaOLeilao()
    {
        $usuario = new Usuario("Robson Lourenço");
        $lance = new Lance($usuario, 1000);
        $this->leilao->recebeLance($lance);

        self::assertEquals("object", gettype($this->leilao->recuperaLances()[0]));
        self::assertEquals(1000, $this->leilao->recuperaLances()[0]->recuperaValor());
        self::assertEquals("object", gettype($this->leilao->recuperaLances()[0]->recuperaUsuario()));
        self::assertEquals("Robson Lourenço", $this->leilao->recuperaLances()[0]->recuperaUsuario()->recuperaNome());
    }
}