<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder;

use MohamadRZ4\Placeholder\command\PlaceholderCommand;
use MohamadRZ4\Placeholder\config\ConfigManager;
use MohamadRZ4\Placeholder\expansion\GeneralExpansion;
use MohamadRZ4\Placeholder\expansion\PlaceholderExpansion;
use MohamadRZ4\Placeholder\service\cache\CacheManager;
use MohamadRZ4\Placeholder\service\PlaceholderHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class PlaceholderAPI extends PluginBase implements Listener
{
	private static ?PlaceholderAPI $instance = null;
	private array $expansions = [];
	private ConfigManager $configManager;
	private CacheManager $cacheManager;
	private PlaceholderHandler $placeholderHandler;

	public function onEnable(): void
	{
		self::$instance = $this;

		$this->configManager = new ConfigManager($this);
		$this->cacheManager = new CacheManager($this->configManager->getCacheExpirationTime());
		$this->placeholderHandler = new PlaceholderHandler($this->getConfigManager(), $this->getCacheManager());
		$this->registerExpansion(new GeneralExpansion());
		/*$this->runTests();*/
	}

/*	public function runTests(): void
	{
		$patternMatcher = $this->getPlaceholderHandler();
		$player = $this->getServer()->getOnlinePlayers()[0] ?? null;

		$text = "%online_players%/%max_players%";
		$processedText = $this->processPlaceholders($player, $text);
		$this->getLogger()->info("Processed Text: " . $processedText);


		$onlinePlayers = $patternMatcher->get('%online_players%');
		$this->getLogger()->info("Online Players: " . ($onlinePlayers ?? "Not found"));

		$exists = $patternMatcher->exist('%max_players%');
		$this->getLogger()->info("Max Players placeholder exists: " . ($exists ? "Yes" : "No"));

		$patternMatcher->unregister('%online_players%');
		$this->getLogger()->info("After unregistering %online_players%");

		$existsAfterUnregister = $patternMatcher->exist('%online_players%');
		$this->getLogger()->info("Online Players placeholder exists after unregister: " . ($existsAfterUnregister ? "Yes" : "No"));
	}*/

	public static function getInstance(): PlaceholderAPI
	{
		return self::$instance;
	}

	public function registerExpansion(PlaceholderExpansion $expansion): void
	{
		$this->expansions[] = $expansion;
	}

	public function processPlaceholders(?Player $player, string $text): string
	{
		$patternMatcher = $this->getPlaceholderHandler();
		foreach ($this->expansions as $expansion) {
			$text = $patternMatcher->replacePlaceholders($player, $text, $expansion);
		}

		return $text;
	}

	public function getConfigManager(): ConfigManager
	{
		return $this->configManager;
	}

	public function getCacheManager(): CacheManager
	{
		return $this->cacheManager;
	}

	public function getPlaceholderHandler(): PlaceholderHandler
	{
		return $this->placeholderHandler;
	}
}
