<?php

declare(strict_types=1);

namespace essentialsev\command;

use essentialsev\EssentialsEV;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use function count;
use function implode;

class BurnCommand extends Command{
    public function __construct(){
        parent::__construct("burn", EssentialsEV::translate("command.burn.description"));

        $this->setPermission("essentialsev.command.burn");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }

        if(count($args) > 0){
            $playerName = implode(" ", $args);
        }elseif($sender instanceof Player){
            $playerName = $sender->getName();
        }else{
            $sender->sendMessage("/burn <playerName: string>");

            return true;
        }

        $player = Server::getInstance()->getPlayer($playerName);
        if($player !== null){
            $player->setOnFire(30);

            $sender->sendMessage(EssentialsEV::translate("command.burn.successful", ["@nick" => $player->getName()]));
        }else{
            $sender->sendMessage(EssentialsEV::translate("command.noplayer", ["@nick" => $playerName]));
        }

        return true;
    }
}