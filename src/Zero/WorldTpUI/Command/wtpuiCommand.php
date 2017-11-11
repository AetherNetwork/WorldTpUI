<?php 

namespace Zero\WorldTpUI\Command;

use pocketmine\Player;

use pocketmine\utils\TextFormat as T;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\command\defaults\VanillaCommand;

class wtpuiCommand extends VanillaCommand {
    
  private $plugin;

  public function __construct(\Zero\WorldTpUI\Main $plugin){
    $this->plugin = $plugin;
    parent::__construct('wtpui', 'allows admins to tp to any world', '/wtpui');
    $this->setPermission('plugins.command');
  }

  public function execute(CommandSender $sender, $alias, array $args){
  if($sender instanceof Player){
  if($sender->isOp() === true){
    $id = rand(1, 999);
    $ui = new \Zero\WorldTpUI\UI\SimpleUI($id);
    $ui->addTitle("WorldTpUI ". $this->plugin->version);
    $ui->addContent(T::YELLOW ."What world do you want to tp to?");
    $ui->addButton('Cancel', 1, 'https://i.imgur.com/PcJEnVy.png');
  foreach($this->plugin->worlds as $wid => $world){
    $ui->addButton("Teleport to: ". $world, 1, 'https://i.imgur.com/apIyTc8.png');
  }
    $this->plugin->ui[$sender->getName()] = $id;
    unset($id);
    $ui->send($sender);
    return true;
  } else {
    $sender->sendMessage(T::RED."You must be Op to run this Command!");
   }
  } else {
    $sender->sendMessage(T::RED."Command must be run in-game!");
    return false;     
   }
  }
}