<?php

declare(strict_types=1);

namespace Pixel\Economy\core;

class LocalGetter
{

    public function __construct(private Main $plugin)
    {
    }

    public function createAccount(string $uuid)
    {
        $this->plugin->data->setNested(base64_encode($uuid) . "MONEY", 0);
    }

    public function getBalance(string $uuid): int
    {
        return $this->plugin->data->getNested(base64_encode($uuid))["MONEY"];
    }

    public function pay(string $self_uuid, string $send_uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($self_uuid) . "MONEY", $this->getBalance(base64_encode($self_uuid)) - $amount);
        $this->plugin->data->setNested(base64_encode($send_uuid) . "MONEY", $amount);
    }

    public function set(string $uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($uuid) . "MONEY", $amount);
    }

    public function remove(string $uuid, int $amount)
    {
        $this->plugin->data->setNested(base64_encode($uuid) . "MONEY", $this->getBalance(base64_encode($uuid)) - $amount);
    }

    public function delete(string $uuid)
    {
        $this->plugin->data->remove(base64_encode($uuid));
    }

    public function reset()
    {
        foreach ($this->plugin->data->getAll() as $accounts) {
            $this->plugin->data->removeNested($accounts);
        }
    }
}
