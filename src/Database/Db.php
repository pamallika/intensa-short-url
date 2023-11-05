<?php

namespace Projects\Intensa\Database;

use Exception;
use PDO;

class Db
{
    private string $host;
    private int $port;
    private string $name;
    private string $user;
    private string $password;

    public function __construct()
    {
        //getenv и $_ENV - не видят переменные окружения, пока оставлю так
        //(возможно из-за того, что .env в корне а файл подключения к бд не в корне)
        $env = parse_ini_file('.env');
        $this->host = $env['DB_HOST'];
        $this->port = $env['DB_PORT'];
        $this->name = $env['DB_NAME'];
        $this->user = $env['DB_USER'];
        $this->password = $env['DB_PASSWORD'];
    }

    /**
     * @throws Exception
     */
    public function connect(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->name;port=$this->port;";
        try {
            return new PDO($dsn, $this->user, $this->password);
        } catch (\PDOException $e) {
            throw new Exception($e);
        }
    }

}