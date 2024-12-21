<?php

namespace MohamadRZ4\Placeholder\expansion;

use pocketmine\player\Player;
use pocketmine\Server;

class GeneralExpansion extends PlaceholderExpansion
{
	public function getIdentifier(): string
	{
		return "general";
	}

	public function getAuthor(): string
	{
		return "De8x";
	}

	public function getVersion(): string
	{
		return "1.0.0";
	}

	public function onPlaceholderRequest(?Player $player, string $params): ?string
	{
		switch ($params) {
			case "online_players":
				return (string)count(Server::getInstance()->getOnlinePlayers());
			case "max_players":
				return (string)Server::getInstance()->getMaxPlayers();
			case "server_motd":
				return Server::getInstance()->getMotd();
			case "server_version":
				return Server::getInstance()->getVersion();
			case "server_ip":
				return Server::getInstance()->getIp();
			case "server_port":
				return (string)Server::getInstance()->getPort();

			case "player_name":
				return $player ? $player->getName() : null;
			case "player_display_name":
				return $player ? $player->getDisplayName() : null;
			case "player_ping":
				return $player ? (string)$player->getNetworkSession()->getPing() : null;
			case "player_ip":
				return $player ? $player->getNetworkSession()->getIp() : null;
			case "player_health":
				return $player ? (string)$player->getHealth() : null;
			case "player_max_health":
				return $player ? (string)$player->getMaxHealth() : null;
			case "player_x":
				return $player ? (string)$player->getPosition()->getX() : null;
			case "player_y":
				return $player ? (string)$player->getPosition()->getY() : null;
			case "player_z":
				return $player ? (string)$player->getPosition()->getZ() : null;
			case "player_world":
				return $player ? $player->getWorld()->getDisplayName() : null;

			case "server_time":
				return date("H:i:s");
			case "server_date":
				return date("Y-m-d");

			case "random_number":
				return (string)mt_rand(1, 100);
			case "total_worlds":
				return (string)count(Server::getInstance()->getWorldManager()->getWorlds());
			case "tps":
				return (string)round(Server::getInstance()->getTicksPerSecond(), 2);
			case "uptime":
				return (string)round(microtime(true) - Server::getInstance()->getStartTime(), 2) . " seconds";

			default:
				return null;
		}
	}
}