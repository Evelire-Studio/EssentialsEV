<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SpawnCommand extends Command{
    public function __construct(){
        parent::__construct("spawn", EssentialsEV::translate("command.spawn.description"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        $sender->teleport(EssentialsEV::getSpawn());
        $sender->sendMessage(EssentialsEV::translate("command.spawn.successful"));

        return true;
    }
}