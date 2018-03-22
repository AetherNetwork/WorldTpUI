<?php

declare(strict_types=1);

namespace Zero\WorldTpUI\Command;

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use Zero\WorldTpUI\Main;

class wtpuiCommand extends VanillaCommand{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        parent::__construct('wtpui', 'allows admins to tp to any world', '/wtpui');
        $this->setPermission('plugins.command');
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if($sender instanceof Player){
            if($sender->isOp() === true){
                $ui = $this->plugin->ui['world-tp'];
                $ui->data = ['type' => 'custom_form', 'title' => 'WorldTpUI ' . $this->plugin->getDescription()->getVersion(),
                    'content' => [
                        ['type' => 'input', 'text' => 'Type a world name', 'placeholder' => 'WorldName', 'default' => null],
                        ['type' => 'step_slider', 'text' => 'load area around yourself', 'steps' => array("\n0, load none", "\nload 4x4 area", "\nload 8x8 area")],
                        ["type" => "label", "text" => "Worlds Loaded:\n" . TextFormat::AQUA . $this->getLevels()]
                    ]];
                $ui->send($sender);
                return true;
            }else{
                $sender->sendMessage(TextFormat::RED . "You must be Op to run this Command!");
                return false;
            }
        }else{
            $sender->sendMessage(TextFormat::RED . "Command must be run in-game!");
            return false;
        }
    }

    public function getLevels(){
        $levels = $this->plugin->getServer()->getLevels();
        foreach($levels as $level){
            $lvl[$level->getName()] = $level;
        }
        return implode(", ", array_keys($lvl));
        unset($lvl);
    }
}
