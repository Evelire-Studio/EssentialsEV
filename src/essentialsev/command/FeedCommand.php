<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FeedCommand extends Command{
    public function __construct(){
        parent::__construct("feed", EssentialsEV::translate("command.feed.description"));

        $this->setPermission("essentialsev.command.feed");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        $sender->setFood($sender->getMaxFood());
        $sender->setSaturation(20);
        $sender->setExhaustion(0);
        $sender->sendMessage(EssentialsEV::translate("command.feed.successful"));

        return true;
    }
}