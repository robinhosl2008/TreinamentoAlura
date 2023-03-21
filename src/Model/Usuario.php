<?php

namespace BoasPraticas\Leilao\Model;

class Usuario
{
    /** @var string */
    private $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function recuperaNome(): string
    {
        return $this->nome;
    }
}
