<?php

namespace MohamadRZ4\Placeholder\expansion;

use pocketmine\player\Player;

class ServerExpansion extends PlaceholderExpansion {

    public function getIdentifier(): string {
        return "server";
    }

    public function getVersion(): string {
        return "1.0.0";
    }

    public function getAuthor(): string {
        return "PlaceholderAPI";
    }

    public function onPlaceholderRequest(?Player $player, string $placeholder): ?string {
        $server = \pocketmine\Server::getInstance();

        switch ($placeholder) {
            case "name":
                return $server->getMotd();
            case "ip":
                return $server->getIp();
            case "port":
                return (string) $server->getPort();
            case "max_players":
                return (string) $server->getMaxPlayers();
            case "online_players":
                return (string) count($server->getOnlinePlayers());
            case "version":
                return $server->getVersion();
            case "tps":
                return (string) round($server->getTicksPerSecond(), 2);
            case "load":
                return (string) round($server->getTickUsage(), 2);
            default:
                return null;
        }
    }
}