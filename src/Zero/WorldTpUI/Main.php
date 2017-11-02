<?php

namespace Zero\WorldTpUI;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as T;

class Main extends PluginBase {

  public $ui = [];
  public $worlds = [];

  public $loadAllWorlds = true;
  
  public $version = '0.0.1';


  public function onEnable() : void {
  try {
  if($this->isFirstLoad() === true){
    $this->getLogger()->info(T::YELLOW ."\nHello and Welcone to WorldTpUI\n\nMake sure you stop the server\nand edit the config in 'plugins/WorldTpUI/config.yml'\n");
  } else {
    $this->getLogger()->info(T::YELLOW ."is Loading...");
  }
    $this->saveResource("config.yml");
    $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

  if($this->config->get('version') === $this->version){
    $this->getLogger()->info(T::GREEN ."Plugin Config is update-to-date.");
  if($this->loadAllWorlds() === true){
    $worlds = $this->getServer()->getDataPath() . "worlds/";
    $allWorlds = array_slice(scandir($worlds), 2);
  foreach($allWorlds as $world){
    $this->getServer()->loadLevel($world);
   }
  }
    $levels = $this->getServer()->getLevels();
    $id = 0;
  foreach($levels as $level){
    $this->getLogger()->info(T::YELLOW ."Level: ". T::AQUA . $level->getName() . T::YELLOW ." Has Been Added to UI List as ". $id);
    $this->worlds[$id] = $level->getName();
    $id++;
   }
  } else {
    $this->getLogger()->info(T::RED ."\nPlease Delete config in 'plugins/WorldTpUI/config.yml'\nthe config needs to be updated");
    $this->getServer()->getPluginManager()->disablePlugin($this);
   }
  } catch(Exception $e){
    $this->getLogger()->info(T::RED ."Failed to load due to $e");
  }
    $this->getServer()->getPluginManager()->registerEvents(new \Zero\WorldTpUI\UI\ListenerUI($this), $this);
    $this->getServer()->getCommandMap()->register('wtpui', new \Zero\WorldTpUI\Command\wtpuiCommand($this));
    $this->getLogger()->info(T::GREEN ."Everything has Loaded!");
  }

  public function isFirstLoad(){
  if(is_file($this->getDataFolder() ."config.yml")){
    return false;
  } else {
    @mkdir($this->getDataFolder());
    $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    $config->setAll(array("version" => $this->version, "load_all_worlds" => false));//will add more later
    $config->save();
    return true;
   }
  }

  public function loadAllWorlds(){
    return $this->config->get("load_all_worlds");
  }

  public function onDisable() : void {
    $this->getLogger()->info(T::RED ."unloading plugin...");
    $this->config->save();
	  $this->getLogger()->info(T::RED ."has Unloaded, Goodbye!");
  }
}