<?php

declare(strict_types=1);

namespace Pixel\Economy\sql;

use mysqli;
use Pixel\Economy\core\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;


/************** THIS IS INCOMPLETED **************/

class SQLGetter
{
    private function __construct(private Main $plugin)
    {
    }

    public function createDatabase()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function accountExists($uuid): bool
    {
        $sql = "SELECT * FROM tokentbl WHERE UUID={$uuid}";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();

        return $this->plugin->data->exists($uuid);
    }

    public function getBalance(string $uuid): int
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();

        return $this->plugin->data->getNested(base64_encode($uuid))["MONEY"];
    }

    public function pay(string $self_uuid, string $send_uuid, int $amount)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function add(string $uuid, int $amount)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function set(string $uuid, int $amount)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function remove(string $uuid, int $amount)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function delete(string $uuid)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if ($this->conn->query($sql) === TRUE) {

            $this->plugin->getServer()->getLogger()->info(TextFormat::GREEN . "Database created successfully");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }

    public function reset()
    {
        $sql = "DROP TABLE IF EXISTS shop, shopstate, tokentbl";

        if ($this->conn->query($sql) === TRUE) {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Database reset successfull");
        } else {
            $this->plugin->getServer()->getLogger()->info(TextFormat::RED . "Error creating database: " . $this->plugin->mySql->connection()->error);
        }

        $this->plugin->mySql->disconnect();
    }
}
