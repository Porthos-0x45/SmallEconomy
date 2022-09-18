<?php

declare(strict_types=1);

namespace Pixel\Economy\events;

use Pixel\Economy\core\LocalGetter;
use Pixel\Economy\core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

class JoinEvent implements Listener
{
    public LocalGetter $getter;

    public function __construct(private Main $plugin)
    {
        $getter = new LocalGetter($plugin);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        $this->getter->createAccount($player);
    }
}
