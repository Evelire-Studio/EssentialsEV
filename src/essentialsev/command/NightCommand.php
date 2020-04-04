<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class NightCommand extends Command{
    public function __construct(){
        parent::__construct("night", EssentialsEV::translate("command.night.description"));

        $this->setPermission("essentialsev.command.night");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }

        $levelName = $args[0] ?? null;

        if($sender instanceof Player){
            if($levelName !== null){
                $level = Server::getInstance()->getLevelByName((string) $levelName);
            }else{
                $level = $sender->getLevel();
            }
        }elseif($levelName !== null){
            $level = Server::getInstance()->getLevelByName((string) $levelName);
        }else{
            $level = Server::getInstance()->getDefaultLevel();
        }

        if($level !== null){
            $level->setTime(Level::TIME_NIGHT);

            $sender->sendMessage(EssentialsEV::translate("command.night.successful"));
        }else{
            //$levelName can be null only when default level (or player level) is null ;)
            $sender->sendMessage(EssentialsEV::translate("command.night.unknownworld", ["@world" => $levelName]));
        }

        return true;
    }
}