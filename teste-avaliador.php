<?php

require_once("vendor/autoload.php");

use BoasPraticas\Leilao\Model\Usuario;
use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Service\Avaliador;

$leilao = new Leilao('Fiat 147 0KM');

$maria = new Usuario("Maria");
$joao = new Usuario("João");

$lance1 = new Lance($maria, 1000);
$leilao->recebeLance($lance1);

$lance2 = new Lance($joao, 1200);
$leilao->recebeLance($lance2);

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->recuperaMaiorValor();

echo "O maior valor neste leilão foi de R$" . $maiorValor . PHP_EOL;