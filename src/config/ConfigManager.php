<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder\config;

use MohamadRZ4\Placeholder\PlaceholderAPI;

class ConfigManager
{
	private PlaceholderAPI $plugin;
	private array $config;

	public function __construct(PlaceholderAPI $plugin)
	{
		$this->plugin = $plugin;
		$this->config = $plugin->getConfig()->getAll();
	}

}
