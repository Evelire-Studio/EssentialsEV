<?php

declare(strict_types=1);

namespace essentialsev;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use function implode;

class Loader extends PluginBase{
    public function onEnable(){
        $this->getLogger()->info(TF::LIGHT_PURPLE . "EssentialsEV Enabled");
        $this->showCredits();
    }

    public function showCredits(){
        $credits = [
            TF::AQUA . "Thanks for using Evelire Studio plugins!",
            TF::BLUE . "VK: " . $this->getDescription()->getWebsite()
        ];

        foreach($credits as $credit){
            $this->getLogger()->info($credit);
        }
    }
}