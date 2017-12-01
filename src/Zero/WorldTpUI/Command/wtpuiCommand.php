<?php 

declare(strict_types=1);

namespace Zero\WorldTpUI\Command;

use pocketmine\Player;

use pocketmine\Server;

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

  public function execute(CommandSender $player, $alias, array $args){
  if($player instanceof Player){
  if($player->isOp() === true){
    $worlds = array_map(function (\pocketmine\level\Level $level){
      return new \pocketmine\form\MenuOption($level->getName());
    }, Server::getInstance()->getLevels());
    $player->sendForm(
      new class(T::DARK_PURPLE . "Worlds", "Teleport to any world", array_values($worlds)) extends \pocketmine\form\MenuForm{
        public function onSubmit(Player $player): ?\pocketmine\form\Form{
          $selectedOption = $this->getSelectedOption()->getText();
          $world = Server::getInstance()->getLevelByName($selectedOption);
          if (!is_null($world)) $player->teleport($world->getSpawnLocation());
          else $player->sendForm(new class("World was not found", "Please contact an administrator about this\nError: World not found\nWorldname: " . $selectedOption, "gui.yes", "gui.no") extends \pocketmine\form\ModalForm{
          }, true);
          return null;
        }
      }
      , true
    );
    return true;
  } else {
    $player->sendMessage(T::RED."You must be Op to run this Command!");
    return false;
   }
  } else {
    $player->sendMessage(T::RED."Command must be run in-game!");
    return false;     
   }
  }
}
