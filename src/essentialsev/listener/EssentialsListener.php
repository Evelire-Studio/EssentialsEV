<?php

declare(strict_types=1);

namespace essentialsev\listener;

use essentialsev\EssentialsEV;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\Listener;

class EssentialsListener implements Listener{
    public function onEntityBowShoot(EntityShootBowEvent $event){
        if(EssentialsEV::isIncreaseArrowMotion()){
            $event->setForce($event->getForce() * 1.5);
        }
    }
}