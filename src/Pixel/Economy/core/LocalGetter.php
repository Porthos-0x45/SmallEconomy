<?php

declare(strict_types=1);

namespace Pixel\Economy\core;

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
        return $this->plugin->data->getNested(base64_encode($uuid))["MONEY"];
    }

    public function pay(string $self_uuid, string $send_uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($self_uuid) . "MONEY", $this->getBalance(base64_encode($self_uuid)) - $amount);
        $this->plugin->data->setNested(base64_encode($send_uuid) . "MONEY", $amount);
        $this->plugin->data->save();
    }

    public function set(string $uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($uuid) . "MONEY", $amount);
        $this->plugin->data->save();
    }

    public function remove(string $uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($uuid) . "MONEY", $this->getBalance(base64_encode($uuid)) - $amount);
        $this->plugin->data->save();
    }

    public function delete(string $uuid)
    {
        $this->plugin->data->remove(base64_encode($uuid));
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
