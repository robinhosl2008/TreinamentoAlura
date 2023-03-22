<?php

namespace BoasPraticas\Leilao\Model;

use Exception;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

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
                return;
            }

            // Usuário não pode dar mas de 5 lances no mesmo lailão.
            $totalLancesUsuario = $this->usuarioAtualLimiteDeLances($lance);

            if ($totalLancesUsuario >= 5) {
                return;
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
}
