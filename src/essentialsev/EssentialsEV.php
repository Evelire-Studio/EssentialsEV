<?php

declare(strict_types=1);

namespace essentialsev;

use essentialsev\command\BurnCommand;
use essentialsev\command\DayCommand;
use essentialsev\command\FeedCommand;
use essentialsev\command\GamemodeCommand;
use essentialsev\command\HealCommand;
use essentialsev\command\NightCommand;
use essentialsev\listener\EssentialsListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use function sprintf;
use function str_replace;

class EssentialsEV extends PluginBase{
    /** @var string[] */
    protected static $messages = [];

    /** @var bool */
    protected static $increaseArrowMotion;

    public function onEnable(){
        $this->loadConfig();
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
            new BurnCommand()
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
}