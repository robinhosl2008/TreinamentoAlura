<?php

namespace BoasPraticas\Leilao\Tests\Unit;

use BoasPraticas\Leilao\Dao\LeilaoDao;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Encerrador;
use BoasPraticas\Leilao\Service\EnviadorEmail;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

// class LeilaoDaoMock extends LeilaoDao
// {
//     private $leiloes = [];

//     public function salva(Leilao $leilao): void
//     {
//         $this->leiloes[] = $leilao;
//     }

//     public function recuperarNaoFinalizados(): array
//     {
//         return array_filter($this->leiloes, function(Leilao $leilao) {
//             return !$leilao->recuperaStatusLeilao();
//         });
//     }

//     public function recuperarFinalizados(string $str): array
//     {
//         return array_filter($this->leiloes, function(Leilao $leilao) {
//             return $leilao->recuperaStatusLeilao();
//         });
//     }

//     public function atualiza(Leilao $leilao)
//     {}
// }

class EncerradorTest extends TestCase
{
        private $encerrador;
        private $leilaoFiat147;
        private $leilaoVariant;
        /** @var MockObject */
        private $enviadorEmail;

    public function setUp(): void
    {
        // arrange
        $this->leilaoVariant = new Leilao(
            "Variant 1972 teste-dev",
            new DateTimeImmutable('10 days ago')
        );

        $this->leilaoFiat147 = new Leilao(
            "Fiat 147 teste-dev",
            new DateTimeImmutable("8 days ago")
        );

        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([
            $this->leilaoVariant, 
            $this->leilaoFiat147
        ]);
        // $leilaoDao->expects($this->exactly(2))->method('atualiza')->withConsecutive(
        //     [$leilao1], 
        //     [$leilao2]
        // );

        // act
        $this->enviadorEmail = $this->createMock(EnviadorEmail::class);
        $this->encerrador = new Encerrador($leilaoDao, $this->enviadorEmail);
    }

    public function testLeilaoComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $this->encerrador->encerra();
        $leiloes = [$this->leilaoVariant, $this->leilaoVariant];

        // assert
        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->recuperaStatusLeilao());
        self::assertTrue($leiloes[1]->recuperaStatusLeilao());
    }

    public function testProcessoDeEncerramentoDeveContinuarOcorrendoMesmoComOErroDeEnvioDeEmail()
    {
        $e = new \DomainException("Erro ao enviar e-mail");
        $this->enviadorEmail
            ->expects($this->exactly(2))
            ->method('notivicarPorEmail')
            ->willThrowException($e);

        $this->encerrador->encerra();

    }

    public function testDeveEnviarEmailSomenteAposFinalizadoOLeilao()
    {
        $this->enviadorEmail
            ->expects($this->exactly(2))
            ->method('notivicarPorEmail')
            ->willReturnCallback(function (Leilao $leilao) {
                self::assertTrue($leilao->recuperaStatusLeilao());
            });

        $this->encerrador->encerra();
    }
}