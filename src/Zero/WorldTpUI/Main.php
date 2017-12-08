<?php

declare(strict_types=1);

namespace Zero\WorldTpUI;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as T;

class Main extends PluginBase {

  public function onEnable() : void {
  if(!is_dir($this->getDataFolder())){
    @mkdir($this->getDataFolder());
  }
  if($this->getServer()->getName() === 'PocketMine-MP'){
    $this->getLogger()->info(T::YELLOW .'is Loading...');
    $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML, array('load_all_worlds' => false));
  if($this->config->get('load_all_worlds') === true){
    $this->loadWorlds();
  } 
    $this->getServer()->getCommandMap()->register('wtpui', new \Zero\WorldTpUI\Command\wtpuiCommand($this));
    $this->getLogger()->info(T::GREEN .'Everything has Loaded!');
  } else {
    $this->getLogger()->info(T::RED .'Sorry this plugin does not support Spoons');
    $this->getServer()->getPluginManager()->disablePlugin($this);
   }
  }

  private function loadWorlds() : void {
    $allWorlds = array_slice(scandir($this->getServer()->getDataPath() . 'worlds/'), 2);
  foreach($allWorlds as $world){
  if(is_dir($this->getServer()->getDataPath() . 'worlds/' . $world . '/') && is_file($this->getServer()->getDataPath() . 'worlds/' . $world . '/level.dat')){
  if(!$this->getServer()->isLevelLoaded($world)){
  if($this->getServer()->loadLevel($world)){
    $this->getLogger()->info(T::YELLOW .'World '. T::AQUA . $world . T::YELLOW .' loaded');
  } else {
    $this->getLogger()->info(T::YELLOW .'Failed to load '. T::AQUA . $world);
      }
     }
    }
   }
  }

  public function onDisable() : void {
    $this->getLogger()->info(T::RED .'unloading plugin...');
    $this->getLogger()->info(T::RED .'has Unloaded, Goodbye!');
  }
}
