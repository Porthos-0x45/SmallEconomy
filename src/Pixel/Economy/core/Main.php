<?php

declare(strick_types=1);

namespace Pixel\Economy\core;

use pocketmine\block\Planks;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{
    public $data;
    public LocalGetter $getter;

    public function onLoad(): void
    {
        $this->getServer()->getLogger()->info(TextFormat::RED . "Loading Economy plugin");
    }

    public function onEnable(): void
    {
        $this->getServer()->getLogger()->info(TextFormat::RED . "Enabling Economy plugin");

        $this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->getter = new LocalGetter($this);
    }

    public function onDisable(): void
    {
        $this->saveConfig();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        $nocmd = TextFormat::RED . "You do not have permission to use this command";
        $help = TextFormat::GOLD . "Helping: " .
            TextFormat::YELLOW . "/money balance | <player>" .
            TextFormat::YELLOW . "/money pay <player> <amount>" .
            TextFormat::YELLOW . "/money set <player> <amount>" .
            TextFormat::YELLOW . "/money remove <player> <amount>" .
            TextFormat::YELLOW . "/money delete <player>" .
            TextFormat::YELLOW . "/money reset";
        /**************  IN-GAME  **************/
        if ($sender instanceof Player) {
            $player = $this->getServer()->getPlayerByPrefix($sender->getName());

            if ($player->hasPermission("economy.owner")) {

                if ($cmd->getName() == "money") {
                    if ($player->hasPermission("economy.owner")) {
                        if (count($args) == 0) {

                            $player->sendMessage($help);
                        } else if (count($args) > 0 && count($args) <= 2) {
                            if ($args[0] == "balance") {
                                if (count($args) == 1) {
                                    $player->sendMessage(TextFormat::GREEN . "Your account balance is: " . TextFormat::BLUE . (string)$this->getter->getBalance($player->getName()) . "$");
                                } else {
                                    $name = $args[1];

                                    $player->sendMessage(TextFormat::GREEN . "Account Balance of. " . $name . " is: " . TextFormat::BLUE . (string)$this->getter->getBalance($name) . "$");
                                }
                            } else if ($args[0] == "delete" && count($args) == 2) {

                                $name = $args[1];

                                $this->getter->delete($name);
                                $player->sendMessage(TextFormat::RED . "You deleted " . $name . " bank account!");
                            } else if ($args[0] == "reset") {
                                $this->getter->reset();
                            } else {
                                $player->sendMessage($help);
                                return true;
                            }
                        } else if (count($args) == 3) {

                            if ($args[0] == "pay") {

                                $name = $args[1];
                                $amount = (int) $args[2];

                                $this->getter->pay($player->getName(), $name, $amount);
                                $player->sendMessage(TextFormat::YELLOW . "You payed " . TextFormat::BLUE . $amount . TextFormat::YELLOW . "$ to " . $name);
                            } else if ($args[0] == "set") {

                                $name = $args[1];
                                $amount = (int) $args[2];

                                $this->getter->set($name, $amount);
                                $player->sendMessage(TextFormat::RED . "You have set the account balance of " . $name . " to " . TextFormat::BLUE . $amount . "$");
                            } else if ($args[0] == "remove") {

                                $name = $args[1];
                                $amount = (int) $args[2];

                                $this->getter->remove($name, $amount);
                                $player->sendMessage(TextFormat::RED . "You have removed " . TextFormat::BLUE . $amount . "$" . TextFormat::RED . " from " . $name);
                            } else {
                                $player->sendMessage($help);
                                return true;
                            }
                        } else {
                            $player->sendMessage($help);
                            return true;
                        }
                    }
                }

                return true;
            } else if ($player->hasPermission("economy.user")) {

                if ($cmd->getName() == "money") {
                    if (count($args) == 0) {
                    } else if (count($args) > 0 && count($args) <= 3) {
                    } else {
                        $sender->sendMessage($nocmd);
                    }
                }

                return true;
            } else {
                $sender->sendMessage($nocmd);

                return true;
            }
        }

        /**************  CONSOLE  **************/
        else {
        }

        if ($cmd->getName() == "money") {
        }


        return true;
    }
}
