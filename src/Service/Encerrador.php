<?php

namespace BoasPraticas\Leilao\Service;

use BoasPraticas\Leilao\Dao\LeilaoDao;

class Encerrador
{
    /** @var LeilaoDao */
    protected $dao;
    /** @var EnviadorEmail */
    protected $enviadorEmail;

    /**
     * Método construtor.
     **/
    public function __construct(LeilaoDao $dao, EnviadorEmail $enviadorEmail)
    {
        $this->dao = $dao;
        $this->enviadorEmail = $enviadorEmail;
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                try {
                    $leilao->finalizaLeilao();
                    $this->dao->atualiza($leilao);
                    $this->enviadorEmail->notivicarPorEmail($leilao);
                } catch (\DomainException $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }
}
