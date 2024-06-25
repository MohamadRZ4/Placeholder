<?php

declare(strict_types=1);

namespace MohamadRZ\Placeholder;

interface PlaceholderProvider {
	/**
	 * Register placeholders.
	 */
	public function registerPlaceholders(): void;
}
