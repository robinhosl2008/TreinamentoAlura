<?php

namespace BoasPraticas\Leilao\Infra;

class ConnectionCreator
{
    private static $pdo = null;

    public static function getConnection(): \PDO
    {
        if (is_null(self::$pdo)) {
            $caminhoBanco = '/home/robson/PHP/boas-praticas/TreinamentoAlura/leilao.db';
            self::$pdo = new \PDO('sqlite:' . $caminhoBanco);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
}
