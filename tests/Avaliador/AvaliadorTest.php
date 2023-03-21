<?php

namespace Alura\Leilao\Tests\Avaliador;

use BoasPraticas\Leilao\Model\Usuario;
use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;
    
    /**
     * Esse método é protegido e deve retornar void.
     * Ele é executado sempre antes da execução de cada teste.
     */
    public function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorEncontraMaiorValorEntreLances($leilao)
    {
        // Act
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->recuperaMaiorValor();

        // Assert
        self::assertEquals(2900, $maiorValor);
    }
    
    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorEncontraMenorValorEntreLances($leilao)
    {
        // Act
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->recuperaMenorValor();

        // Assert
        self::assertEquals(800, $maiorValor);
    }
    
    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorEncontraMaioresValoresEntreLances($leilao)
    {
        // Act
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->recuperaMaioresLances();

        static::assertCount(3, $maioresLances);
        static::assertEquals($maioresLances[0]->recuperaValor(), $maioresLances[0]->recuperaValor());
        static::assertEquals($maioresLances[1]->recuperaValor(), $maioresLances[1]->recuperaValor());
        static::assertEquals($maioresLances[2]->recuperaValor(), $maioresLances[2]->recuperaValor());
    }

    /** Dados */
    public static function leilaoEmOrdemCrescente()
    {
        // Arrange
        $leilao = new Leilao("Fiat 157 0Km");

        $maria = new Usuario("Maria");
        $lance2 = new Lance($maria, 800);
        $leilao->recebeLance($lance2);
        
        $joao = new Usuario("João");
        $lance1 = new Lance($joao, 1000);
        $leilao->recebeLance($lance1);

        $robson = new Usuario("Robson");
        $lance3 = new Lance($robson, 1300);
        $leilao->recebeLance($lance3);

        $ana = new Usuario("Ana");
        $lance4 = new Lance($ana, 2900);
        $leilao->recebeLance($lance4);

        return [[$leilao]];
    }

    public static function leilaoEmOrdemDecrescente()
    {
        // Arrange
        $leilao = new Leilao("Fiat 157 0Km");

        $robson = new Usuario("Robson");
        $lance3 = new Lance($robson, 1300);
        $leilao->recebeLance($lance3);

        $joao = new Usuario("João");
        $lance1 = new Lance($joao, 1000);
        $leilao->recebeLance($lance1);

        $maria = new Usuario("Maria");
        $lance2 = new Lance($maria, 800);
        $leilao->recebeLance($lance2);

        $ana = new Usuario("Ana");
        $lance4 = new Lance($ana, 2900);
        $leilao->recebeLance($lance4);
        
        return [[$leilao]];
    }

    public static function leilaoEmOrdemAleatoria()
    {
        // Arrange
        $leilao = new Leilao("Fiat 157 0Km");

        $robson = new Usuario("Robson");
        $lance3 = new Lance($robson, 1300);
        $leilao->recebeLance($lance3);

        $maria = new Usuario("Maria");
        $lance2 = new Lance($maria, 800);
        $leilao->recebeLance($lance2);
        
        $joao = new Usuario("João");
        $lance1 = new Lance($joao, 1000);
        $leilao->recebeLance($lance1);

        $ana = new Usuario("Ana");
        $lance4 = new Lance($ana, 2900);
        $leilao->recebeLance($lance4);

        return [[$leilao]];
    }
}