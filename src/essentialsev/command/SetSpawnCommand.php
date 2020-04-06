<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class SetSpawnCommand extends Command{
    public function __construct(){
        parent::__construct("setspawn", EssentialsEV::translate("command.setspawn.description"));

        $this->setPermission("essentialsev.command.setspawn");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        $config = new Config(EssentialsEV::getInstance()->getDataFolder() . "spawnData.yml", Config::YAML);
        $config->setNested("spawn.x", $sender->getX());
        $config->setNested("spawn.yy", $sender->getY());
        $config->setNested("spawn.z", $sender->getZ());
        $config->setNested("spawn.yaw", $sender->getYaw());
        $config->setNested("spawn.pitch", $sender->getPitch());
        $config->setNested("spawn.level", $sender->getLevel()->getFolderName());
        $config->save();

        EssentialsEV::setSpawn($sender->asLocation());

        $sender->sendMessage(EssentialsEV::translate("command.setspawn.successful"));

        return true;
    }
}