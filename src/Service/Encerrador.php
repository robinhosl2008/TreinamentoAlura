<?php

namespace BoasPraticas\Leilao\Service;

use BoasPraticas\Leilao\Dao\LeilaoDao;

class Encerrador
{
    /** @var LeilaoDao */
    protected $dao;

    /**
     * Método construtor.
     **/
    public function __construct(LeilaoDao $dao)
    {
        $this->dao = $dao;
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                $leilao->finalizaLeilao();
                $this->dao->atualiza($leilao);
            }
        }
    }
}
