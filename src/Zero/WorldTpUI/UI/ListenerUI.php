<?php

declare(strict_types=1);

namespace Zero\WorldTpUI\UI;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Zero\WorldTpUI\Main;

class ListenerUI implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function getPlugin(){
        return $this->plugin;
    }

    public function onPacketReceived(DataPacketReceiveEvent $e){
        $player = $e->getPlayer();
        if($player instanceof Player){
            $pk = $e->getPacket();
            if($pk instanceof ModalFormResponsePacket){
                $id = $pk->formId;
                $data = json_decode($pk->formData, true);
                //var_dump($data);//debuggging.
                $form = $this->plugin->ui['world-tp'];
                if($id === $form->getId()){
                    if($data[0] != '' or $data[0] != null){
                        if($this->getPlugin()->getServer()->isLevelLoaded($data[0])){
                            if($player->getLevel()->getName() != $data[0]){
                                $this->loadArea($data[0], $data[1]);
                                $player->teleport(Server::getInstance()->getLevelByName($data[0])->getSafeSpawn());
                                $player->sendMessage(TextFormat::AQUA . 'You have teleported to ' . $data[0]);
                            }else{
                                $player->sendMessage(TextFormat::RED . 'You are already in that world');
                            }
                        }else{
                            $player->sendMessage(TextFormat::RED . 'It seems that level is not loaded or does not exist');
                        }
                    }else{
                        $player->sendMessage(TextFormat::RED . 'Please type a world in the input box.');
                    }
                }
            }
        }
    }

    public function loadArea(string $level, int $area){
        $lvl = $this->getPlugin()->getServer()->getLevelByName($level);
        $position = new Position($lvl->getSafeSpawn()->x, $lvl->getSafeSpawn()->y, $lvl->getSafeSpawn()->z, $lvl);
        switch($area){
            case 1;
                for($x = $position->getFloorX() - 4; $x <= $position->getFloorX() + 4; $x++){
                    for($z = $position->getFloorZ() - 4; $z <= $position->getFloorZ() + 4; $z++){
                        $position->getLevel()->loadChunk($x, $z);
                    }
                }
                break;
            case 2:
                for($x = $position->getFloorX() - 8; $x <= $position->getFloorX() + 8; $x++){
                    for($z = $position->getFloorZ() - 8; $z <= $position->getFloorZ() + 8; $z++){
                        $position->getLevel()->loadChunk($x, $z);
                    }
                }
                break;
        }
    }
}