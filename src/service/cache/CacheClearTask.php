<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder\service\cache;

use MohamadRZ4\Placeholder\service\PlaceholderHandler;
use pocketmine\scheduler\Task;

class CacheClearTask extends Task
{
	private PlaceholderHandler $patternMatcher;

	public function __construct(PlaceholderHandler $patternMatcher)
	{
		$this->patternMatcher = $patternMatcher;
	}

	public function onRun(): void
	{
		$this->patternMatcher->clearCacheIfExpired();
	}
}
