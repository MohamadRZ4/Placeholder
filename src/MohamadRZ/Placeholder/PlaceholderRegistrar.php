<?php

namespace MohamadRZ\Placeholder;

	use pocketmine\Player;

class PlaceholderRegistrar implements PlaceholderProvider {

	public function registerPlaceholders(): void {

		PlaceholderManager::addPlaceholder("NAME", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return $player->getName();
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("X", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return (string) intval($player->getX());
			} else {
				return "Unknown X";
			}
		});

		PlaceholderManager::addPlaceholder("Y", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return (string) intval($player->getY());
			} else {
				return "Unknown Y";
			}
		});

		PlaceholderManager::addPlaceholder("Z", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return (string) intval($player->getZ());
			} else {
				return "Unknown Z";
			}
		});

		PlaceholderManager::addPlaceholder("ONLINE_PLAYERS", function($params = []) {
			$onlinePlayers = count(Main::getInstance()->getServer()->getOnlinePlayers());
			return $onlinePlayers;
		});


		PlaceholderManager::addPlaceholder("DISPLAY_NAME", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return $player->getDisplayName();
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("PING", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return $player->getPing();
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("MAX_PLAYERS", function($params = []) {
			$maxPlayers = Main::getInstance()->getServer()->getMaxPlayers();
			return $maxPlayers;
		});

		PlaceholderManager::addPlaceholder("REAL_TPS", function($params = []) {
			return Main::getInstance()->getServer()->getTicksPerSecond();
		});

		PlaceholderManager::addPlaceholder("TPS", function($params = []) {
			Main::getInstance()->getServer()->getTicksPerSecondAverage();
		});

		PlaceholderManager::addPlaceholder("REAL_LOAD", function($params = []) {
			Main::getInstance()->getServer()->getTickUsage();
		});

		PlaceholderManager::addPlaceholder("LOAD", function($params = []) {
			Main::getInstance()->getServer()->getTickUsageAverage();
		});

		PlaceholderManager::addPlaceholder("LEVEL_NAME", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return $player->getLevel()->getFolderName();
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("LEVEL_PLAYERS", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return count($player->getLevel()->getPlayers());
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("CONNECTION_IP", function($params = []) {
			$player = $params['player'] ?? null;
			if ($player instanceof Player) {
				return $player->getAddress();
			} else {
				return "Unknown Player";
			}
		});

		PlaceholderManager::addPlaceholder("SERVER_IP", function($params = []) {
			Main::getInstance()->getServer()->getIP();
		});

		PlaceholderManager::addPlaceholder("TIME", function($params = []) {
			Time::getTime();
		});

		PlaceholderManager::addPlaceholder("DATE", function($params = []) {
			Time::getDate();
		});
	}
}