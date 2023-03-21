<?php

namespace Alura\Leilao\Tests\Service;

use BoasPraticas\Leilao\Model\Usuario;
use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    public function testMaiorValorEntreLances()
    {
        // Arrange
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario("Maria");
        $joao = new Usuario("João");

        $lance1 = new Lance($maria, 1000);
        $leilao->recebeLance($lance1);

        $lance2 = new Lance($joao, 1300);
        $leilao->recebeLance($lance2);

        // Act
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        $maiorValor = $leiloeiro->recuperaMaiorValor();

        // Assert
        self::assertEquals(1300, $maiorValor);
    }
    
    public function testMaiorValorEntreLancesDesc()
    {
        // Arrange
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario("Maria");
        $joao = new Usuario("João");

        $lance2 = new Lance($joao, 1300);
        $leilao->recebeLance($lance2);

        $lance1 = new Lance($maria, 1000);
        $leilao->recebeLance($lance1);

        // Act
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        $maiorValor = $leiloeiro->recuperaMaiorValor();

        // Assert
        self::assertEquals(1300, $maiorValor);
    }

    public function testRecuperaMenorValorEntreLances()
    {
        // Arrange
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario("Maria");
        $joao = new Usuario("João");

        $lance1 = new Lance($maria, 1000);
        $leilao->recebeLance($lance1);

        $lance2 = new Lance($joao, 1300);
        $leilao->recebeLance($lance2);

        // Act
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        $menorValor = $leiloeiro->recuperaMenorValor();

        // Assert
        self::assertEquals(1000, $menorValor);
    }

    public function testRecuperaMenorValorEntreLancesDesc()
    {
        // Arrange
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario("Maria");
        $joao = new Usuario("João");

        $lance2 = new Lance($joao, 1300);
        $leilao->recebeLance($lance2);

        $lance1 = new Lance($maria, 1000);
        $leilao->recebeLance($lance1);

        // Act
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        $menorValor = $leiloeiro->recuperaMenorValor();

        // Assert
        self::assertEquals(1000, $menorValor);
    }
}