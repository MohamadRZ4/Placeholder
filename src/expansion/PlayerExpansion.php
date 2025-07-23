<?php

namespace MohamadRZ4\Placeholder\expansion;

use IvanCraft623\RankSystem\RankSystem;
use MohamadRZ\Utils\Main;
use MohamadRZ4\Placeholder\PlaceholderAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerExpansion extends PlaceholderExpansion {

    public function getIdentifier(): string {
        return "player";
    }

    public function getVersion(): string {
        return "1.0.0";
    }

    public function getAuthor(): string {
        return "PlaceholderAPI";
    }

    public function onPlaceholderRequest(?Player $player, string $placeholder): ?string {
        if ($player === null) {
            return null;
        }

        switch ($placeholder) {
            case "name":
                return $player->getName();
            case "display_name":
                return $player->getDisplayName();
            case "ping":
                return (string) $player->getNetworkSession()->getPing();
            case "health":
                return (string) $player->getHealth();
            case "max_health":
                return (string) $player->getMaxHealth();
            case "food":
                return (string) $player->getHungerManager()->getFood();
            case "level":
                return $player->getWorld()->getFolderName();
            case "gamemode":
                return (string) $player->getGamemode()->getEnglishName();
            case "ip":
                return $player->getNetworkSession()->getIp();
            case "x":
                return (string) round($player->getLocation()->getX(), 2);
            case "y":
                return (string) round($player->getLocation()->getY(), 2);
            case "z":
                return (string) round($player->getLocation()->getZ(), 2);
            case "is_op":
                return Server::getInstance()->isOp($player->getName()) ? "true" : "false";
            case "name_format":
                return RankSystem::getInstance()->getSessionManager()->get($player->getName())->getHighestRank()->getChatFormat()["nameColor"];
            case "session_time":
                if ($this->plugin) {
                    $data = $this->plugin->getPlayerData($player->getName());
                    if ($data) {
                        $sessionTime = time() - $data["join_time"];
                        return $this->formatTime($sessionTime);
                    }
                }
                return "0";
            default:
                return null;
        }
    }

    private function formatTime(int $seconds): string {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
}