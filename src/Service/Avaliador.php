<?php

namespace BoasPraticas\Leilao\Service;

use BoasPraticas\Leilao\Model\Leilao;
use DomainException;

class Avaliador
{
    /** @var float */
    protected $maiorValor = -INF;
    /** @var float */
    protected $menorValor = INF;
    protected $maioresLances = [];
    
    public function avalia(Leilao $leilao): void
    {
        if ($leilao->recuperaStatus()) {
            throw new DomainException("Leiloeiro não pode avaliar um leilão que já foi finalizado.");
        }

        if (empty($leilao->recuperaLances())) {
            throw new DomainException("Não é possível avaliar um leilão vazio.");
        }

        foreach ($leilao->recuperaLances() as $lance) {
            if ($lance->recuperaValor() > $this->maiorValor) {
                $this->maiorValor = $lance->recuperaValor();
            }
            
            if ($lance->recuperaValor() < $this->menorValor) {
                $this->menorValor = $lance->recuperaValor();
            }
        }

        $lances = $leilao->recuperaLances();

        usort($lances, function($a, $b) {
            return $b->recuperaValor() - $a->recuperaValor();
        });

        $this->maioresLances = array_slice($lances, 0, 3);
    }

    /**
     * Recupera o maior valor entre os lances.
     *
     * @return float
     **/
    public function recuperaMaiorValor(): float
    {
        return $this->maiorValor;
    }

    /**
     * Recupera o menor valor entre os lances.
     *
     * @return float
     **/
    public function recuperaMenorValor(): float
    {
        return $this->menorValor;
    }

    public function recuperaMaioresLances(): array
    {
        return $this->maioresLances;
    }
}