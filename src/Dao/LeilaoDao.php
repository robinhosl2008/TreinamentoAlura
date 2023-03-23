<?php

namespace BoasPraticas\Leilao\Dao;

use BoasPraticas\Leilao\Infra\ConnectionCreator;
use BoasPraticas\Leilao\Model\Leilao;

/**
 * Summary of Leilao
 */
class LeilaoDao
{
    private $con;

    public function __construct()
    {
        $connector = new ConnectionCreator();
        $this->con = $connector->getConnection();
    }

    /**
     * Summary of salva
     * @param Leilao $leilao
     * @return void
     */
    public function salva(Leilao $leilao): void
    {
        $sql = 'INSERT INTO leiloes (descricao, finalizado, dataInicio) VALUES (?, ?, ?)';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $leilao->recuperaDescricao(), \PDO::PARAM_STR);
        $stm->bindValue(2, $leilao->recuperaStatusLeilao(), \PDO::PARAM_BOOL);
        $stm->bindValue(3, $leilao->recuperarDataInicio()->format('Y-m-d'));
        $stm->execute();
    }

    /**
     * @return Leilao[]
     */
    public function recuperarNaoFinalizados(): array
    {
        return $this->recuperarLeiloesSeFinalizado(false);
    }

    /**
     * @return Leilao[]
     */
    public function recuperarFinalizados(): array
    {
        return $this->recuperarLeiloesSeFinalizado(true);
    }

    /**
     * @return Leilao[]
     */
    private function recuperarLeiloesSeFinalizado(bool $finalizado): array
    {
        $sql = 'SELECT * FROM leiloes WHERE finalizado = ' . ($finalizado ? 1 : 0);
        $stm = $this->con->query($sql, \PDO::FETCH_ASSOC);

        $dados = $stm->fetchAll();
        $leiloes = [];
        foreach ($dados as $dado) {
            $leilao = new Leilao($dado['descricao'], new \DateTimeImmutable($dado['dataInicio']), $dado['id']);
            if ($dado['finalizado']) {
                $leilao->finalizaLeilao();
            }
            $leiloes[] = $leilao;
        }

        return $leiloes;
    }

    public function atualiza(Leilao $leilao)
    {
        $sql = 'UPDATE leiloes SET descricao = :descricao, dataInicio = :dataInicio, finalizado = :finalizado WHERE id = :id';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(':descricao', $leilao->recuperaDescricao());
        $stm->bindValue(':dataInicio', $leilao->recuperarDataInicio()->format('Y-m-d'));
        $stm->bindValue(':finalizado', $leilao->recuperaStatusLeilao(), \PDO::PARAM_BOOL);
        $stm->bindValue(':id', $leilao->recuperarId(), \PDO::PARAM_INT);
        $stm->execute();
    }
}
