<?php

namespace BoasPraticas\Leilao\Tests;

use BoasPraticas\Leilao\Dao\LeilaoDao;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Encerrador;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class LeilaoDaoMock extends LeilaoDao
{
    private $leiloes = [];

    public function salva(Leilao $leilao): void
    {
        $this->leiloes[] = $leilao;
    }

    public function recuperarNaoFinalizados(): array
    {
        return array_filter($this->leiloes, function(Leilao $leilao) {
            return !$leilao->recuperaStatusLeilao();
        });
    }

    public function recuperarFinalizados(string $str): array
    {
        return array_filter($this->leiloes, function(Leilao $leilao) {
            return $leilao->recuperaStatusLeilao();
        });
    }

    public function atualiza(Leilao $leilao)
    {}
}

class EncerradorTest extends TestCase
{
    public function tearDown(): void
    {
        // $leilaoDao = new LeilaoDao();
        // $leilaoDao->removeLeiloes("teste-dev", null);
    }

    public function testLeilaoComMaisDeUmaSemanaDevemSerEncerrados()
    {
        // arrange
        $leilao2 = new Leilao(
            "Variant 1972 teste-dev",
            new DateTimeImmutable('10 days ago')
        );

        $leilao1 = new Leilao(
            "Fiat 147 teste-dev",
            new DateTimeImmutable("8 days ago")
        );

        $leilaoDao = new LeilaoDaoMock();
        $leilaoDao->salva($leilao1);
        $leilaoDao->salva($leilao2);

        // act
        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();
        $leiloes = $leilaoDao->recuperarFinalizados("teste-dev");

        // assert
        self::assertCount(2, $leiloes);
        self::assertEquals("Fiat 147 teste-dev", $leiloes[0]->recuperaDescricao());
        self::assertEquals("Variant 1972 teste-dev", $leiloes[1]->recuperaDescricao());
    }
}