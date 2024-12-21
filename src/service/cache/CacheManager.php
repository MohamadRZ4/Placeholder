<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder\service\cache;

class CacheManager
{
	private array $processedCache = [];
	private int $cacheExpirationTime;
	private int $lastCacheClearTime = 0;

	public function __construct(int $cacheExpirationTime)
	{
		$this->cacheExpirationTime = $cacheExpirationTime;
	}

	public function getCache(): array
	{
		return $this->processedCache;
	}

	public function setCache(string $text, string $result): void
	{
		$this->processedCache[$text] = $result;
	}

	public function clearCacheIfExpired(): void
	{
		$currentTime = time();
		if (($currentTime - $this->lastCacheClearTime) >= $this->cacheExpirationTime) {
			$this->processedCache = [];
			$this->lastCacheClearTime = $currentTime;
		}
	}
}
