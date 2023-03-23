<?php

namespace BoasPraticas\Leilao\Service;

use BoasPraticas\Leilao\Dao\LeilaoDao;

class Encerrador
{
    public function encerra()
    {
        $dao = new LeilaoDao();
        $leiloes = $dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                $leilao->finalizaLeilao();
                $dao->atualiza($leilao);
            }
        }
    }
}
