<?php

namespace MohamadRZ4\Placeholder\expansion;

use MohamadRZ4\Placeholder\PlaceholderAPI;
use pocketmine\player\Player;

abstract class PlaceholderExpansion {

    protected $plugin;
    protected $placeholders = [];

    /**
     * Get expansion identifier
     */
    abstract public function getIdentifier(): string;

    /**
     * Get expansion version
     */
    abstract public function getVersion(): string;

    /**
     * Get expansion author
     */
    abstract public function getAuthor(): string;

    /**
     * Handle placeholder request
     */
    abstract public function onPlaceholderRequest(?Player $player, string $placeholder): ?string;

    /**
     * Called when expansion is registered
     */
    public function onRegister(PlaceholderAPI $plugin): void {
        $this->plugin = $plugin;
    }

    /**
     * Called when expansion is unregistered
     */
    public function onUnregister(): void {
        $this->plugin = null;
    }

    /**
     * Called periodically to update dynamic placeholders
     */
    public function update(): void {
        // Override in subclasses if needed
    }

    /**
     * Get all available placeholders
     */
    public function getPlaceholders(): array {
        return array_keys($this->placeholders);
    }

    /**
     * Set placeholder value
     */
    public function setPlaceholder(string $key, $value): bool {
        $this->placeholders[$key] = $value;
        return true;
    }

    /**
     * Get placeholder value
     */
    public function getPlaceholderValue(string $key): ?string {
        return $this->placeholders[$key] ?? null;
    }
}