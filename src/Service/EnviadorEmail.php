<?php

namespace BoasPraticas\Leilao\Service;
use BoasPraticas\Leilao\Model\Leilao;

class EnviadorEmail
{
    public function notivicarPorEmail(Leilao $leilao)
    {
        $sucesso = mail(
            "robinhosl2008@gmail.com",
            "Leilão encerrado",
            "O leilão '{$leilao->recuperaDescricao()}' que está participando foi encerrado."
        );

        if (!$sucesso) {
            throw new \DomainException("Algo deu errado e seu e-mail não pode ser enviado.");
        }
    }
}