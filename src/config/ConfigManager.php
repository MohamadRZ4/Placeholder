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

	/**
	 * Get the cache expiration time.
	 * If cache expiration is disabled, returns 0.
	 *
	 * @return int
	 */
	public function getCacheExpirationTime(): int
	{
		if ($this->isCacheExpirationEnabled()) {
			return $this->config['cache_expiration_time'] ?? 60;
		}

		return 0;
	}

	/**
	 * Check if cache expiration is enabled in the config.
	 *
	 * @return bool
	 */
	public function isCacheExpirationEnabled(): bool
	{
		return $this->config['enable_cache_expiration'] ?? true;
	}
}

