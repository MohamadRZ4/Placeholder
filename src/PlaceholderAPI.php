<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder;

use MohamadRZ4\Placeholder\config\ConfigManager;
use MohamadRZ4\Placeholder\expansion\GeneralExpansion;
use MohamadRZ4\Placeholder\expansion\PlaceholderExpansion;
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
	private PlaceholderHandler $placeholderHandler;

	public function onLoad(): void
	{
		self::$instance = $this;
	}

	public function onEnable(): void
	{
		$this->initializeServices();
		$this->registerDefaultExpansions();
		$this->registerEvents();
	}

	private function initializeServices(): void
	{
		$this->configManager = new ConfigManager($this);
		$this->placeholderHandler = new PlaceholderHandler($this->getConfigManager());
	}

	private function registerDefaultExpansions(): void
	{
		$this->registerExpansion(new GeneralExpansion());
	}

	private function registerEvents(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public static function getInstance(): PlaceholderAPI
	{
		return self::$instance;
	}

	public function registerExpansion(PlaceholderExpansion $expansion): void
	{
		$this->expansions[] = $expansion;
	}

	public function unregisterExpansion(string $identifier): bool
	{
		foreach ($this->expansions as $key => $expansion) {
			if ($expansion->getIdentifier() === $identifier) {
				unset($this->expansions[$key]);
				return true;
			}
		}

		return false;
	}

	public function listExpansions(): array
	{
		return array_map(static fn($expansion) => [
			"identifier" => $expansion->getIdentifier(),
			"version" => $expansion->getVersion(),
			"author" => $expansion->getAuthor(),
		], $this->expansions);
	}

	public function getExpansionVersion(string $identifier): ?string
	{
		return $this->findExpansionByIdentifier($identifier)?->getVersion();
	}

	public function getExpansionAuthor(string $identifier): ?string
	{
		return $this->findExpansionByIdentifier($identifier)?->getAuthor();
	}

	private function findExpansionByIdentifier(string $identifier): ?PlaceholderExpansion
	{
		foreach ($this->expansions as $expansion) {
			if ($expansion->getIdentifier() === $identifier) {
				return $expansion;
			}
		}

		return null;
	}

	public function processPlaceholders(?Player $player, string $text): string
	{
		foreach ($this->expansions as $expansion) {
			$text = $this->placeholderHandler->replacePlaceholders($player, $text, $expansion);
		}

		return $text;
	}

	public function getConfigManager(): ConfigManager
	{
		return $this->configManager;
	}

	public function getPlaceholderHandler(): PlaceholderHandler
	{
		return $this->placeholderHandler;
	}
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

/*	public function runTests(): void
	{
		$this->getLogger()->info("=== Running PlaceholderAPI Tests ===");

		$dummyExpansion = new class extends PlaceholderExpansion {
			public function getIdentifier(): string {
				return "dummy";
			}

			public function getVersion(): string {
				return "1.0.0";
			}

			public function getAuthor(): string {
				return "Tester";
			}

			public function onPlaceholderRequest(?Player $player, string $params): string {
				switch ($params) {
					case "test":
						return "hi";
						break;
				}
				return "unknown_placeholder";
			}
		};


		$this->registerExpansion($dummyExpansion);
		$this->getLogger()->info("Dummy expansion registered.");

		$expansionList = $this->listExpansions();
		$this->getLogger()->info("Registered Expansions: " . json_encode($expansionList));

		$version = $this->getExpansionVersion("dummy");
		$author = $this->getExpansionAuthor("dummy");
		$this->getLogger()->info("Dummy expansion version: $version, author: $author");

		$player = $this->getServer()->getOnlinePlayers()[0] ?? null;
		$processedText = $this->processPlaceholders($player, "Hello, %test%");
		$this->getLogger()->info("Processed text: $processedText");

		$this->unregisterExpansion("dummy");
		$this->getLogger()->info("Dummy expansion unregistered.");

		$exists = $this->getExpansionVersion("dummy") !== null;
		$this->getLogger()->info("Dummy expansion still exists: " . ($exists ? "Yes" : "No"));

		$this->getLogger()->info("=== Tests Completed ===");
	}*/