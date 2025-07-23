<?php

declare(strict_types=1);

namespace MohamadRZ4\Placeholder;

use MohamadRZ4\Placeholder\config\ConfigManager;
use MohamadRZ4\Placeholder\expansion\PlaceholderExpansion;
use MohamadRZ4\Placeholder\expansion\PlayerExpansion;
use MohamadRZ4\Placeholder\expansion\ServerExpansion;
use MohamadRZ4\Placeholder\expansion\TimeExpansion;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class PlaceholderAPI extends PluginBase implements Listener {

    private $config;
    private $expansions = [];
    private $playerData = [];

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->loadDefaultExpansions();

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void {
            $this->updateExpansions();
        }), 20);
    }

    private function loadDefaultExpansions(): void {
        $this->registerExpansion(new PlayerExpansion());
        $this->registerExpansion(new ServerExpansion());
        $this->registerExpansion(new TimeExpansion());
    }

    private function updateExpansions(): void {
        foreach ($this->expansions as $expansion) {
            if ($expansion instanceof PlaceholderExpansion) {
                $expansion->update();
            }
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->playerData[$player->getName()] = [
            "join_time" => time(),
            "last_seen" => time()
        ];
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if (isset($this->playerData[$player->getName()])) {
            unset($this->playerData[$player->getName()]);
        }
    }

    public function registerExpansion(PlaceholderExpansion $expansion): bool {
        $identifier = $expansion->getIdentifier();

        if (isset($this->expansions[$identifier])) {
            return false;
        }

        $this->expansions[$identifier] = $expansion;
        $expansion->onRegister($this);

        return true;
    }

    public function unregisterExpansion(string $identifier): bool {
        if (!isset($this->expansions[$identifier])) {
            return false;
        }

        $expansion = $this->expansions[$identifier];
        if ($expansion instanceof PlaceholderExpansion) {
            $expansion->onUnregister();
        }

        unset($this->expansions[$identifier]);
        return true;
    }

    public function getExpansions(): array {
        return $this->expansions;
    }

    public function getExpansion(string $identifier): ?PlaceholderExpansion {
        return $this->expansions[$identifier] ?? null;
    }

    public function parsePlaceholders(string $text, ?Player $player = null): string {
        return preg_replace_callback('/%([a-zA-Z0-9]+)_([a-zA-Z0-9_]+)%/', function ($matches) use ($player) {
            $identifier = $matches[1];
            $placeholder = $matches[2];

            if (!isset($this->expansions[$identifier])) {
                return $matches[0];
            }

            $expansion = $this->expansions[$identifier];
            if ($expansion instanceof PlaceholderExpansion) {
                $result = $expansion->onPlaceholderRequest($player, $placeholder);
                return $result !== null ? (string)$result : $matches[0];
            }

            return $matches[0];
        }, $text);
    }

    public function setPlaceholder(string $identifier, string $placeholder, $value): bool {
        if (!isset($this->expansions[$identifier])) {
            return false;
        }

        $expansion = $this->expansions[$identifier];
        if ($expansion instanceof PlaceholderExpansion) {
            return $expansion->setPlaceholder($placeholder, $value);
        }

        return false;
    }

    public function getPlaceholderValue(string $identifier, string $placeholder, ?Player $player = null): ?string {
        if (!isset($this->expansions[$identifier])) {
            return null;
        }

        $expansion = $this->expansions[$identifier];
        if ($expansion instanceof PlaceholderExpansion) {
            return $expansion->onPlaceholderRequest($player, $placeholder);
        }

        return null;
    }

    public function getAllPlaceholders(): array {
        $placeholders = [];

        foreach ($this->expansions as $identifier => $expansion) {
            if ($expansion instanceof PlaceholderExpansion) {
                $expansionPlaceholders = $expansion->getPlaceholders();
                foreach ($expansionPlaceholders as $placeholder) {
                    $placeholders[] = "%{$identifier}_{$placeholder}%";
                }
            }
        }

        return $placeholders;
    }

    public function getPlayerData(string $playerName): ?array {
        return $this->playerData[$playerName] ?? null;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "placeholderapi") {
            if (!$sender->hasPermission("placeholderapi.admin")) {
                $sender->sendMessage("§cYou don't have permission to use this command!");
                return true;
            }

            if (empty($args)) {
                $sender->sendMessage("§aPlaceholderAPI Commands:");
                $sender->sendMessage("§7/papi reload - Reload the plugin");
                $sender->sendMessage("§7/papi list - List all expansions");
                $sender->sendMessage("§7/papi parse <text> - Parse placeholders in text");
                $sender->sendMessage("§7/papi test <expansion> <placeholder> - Test a placeholder");
                return true;
            }

            switch (strtolower($args[0])) {
                case "reload":
                    $this->reloadConfig();
                    $sender->sendMessage("§aPlaceholderAPI has been reloaded!");
                    break;

                case "list":
                    $sender->sendMessage("§aRegistered Expansions:");
                    foreach ($this->expansions as $identifier => $expansion) {
                        $version = $expansion instanceof PlaceholderExpansion ? $expansion->getVersion() : "Unknown";
                        $author = $expansion instanceof PlaceholderExpansion ? $expansion->getAuthor() : "Unknown";
                        $sender->sendMessage("§7- {$identifier} v{$version} by {$author}");
                    }
                    break;

                case "parse":
                    if (count($args) < 2) {
                        $sender->sendMessage("§cUsage: /papi parse <text>");
                        return true;
                    }

                    $text = implode(" ", array_slice($args, 1));
                    $player = $sender instanceof Player ? $sender : null;
                    $parsed = $this->parsePlaceholders($text, $player);
                    $sender->sendMessage("§aOriginal: §f{$text}");
                    $sender->sendMessage("§aParsed: §f{$parsed}");
                    break;

                case "test":
                    if (count($args) < 3) {
                        $sender->sendMessage("§cUsage: /papi test <expansion> <placeholder>");
                        return true;
                    }

                    $identifier = $args[1];
                    $placeholder = $args[2];
                    $player = $sender instanceof Player ? $sender : null;

                    $result = $this->getPlaceholderValue($identifier, $placeholder, $player);
                    if ($result !== null) {
                        $sender->sendMessage("§aResult: §f{$result}");
                    } else {
                        $sender->sendMessage("§cPlaceholder not found or returned null");
                    }
                    break;

                default:
                    $sender->sendMessage("§cUnknown subcommand: {$args[0]}");
                    break;
            }

            return true;
        }

        return false;
    }

    public static function getAPI(): ?PlaceholderAPI {
        return self::getInstance();
    }

    private static function getInstance(): ?PlaceholderAPI {
        $plugin = \pocketmine\Server::getInstance()->getPluginManager()->getPlugin("PlaceholderAPI");
        return $plugin instanceof PlaceholderAPI ? $plugin : null;
    }
}