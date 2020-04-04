<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use function count;

class GamemodeCommand extends Command{
    public function __construct(){
        parent::__construct("gamemode", EssentialsEV::translate("command.gamemode.description"), null, ["gm"]);

        $this->setPermission("essentialsev.command.gamemode");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }

        if(!($sender instanceof Player)){
            $sender->sendMessage(EssentialsEV::translate("command.mustbeplayer"));

            return false;
        }

        if(count($args) > 0){
            $gamemode = (int) $args[0];

            if($gamemode >= 0 && $gamemode <= 3){
                $sender->setGamemode($gamemode);

                $sender->sendMessage(EssentialsEV::translate("command.gamemode.successful", ["@gamemode" => Server::getGamemodeName($gamemode)]));
            }else{
                $sender->sendMessage(EssentialsEV::translate("command.gamemode.wrongmode"));
            }
        }else{
            $sender->sendMessage(TF::YELLOW . "/gamemode <gm: int>");
        }

        return true;
    }
}