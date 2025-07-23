<?php

namespace MohamadRZ4\Placeholder\expansion;

use pocketmine\player\Player;

class TimeExpansion extends PlaceholderExpansion {

    public function getIdentifier(): string {
        return "time";
    }

    public function getVersion(): string {
        return "1.0.0";
    }

    public function getAuthor(): string {
        return "PlaceholderAPI";
    }

    public function onPlaceholderRequest(?Player $player, string $placeholder): ?string {
        switch ($placeholder) {
            case "current":
                return date("H:i:s");
            case "date":
                return date("Y-m-d");
            case "datetime":
                return date("Y-m-d H:i:s");
            case "timestamp":
                return (string) time();
            default:
                return null;
        }
    }
}