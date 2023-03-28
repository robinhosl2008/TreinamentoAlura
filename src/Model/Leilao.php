<?php

namespace BoasPraticas\Leilao\Model;

use Exception;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    /** @var bool */
    private $finalizado;
    /** @var \DateTimeInterface  */
    private $dataInicio;
    /** @var int */
    private $id;

    public function __construct(string $descricao, \DateTimeImmutable $dataInicio = null, int $id = null)
    {
        $this->descricao = $descricao;
        $this->finalizado = false;
        $this->lances = [];
        $this->dataInicio = $dataInicio ?? new \DateTimeImmutable();
        $this->id = $id;
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

    public function finalizaLeilao(): void
    {
        $this->finalizado = true;
    }
    
    public function recuperaStatusLeilao(): bool
    {
        return $this->finalizado;
    }

    public function recuperarDataInicio(): \DateTimeInterface
    {
        return $this->dataInicio;
    }

    public function temMaisDeUmaSemana(): bool
    {
        $hoje = new \DateTime();
        $intervalo = $this->dataInicio->diff($hoje);

        return $intervalo->days > 7;
    }

    public function insereId(int $id): void
    {
        $this->id = $id;
    }

    public function recuperarId(): int
    {
        return $this->id;
    }
}
