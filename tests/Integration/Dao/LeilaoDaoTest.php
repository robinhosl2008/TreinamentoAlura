<?php

namespace BoasPraticas\Leilao\Tests\Integration\Dao;

use BoasPraticas\Leilao\Dao\LeilaoDao;
use BoasPraticas\Leilao\Model\Leilao;
use PDO;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    /** @var PDO */
    private static $pdo;
    /** @var LeilaoDao */
    private static $leilaoDao;

    /**
     * Executa antes da classe ser instanciada.
     */
    public static function setUpBeforeClass(): void
    {
    }

    /** 
     * Executa antes de qualquer teste nessa classe.
     * 
     * @return void 
     */
    public function setUp(): void
    {
        self::$pdo = new PDO('sqlite::memory:');
        self::$pdo->exec(
            "CREATE TABLE leilao (
                id INTEGER PRIMARY KEY,
                descricao TEXT,
                finalizado BOOLEAN,
                dataInicio TEXT
            )");
        self::$pdo->beginTransaction();
        self::$leilaoDao = new LeilaoDao(self::$pdo);

        // Arrange
        $leilao = new Leilao("Variant 0km teste-dev");
        self::$leilaoDao->salva($leilao);
        
        $leilao = new Leilao("Fiat 147 0km teste-dev");
        $leilao->finalizaLeilao();
        self::$leilaoDao->salva($leilao);
    }

    public function testInserirEBuscarLeiloesNãoFinalizadosDevemFuncionar(): void
    {
        $nFinalizado = self::$leilaoDao->recuperarNaoFinalizados(" AND descricao LIKE '% teste-dev'");
        
        // Assert
        self::assertCount(1, $nFinalizado);
        self::assertContainsOnlyInstancesOf(Leilao::class, $nFinalizado);
        self::assertSame("Variant 0km teste-dev", $nFinalizado[0]->recuperaDescricao());
    }

    public function testInserirEBuscarLeiloesFinalizadosDevemFuncionar(): void
    {
        $finalizado = self::$leilaoDao->recuperarFinalizados(" AND descricao LIKE '% teste-dev'");
        
        // Assert
        self::assertCount(1, $finalizado);
        self::assertContainsOnlyInstancesOf(Leilao::class, $finalizado);
        self::assertSame("Fiat 147 0km teste-dev", $finalizado[0]->recuperaDescricao());
    }

    public function testVerificaSeLeilaoEstaSendoEditado()
    {
        self::$pdo->exec("DELETE FROM leilao WHERE id > 0");
        $leilao = new Leilao("Robson teste-dev");
        $leilao->finalizaLeilao();
        self::$leilaoDao->salva($leilao);
        
        self::$leilaoDao->atualiza($leilao);

        $leiloes = self::$leilaoDao->recuperarFinalizados();

        self::assertCount(1, $leiloes);
        self::assertEquals("Robson teste-dev", $leiloes[0]->recuperaDescricao());
        self::assertTrue($leiloes[0]->recuperaStatusLeilao());
    }

    /** 
     * Executa depois de todos os testes nessa classe
     * 
     * @return void 
     */
    public function tearDown(): void
    {
        self::$pdo->rollBack();
    }
}