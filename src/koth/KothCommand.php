<?php
/**
 * Created: 
 * User: 
 * Date: 
 * Time: 
 */
namespace koth;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
class KothCommand extends Command
{
    private $plugin;
    public function __construct($name, KothMain $main)
    {
        parent::__construct($name, "");
        $this->plugin = $main;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        if ($sender instanceof Player){
            if (isset($args[0])){
                if (strtolower($args[0]) === "Join"){
                    if ($this->plugin->sendToKoth($sender)){
                        $sender->sendMessage($this->plugin->getData("Joined"));
                        return false;
                    }else{
                        $sender->sendMessage($this->plugin->getData("Not_Running"));
                    }
                    return true;
                } else if (strtolower($args[0]) === "SetSpawn"){
                    if (!$sender->hasPermission("koth.start")) return true;
                    $this->plugin->setPoint($sender,"Spawn");
                    $sender->sendMessage("Successfully Added SpawnPoint");
                    return true;
                } else if (strtolower($args[0]) === "p1"){
                    if (!$sender->hasPermission("koth.start")) return true;
                    $this->plugin->setPoint($sender,"p1");
                    $sender->sendMessage("Successfully Added P1 Point (Make Sure To Set P2)");
                } else if (strtolower($args[0]) === "p2"){
                    if (!$sender->hasPermission("koth.start")) return true;
                    $this->plugin->setPoint($sender,"p2");
                    $sender->sendMessage("Successfully Added P2 Point");
                } else if (strtolower($args[0]) === "start"){
                    if (!$sender->hasPermission("koth.start")) return true;
                    if ($this->plugin->startArena()){
                        $sender->sendMessage("Koth Event Started");
                    }else{
                        $sender->sendMessage("No Koth Arena Fully SetUp");
                    }
                } else if (strtolower($args[0]) === "stop"){
                    if (!$sender->hasPermission("koth.stop")) return true;
                    if ($this->plugin->forceStop()){
                        $sender->sendMessage("Koth Event Force Stopped");
                    }else{
                        $sender->sendMessage("No Koth Arena Fully SetUp");
                    }
                } else{
                    if ($sender->isOp()) $this->sendHelp($sender);
                    if (!$sender->isOp()) $sender->sendMessage($this->plugin->prefix()."Join Game With /koth Join");
                }
            }else{
                if ($sender->isOp()) $this->sendHelp($sender);
                if (!$sender->isOp()) $sender->sendMessage($this->plugin->prefix()."Join Game With /koth Join");
            }
        }else{
            if (isset($args[0])){
                if (strtolower($args[0]) === "start"){
                    if ($this->plugin->startArena()){
                        $sender->sendMessage("Koth Event Started");
                    }else{
                        $sender->sendMessage("No Koth Arena Fully SetUp");
                    }
                    return true;
                } else if (strtolower($args[0]) === "stop"){
                    if ($this->plugin->forceStop()){
                        $sender->sendMessage("Koth Event Force Stopped");
                    }else{
                        $sender->sendMessage("No Koth Arena Fully SetUp");
                    }
                    return true;
                }
            }
            $sender->sendMessage("Error Cant Run That In Console");
        }
        return true;
    }
    public function sendHelp(CommandSender $sender){
        $sender->sendMessage("---KOTH Commands---");
        $sender->sendMessage("Make sure to run first 3 commands to fully setup Arena");
        $sender->sendMessage("1) /koth setspawn - set as many spawn points as your want!");
        $sender->sendMessage("2) /koth p1 - set point 1 for capture area");
        $sender->sendMessage("3) /koth p2 - set point 2 for capture area");
        $sender->sendMessage("/koth start - starts KOTH Match");
        $sender->sendMessage("/koth stop - force stop KOTH Math");
        $sender->sendMessage("Reload server or restart to setup Arena fully!");
    }
}
