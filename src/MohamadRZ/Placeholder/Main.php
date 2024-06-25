<?php

declare(strict_types=1);

namespace MohamadRZ\Placeholder;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	private static $instance;

	public function onEnable(): void {
		self::$instance = $this;
		PlaceholderManager::init();

		$this->getLogger()->info("PlaceholderPlugin enabled");
		$placeholderRegistrar = new PlaceholderRegistrar();
		$placeholderRegistrar->registerPlaceholders();
	}

	public function onDisable(): void {
		$this->getLogger()->info("PlaceholderPlugin disabled");
	}

	public static function getInstance(): self {
		return self::$instance;
	}
}
