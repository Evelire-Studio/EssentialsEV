<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class HealCommand extends Command{
    public function __construct(){
        parent::__construct("heal", EssentialsEV::translate("command.heal.description"));

        $this->setPermission("essentialsev.command.heal");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        $sender->setHealth($sender->getMaxHealth());
        $sender->sendMessage(EssentialsEV::translate("command.heal.successful"));

        return true;
    }
}