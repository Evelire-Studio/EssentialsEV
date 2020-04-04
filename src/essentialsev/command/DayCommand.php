<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class DayCommand extends Command{
    public function __construct(){
        parent::__construct("day", EssentialsEV::translate("command.day.description"));

        $this->setPermission("essentialsev.command.day");
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
            $level->setTime(Level::TIME_DAY);

            $sender->sendMessage(EssentialsEV::translate("command.day.successful"));
        }else{
            //$levelName can be null only when default level (or player level) is null ;)
            $sender->sendMessage(EssentialsEV::translate("command.day.unknownworld", ["@world" => $levelName]));
        }

        return true;
    }
}