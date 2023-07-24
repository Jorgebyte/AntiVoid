<?php

namespace Jorgebyte\AntiVoidFast;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener
{
    private $title;
    private $subtitle;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();

        $config = $this->getConfig();
        $this->title = $config->get("title-text");
        $this->subtitle = $config->get("subtitle-text");
    }

    public function worldsAntiVoid(Player $player) {
        $currentWorld = $player->getWorld()->getFolderName();
        $worlds = $this->getConfig()->get("worlds", []);
        return in_array($currentWorld, $worlds);
    }

    public function onFallDamage(EntityDamageEvent $event) {
        if ($event->getCause() === EntityDamageEvent::CAUSE_VOID && $event->getEntity() instanceof Player) {
            $player = $event->getEntity();
            if ($this->worldsAntiVoid($player)) {
                $event->cancel();
                $player->teleport($player->getWorld()->getSafeSpawn());
                $player->sendTitle($this->title, $this->subtitle, 10, 40, 10);
            }
        }
    }
}
