<?php

use BoasPraticas\Leilao\Dao\LeilaoDao;
use BoasPraticas\Leilao\Infra\ConnectionCreator;
use BoasPraticas\Leilao\Model\Leilao;

require_once __DIR__ . '/vendor/autoload.php';

$pdo = new \PDO('sqlite::memory:');
$pdo->exec('create table leiloes (
    id INTEGER primary key,
    descricao TEXT,
    finalizado BOOL,
    dataInicio TEXT
);');

$leilaoDao = new LeilaoDao();

$leilao1 = new Leilao('Leilão 1 dev');
$leilao2 = new Leilao('Leilão 2 dev');
$leilao3 = new Leilao('Leilão 3 dev');
$leilao4 = new Leilao('Leilão 4 dev');

$leilaoDao->salva($leilao1);
$leilaoDao->salva($leilao2);
$leilaoDao->salva($leilao3);
$leilaoDao->salva($leilao4);

header('Content-type: application/json');
echo json_encode(array_map(function (Leilao $leilao) {
    return [
        'descricao' => $leilao->recuperaDescricao(),
        'estaFinalizado' => $leilao->recuperaStatusLeilao(),
    ];
}, $leilaoDao->recuperarNaoFinalizados()));

$leilao1->finalizaLeilao();
$leilaoDao->atualiza($leilao1);
$leilao2->finalizaLeilao();
$leilaoDao->atualiza($leilao2);
$leilao3->finalizaLeilao();
$leilaoDao->atualiza($leilao3);
$leilao4->finalizaLeilao();
$leilaoDao->atualiza($leilao4);

$leilaoDao->removeLeiloes(" dev");