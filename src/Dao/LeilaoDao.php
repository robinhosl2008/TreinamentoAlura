<?php

namespace BoasPraticas\Leilao\Dao;

use BoasPraticas\Leilao\Infra\ConnectionCreator;
use BoasPraticas\Leilao\Model\Leilao;
use PDO;

/**
 * Summary of Leilao
 */
class LeilaoDao
{
    private $con;

    public function __construct(PDO $pdo = null)
    {
        if ($pdo == null) {
            $connector = new ConnectionCreator();
            $pdo = $connector->getConnection();
        }

        $this->con = $pdo;
    }

    /**
     * Summary of salva
     * @param Leilao $leilao
     * @return void
     */
    public function salva(Leilao $leilao): void
    {
        $sql = 'INSERT INTO leilao (descricao, finalizado, dataInicio) VALUES (?, ?, ?)';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(1, $leilao->recuperaDescricao(), \PDO::PARAM_STR);
        $stm->bindValue(2, $leilao->recuperaStatusLeilao(), \PDO::PARAM_BOOL);
        $stm->bindValue(3, $leilao->recuperarDataInicio()->format('Y-m-d'));
        $stm->execute();
        $leilao->insereId($this->con->lastInsertId());
    }

    /**
     * Remove os leilões da base de dados;
     *
     * @param string $str Texto utilizado na busca pela descrição do leilão.
     * @param int $id ID do leilão.
     * @return void
     **/
    public function removeLeiloes(string $str = null, int $id = null): void
    {
        $str = ($str != null) ? "AND descricao LIKE '%$str%'" : "";
        $id = ($id != null) ? "AND id = $id" : "";

        $sql = "DELETE FROM leilao WHERE finalizado = 1 $str $id";
        $stm = $this->con->prepare($sql);
        $stm->execute();
    }

    /**
     * @return Leilao[]
     */
    public function recuperarNaoFinalizados(string $filtro = null): array
    {
        return $this->recuperarLeiloesSeFinalizado(false, $filtro);
    }

    /**
     * @return Leilao[]
     */
    public function recuperarFinalizados(string $filtro = null): array
    {
        return $this->recuperarLeiloesSeFinalizado(true, $filtro);
    }

    /**
     * @return Leilao[]
     */
    public function recuperarLeiloesSeFinalizado(bool $finalizado, string $filtro = null): array
    {
        $sql = 'SELECT * FROM leilao WHERE finalizado = ' . ($finalizado ? 1 : 0) . ($filtro ? $filtro : "");
        $stm = $this->con->query($sql, PDO::FETCH_ASSOC);

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
        $sql = 'UPDATE leilao SET descricao = :descricao, dataInicio = :dataInicio, finalizado = :finalizado WHERE id = :id';
        $stm = $this->con->prepare($sql);
        $stm->bindValue(':descricao', $leilao->recuperaDescricao());
        $stm->bindValue(':dataInicio', $leilao->recuperarDataInicio()->format('Y-m-d'));
        $stm->bindValue(':finalizado', $leilao->recuperaStatusLeilao(), \PDO::PARAM_BOOL);
        $stm->bindValue(':id', $leilao->recuperarId(), \PDO::PARAM_INT);
        $stm->execute();
    }
}
