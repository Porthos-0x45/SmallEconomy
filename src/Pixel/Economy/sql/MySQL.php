<?php

declare(strict_types=1);

namespace Pixel\Economy\sql;

use mysqli;
use Pixel\Economy\core\Main;
use pocketmine\utils\TextFormat;

class MySQL
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $port;
    public mysqli $conn;

    public function __construct(private Main $plugin)
    {
        $this->host = $plugin->config->getNested("database")["mysql"]["host"];
        $this->user = $plugin->config->getNested("database")["mysql"]["user"];
        $this->password = $plugin->config->getNested("database")["mysql"]["port"];
        $this->port = $plugin->config->getNested("database")["mysql"]["host"];
        $this->dbname = $plugin->config->getNested("database")["mysql"]["databaseName"];
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->connection()->connect_error) {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Connection error: " + $this->connection()->connect_error);

            $this->connection()->close();
        }
    }

    public function disconnect()
    {
        $this->connection()->close();
    }

    public function connection(): mysqli
    {
        return $this->conn;
    }

    public function isConnected(): bool
    {
        if ($this->connection()->connect_error) {
            $this->connection()->close();
            return false;
        }
    }
}
