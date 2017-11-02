<?php

namespace Zero\WorldTpUI\UI;

use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

use pocketmine\Player;

use pocketmine\utils\TextFormat as T;

class ListenerUI implements Listener {

  private $plugin;
    
  public function __construct(\Zero\WorldTpUI\Main $plugin){
    $this->plugin = $plugin;
  }
    
  public function getPlugin(){
    return $this->plugin;
  }
    
  public function onPacketReceived(\pocketmine\event\server\DataPacketReceiveEvent $e){
    $player = $e->getPlayer();
    $pk = $e->getPacket();
  if($pk instanceof ModalFormResponsePacket){
    $id = $pk->formId;
    $buttonid = json_decode($pk->formData, true);
    $ui = $this->getPlugin()->ui;
  if($id === $ui[$player->getName()]){
    $worlds = $this->getPlugin()->worlds;
  if(isset($worlds[$buttonid])){
    $world = $worlds[$buttonid];
  if($player->getLevel()->getName() != $world){
  if($this->getPlugin()->getServer()->isLevelLoaded($world)){
    $player->teleport(\pocketmine\Server::getInstance()->getLevelByName($world)->getSafeSpawn());
   }
  } else {
    $player->sendMessage(T::RED ."You are already in that world");
  }
     }
    }
   }
  }
}