<?php
/**
 * Created by PhpStorm.
 * User: JeremyMorales
 * Date: 6/22/17
 * Time: 10:48 AM
 */
namespace koth;
use pocketmine\scheduler\PluginTask;
class GameTimer extends PluginTask
{
    private $plugin;
    private $arena;
    private $time;
    public function __construct(KothMain $owner, KothArena $arena){
        parent::__construct($owner);
        $this->plugin = $owner;
        $this->arena = $arena;
        $this->time = $owner->getData("game_time") * 60;
    }
    public function onRun(int $currentTick){
        $time = $this->time--;
        if ($time < 1){
            $this->arena->endGame();
            $this->getHandler()->cancel();
            return;
        }
        $msg = $this->plugin->getData("game_bar");
        $msg = str_replace("{time}", gmdate("i:s", $time), $msg);
        $this->arena->sendPopup($msg);
        $this->arena->checkPlayers();
    }
}
