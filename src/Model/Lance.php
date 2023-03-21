<?php

namespace BoasPraticas\Leilao\Model;

class Lance
{
    /** @var Usuario */
    private $usuario;
    /** @var float */
    private $valor;

    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }

    public function recuperaUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function recuperaValor(): float
    {
        return $this->valor;
    }
}
