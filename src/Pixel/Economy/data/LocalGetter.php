<?php

declare(strict_types=1);

namespace Pixel\Economy\util;

use Pixel\Economy\core\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;


class LocalGetter
{

    public function __construct(private Main $plugin)
    {
    }

    public function createAccount(Player $player)
    {
        if ($this->accountExists($player->getName()) == false) {
            $this->plugin->data->setNested(base64_encode($player->getName()) . "MONEY", 0);
            $this->plugin->data->save();
            $player->sendMessage(TextFormat::DARK_GREEN . "Your bank account has been created.");
        }
    }

    public function accountExists($uuid): bool
    {
        return $this->plugin->data->exists($uuid);
    }

    public function getBalance(string $uuid): int
    {
        return $this->plugin->data->getNested($uuid)["MONEY"];
    }

    public function pay(string $self_uuid, string $send_uuid, int $amount)
    {
        $this->plugin->data->setNested($self_uuid . "MONEY", $this->getBalance($self_uuid) - $amount);
        $this->plugin->data->setNested($send_uuid . "MONEY", $this->getBalance($send_uuid) + $amount);
        $this->plugin->data->save();
    }

    public function add(string $uuid, int $amount)
    {
        $this->plugin->data->setNested($uuid . "MONEY", $amount);
        $this->plugin->data->save();
    }

    public function set(string $uuid, int $amount)
    {
        $this->plugin->data->setNested($uuid . "MONEY", $amount);
        $this->plugin->data->save();
    }

    public function remove(string $uuid, int $amount)
    {
        $this->plugin->data->setNested($uuid . "MONEY", $this->getBalance($uuid) - $amount);
        $this->plugin->data->save();
    }

    public function delete(string $uuid)
    {
        $this->plugin->data->remove($uuid);
        $this->plugin->data->save();
    }

    public function reset()
    {
        foreach ($this->plugin->data->getAll() as $accounts) {
            $this->plugin->data->removeNested($accounts);
        }
        $this->plugin->data->save();
    }
}
