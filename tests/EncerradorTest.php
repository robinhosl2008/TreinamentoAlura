<?php

namespace BoasPraticas\Leilao\Tests;

use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Encerrador;
use DateTimeImmutable;

class EncerradorTest
{
    public function testLeilaoComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $leilao1 = new Leilao(
            "Fiat 147",
            new DateTimeImmutable("8 dais ago")
        );

        $leilao2 = new Leilao(
            "Variant 1972",
            new DateTimeImmutable('10 days ago')
        );

        $encerrador = new Encerrador();
        $encerrador->encerra();
    }
}