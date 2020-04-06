<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ClearInventoryCommand extends Command{
    public function __construct(){
        parent::__construct("clearinventory", EssentialsEV::translate("command.clearinventory.description"), null, ["clear", "clean"]);

        $this->setPermission("essentialsev.command.clearinventory");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        $sender->getInventory()->clearAll();
        $sender->getCursorInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();

        $sender->sendMessage(EssentialsEV::translate("command.clearinventory.successful"));

        return true;
    }
}