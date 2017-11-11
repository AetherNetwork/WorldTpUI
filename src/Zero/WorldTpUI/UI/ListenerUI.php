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
  if($buttonid != '' or $buttonid != 0){
  if(isset($this->getPlugin()->worlds[$buttonid])){
    $world = $this->getPlugin()->worlds[$buttonid];
  if($this->getPlugin()->getServer()->isLevelLoaded($world)){
  if($player->getLevel()->getName() != $world){
    $player->teleport(\pocketmine\Server::getInstance()->getLevelByName($world)->getSafeSpawn());
  } else {
    $player->sendMessage(T::RED ."You are already in that world");
   }
  } else {
    $player->sendMessage(T::RED .'It seems that level is not loaded');
  }
     }
    }
   }
  }
}