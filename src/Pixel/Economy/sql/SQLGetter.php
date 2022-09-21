<?php

declare(strict_types=1);

namespace Pixel\Economy\sql;

use mysqli;
use Pixel\Economy\core\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SQLGetter
{
    private function __construct(private Main $plugin)
    {
    }

    public function createDatabase()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tokentbl (NAME VARCHAR(100),UUID VARCHAR(100),TOKENS INT(100),PRIMARY KEY (NAME))";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function createAccount($uuid, $name)
    {
        $sql = "INSERT IGNORE INTO tokentbl (NAME,UUID) VALUES (?, ?)";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("ss", $name, $uuid);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function getName($uuid): string
    {
        $sql = "SELECT NAME FROM tokentbl WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return null;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("ss", $name, (string) $uuid);
        $stmt->execute();

        $result = (string) $stmt->get_result();

        $stmt->close();
        $this->plugin->mySql->disconnect();

        return $result;
    }

    public function accountExists($uuid): bool
    {
        $player = $this->plugin->getServer()->getPlayerByRawUUID($uuid);

        $sql = "SELECT * FROM tokentbl WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("s", $uuid);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result != null)
            return true;

        $stmt->close();
        $this->plugin->mySql->disconnect();

        $player->sendMessage(TextFormat::RED . "This player does not exists");

        return false;
    }

    public function getBalance(string $uuid): int
    {
        $sql = "SELECT TOKENS FROM tokentbl WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return null;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("s", $uuid);
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();
        $this->plugin->mySql->disconnect();

        return (int) $result;
    }

    public function pay(string $self_uuid, string $send_uuid, int $amount)
    {
        $sql = "UPDATE tokentbl SET TOKENS=? WHERE UUID=?";

        // send transaction template
        $uuid = $send_uuid;
        $send = $this->getBalance($uuid) + $amount;

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("is", $send, $uuid);

        // send first transaction
        $stmt->execute();

        // fetch transaction
        $uuid = $self_uuid;
        $send = $this->getBalance($uuid) - $amount;
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function add(string $uuid, int $amount)
    {
        $sql = "UPDATE tokentbl SET TOKENS=? WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("is", $send, $uuid);

        // send transaction
        $send = $this->getBalance($uuid) + $amount;
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function set(string $uuid, int $amount)
    {
        $sql = "UPDATE tokentbl SET TOKENS=? WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return null;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function remove(string $uuid, int $amount)
    {
        $sql = "UPDATE tokentbl SET TOKENS=? WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return null;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("is", $this->getBalance($uuid) - $amount, $uuid);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function delete(string $uuid)
    {
        $sql = "DELETE FROM tokentbl WHERE UUID=?";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        if (!$this->accountExists($uuid)) {
            return null;
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->bind_param("s", $uuid);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }

    public function reset()
    {
        $sql = "DROP TABLE IF EXISTS shop, shopstate, tokentbl";

        if (!$this->plugin->mySql->isConnected()) {
            $this->plugin->mySql->connect();
        }

        $stmt = $this->plugin->mySql->connection()->prepare($sql);
        $stmt->execute();

        $stmt->close();
        $this->plugin->mySql->disconnect();
    }
}
