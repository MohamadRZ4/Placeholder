<?php

declare(strict_types=1);

namespace MohamadRZ\Placeholder\event;

use pocketmine\event\Event;

class PlaceholderRequestEvent extends Event {

	private $identifier;
	private $params;
	private $value;

	public function __construct(string $identifier, array $params = []) {
		$this->identifier = $identifier;
		$this->params = $params;
		$this->value = null;
	}

	public function getIdentifier(): string {
		return $this->identifier;
	}

	public function getParams(): array {
		return $this->params;
	}

	public function setValue(?string $value): void {
		$this->value = $value;
	}

	public function getValue(): ?string {
		return $this->value;
	}
}
