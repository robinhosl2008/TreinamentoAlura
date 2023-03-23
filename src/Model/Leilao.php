<?php

namespace BoasPraticas\Leilao\Model;

use DomainException;
use Exception;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /** @var bool */
    private $finalizado = false;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {   
        // Usuário não pode dar dois lances seguidos.
        if (!empty($this->lances)) {
            if ($this->lanceAtualIgualUltimoLance($lance)) {
                throw new DomainException("Usuário não pode dar dois lances seguidos.");
            }

            // Usuário não pode dar mas de 5 lances no mesmo lailão.
            $totalLancesUsuario = $this->usuarioAtualLimiteDeLances($lance);

            if ($totalLancesUsuario >= 5) {
                throw new DomainException("Usuário não pode dar mais de 5 lances no mesmo leilão.");
            }
        }

        $this->lances[] = $lance;
    }

    private function lanceAtualIgualUltimoLance(Lance $lance)
    {
        $nomeLanceAtual = $lance->recuperaUsuario()->recuperaNome();
        $nomeUltimoLance = $this->lances[count($this->lances) - 1]->recuperaUsuario()->recuperaNome();
        
        return ($nomeLanceAtual == $nomeUltimoLance) ?? true;
    }

    private function usuarioAtualLimiteDeLances(Lance $lance): int
    {
        $usuario = $lance->recuperaUsuario();

        return array_reduce(
            $this->lances,
            function(int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->recuperaUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }

                return $totalAcumulado;
            },
            0
        );
    }

    /**
     * @return Lance[]
     */
    public function recuperaLances(): array
    {
        return $this->lances;
    }

    public function recuperaDescricao(): string
    {
        return $this->descricao;
    }

    public function finalizaLeilao()
    {
        $this->finalizado = true;
    }

    public function recuperaStatus()
    {
        return $this->finalizado;
    }
}
