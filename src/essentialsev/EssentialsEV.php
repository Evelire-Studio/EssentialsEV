<?php

declare(strict_types=1);

namespace essentialsev;

use essentialsev\command\BurnCommand;
use essentialsev\command\ClearInventoryCommand;
use essentialsev\command\DayCommand;
use essentialsev\command\FeedCommand;
use essentialsev\command\GamemodeCommand;
use essentialsev\command\HealCommand;
use essentialsev\command\NightCommand;
use essentialsev\command\SetSpawnCommand;
use essentialsev\command\SpawnCommand;
use essentialsev\listener\EssentialsListener;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use function array_slice;
use function array_sum;
use function sprintf;
use function str_replace;

class EssentialsEV extends PluginBase{
    /** @var self */
    protected static $instance;

    /** @var string[] */
    protected static $messages = [];

    /** @var bool */
    protected static $increaseArrowMotion;
    /** @var Position|Location */
    protected static $spawn;

    /**
     * @return EssentialsEV
     */
    public static function getInstance() : self{
        return self::$instance;
    }

    public function onEnable(){
        self::$instance = $this;

        $this->loadConfig();
        $this->loadSpawnData();
        $this->loadMessages();
        $this->registerCommands();

        $this->getServer()->getPluginManager()->registerEvents(new EssentialsListener(), $this);

        $this->getLogger()->info(TF::LIGHT_PURPLE . "EssentialsEV Enabled");
        $this->showCredits();
    }

    public function loadConfig(){
        $this->saveResource("config.yml", false);

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        self::$increaseArrowMotion = $config->getNested("bow.increase-arrow-motion", true);
    }

    public function loadSpawnData(){
        $this->saveResource("spawnData.yml", false);

        $config = new Config($this->getDataFolder() . "spawnData.yml", Config::YAML);

        //TODO: invalid config can cause crash
        if(array_sum(array_slice($config->getAll()["spawn"], 0, 5)) !== 0){
            self::$spawn = new Location(
                $config->getNested("spawn.x"),
                $config->getNested("spawn.yy"),
                $config->getNested("spawn.z"),
                $config->getNested("spawn.yaw"),
                $config->getNested("spawn.pitch"),
                $this->getServer()->getLevelByName($config->getNested("spawn.level"))
            );
        }else{
            self::$spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
        }
    }

    public function loadMessages(){
        $this->saveResource("messages.yml", false);

        $messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);

        $allMessages = $messages->getAll();
        // I can use & but it will look strange :D
        foreach($allMessages as $key => $message){
            $allMessages[$key] = str_replace("&", "§", $message);
        }

        self::$messages = $allMessages;
    }

    public function registerCommands(){
        static $toUnregister = [
            "gamemode"
        ];

        foreach($toUnregister as $commandName){
            if(($command = $this->getServer()->getCommandMap()->getCommand($commandName)) !== null){
                $this->getServer()->getCommandMap()->unregister($command);
            }
        }

        $this->getServer()->getCommandMap()->registerAll("EssentialsEV", [
            new GamemodeCommand(),
            new HealCommand(),
            new FeedCommand(),
            new DayCommand(),
            new NightCommand(),
            new BurnCommand(),
            new ClearInventoryCommand(),
            new SetSpawnCommand(),
            new SpawnCommand()
        ]);
    }

    public static function translate(string $key, array $parameters = []){
        $message = self::$messages[$key] ?? null;

        if($message !== null){
            foreach($parameters as $parameter => $parameterValue){
                $message = str_replace($parameter, $parameterValue, $message);
            }

            return $message;
        }

        throw new \InvalidArgumentException(sprintf("Invalid message key %s", $key));
    }

    public function showCredits(){
        $credits = [
            TF::AQUA . "Thanks for using Evelire Studio's plugins!",
            TF::BLUE . "VK: " . $this->getDescription()->getWebsite()
        ];

        foreach($credits as $credit){
            $this->getLogger()->info($credit);
        }
    }

    /**
     * @return bool
     */
    public static function isIncreaseArrowMotion() : bool{
        return self::$increaseArrowMotion;
    }

    /**
     * @return Location|Position
     */
    public static function getSpawn(){
        return self::$spawn;
    }

    /**
     * @param Location|Position $spawn
     */
    public static function setSpawn($spawn){
        self::$spawn = $spawn;
    }
}