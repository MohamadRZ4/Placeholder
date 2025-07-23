# PlaceholderAPI

A powerful and flexible placeholder plugin for PocketMine-MP Minecraft servers, enabling dynamic data integration in chat, scoreboards, and other plugins.

## Overview

PlaceholderAPI allows server administrators to use dynamic placeholders (e.g., `%player_name%`, `%server_online_players%`) to display real-time information about players, server status, and time. It includes built-in expansions and supports custom ones for developers to extend functionality.

## Features

- **Dynamic Placeholders**: Replace placeholders with real-time data.
- **Built-in Expansions**: Player, server, and time data out of the box.
- **Player Data Tracking**: Tracks session data like join time.
- **Commands**: Manage and test placeholders easily.
- **Developer-Friendly**: Extendable with custom expansions.

## Commands

- `/papi reload`: Reloads the plugin configuration.
- `/papi list`: Lists all registered expansions.
- `/papi parse <text>`: Parses placeholders in the given text.
- `/papi test <expansion> <placeholder>`: Tests a specific placeholder.

**Permission**: `placeholderapi.admin` (required for commands).

## Default Expansions

### Player Expansion (`player`)
- `%player_name%`: Player's username.
- `%player_display_name%`: Player's display name.
- `%player_ping%`: Player's ping.
- `%player_health%`: Current health.
- `%player_max_health%`: Maximum health.
- `%player_food%`: Food level.
- `%player_level%`: World name.
- `%player_gamemode%`: Current gamemode.
- `%player_ip%`: Player's IP address.
- `%player_x%`, `%player_y%`, `%player_z%`: Coordinates.
- `%player_is_op%`: Operator status (`true`/`false`).
- `%player_name_format%`: Rank-based name color (requires RankSystem).
- `%player_session_time%`: Session duration (HH:MM:SS).

### Server Expansion (`server`)
- `%server_name%`: Server MOTD.
- `%server_ip%`: Server IP.
- `%server_port%`: Server port.
- `%server_max_players%`: Max player limit.
- `%server_online_players%`: Current online players.
- `%server_version%`: Server version.
- `%server_tps%`: Ticks per second.
- `%server_load%`: Tick usage percentage.

### Time Expansion (`time`)
- `%time_current%`: Current time (e.g., `14:30:45`).
- `%time_date%`: Current date (e.g., `2025-07-23`).
- `%time_datetime%`: Date and time (e.g., `2025-07-23 14:30:45`).
- `%time_timestamp%`: Unix timestamp.

## Parsing Placeholders

To use placeholders in your plugin, call the `parsePlaceholders` method from the `PlaceholderAPI` class. This method processes a string containing placeholders and replaces them with their corresponding values.

### Method Signature
```php
public function parsePlaceholders(string $text, ?Player $player = null): string
```

- **Parameters**:
  - `$text`: The input string containing placeholders (e.g., `Hello %player_name%!`).
  - `$player`: An optional `Player` object for player-specific placeholders. Pass `null` for non-player-specific placeholders (e.g., server or time).
- **Returns**: The string with placeholders replaced by their values.

### How It Works
The method uses a regular expression to match placeholders in the format `%identifier_placeholder%` (e.g., `%player_name%`). It then calls the `onPlaceholderRequest` method of the corresponding expansion to retrieve the value. If the placeholder or expansion is invalid, the original placeholder text is returned unchanged.

### Example Usage
```php
use MohamadRZ4\Placeholder\PlaceholderAPI;
use pocketmine\player\Player;

function displayWelcomeMessage(Player $player): void {
    $placeholderAPI = PlaceholderAPI::getAPI();
    if ($placeholderAPI !== null) {
        $message = $placeholderAPI->parsePlaceholders(
            "Welcome %player_name% to %server_name%! Time: %time_current%",
            $player
        );
        $player->sendMessage($message);
    }
}
```

**Output** (example):
```
Welcome Steve to MyServer! Time: 14:30:45
```

### Notes on Parsing
- Ensure the `PlaceholderAPI` plugin is enabled and loaded before calling `getAPI()`.
- Placeholders are case-sensitive.
- For player-specific placeholders, always pass a valid `Player` object.
- Non-existent placeholders remain unchanged in the output string. 

## For Developers

Create custom expansions by extending `PlaceholderExpansion`. Implement required methods: `getIdentifier`, `getVersion`, `getAuthor`, and `onPlaceholderRequest`.

### Example: Custom Expansion
```php
<?php

namespace MyPlugin\Expansion;

use MohamadRZ4\Placeholder\expansion\PlaceholderExpansion;
use MohamadRZ4\Placeholder\PlaceholderAPI;
use pocketmine\player\Player;

class PlayTimeExpansion extends PlaceholderExpansion {

    public function getIdentifier(): string {
        return "playtime";
    }

    public function getVersion(): string {
        return "1.0.0";
    }

    public function getAuthor(): string {
        return "YourName";
    }

    public function onPlaceholderRequest(?Player $player, string $placeholder): ?string {
        if ($player === null) {
            return null;
        }

        if ($placeholder === "total_hours") {
            $joinTime = $this->plugin->getPlayerData($player->getName())["join_time"] ?? time();
            $totalSeconds = time() - $joinTime;
            $hours = floor($totalSeconds / 3600);
            return (string)$hours;
        }

        return null;
    }

    public function onRegister(PlaceholderAPI $plugin): void {
        parent::onRegister($plugin);
    }
}
```

Register the expansion:
```php
$placeholderAPI = PlaceholderAPI::getAPI();
if ($placeholderAPI !== null) {
    $placeholderAPI->registerExpansion(new PlayTimeExpansion());
}
```

Use `%playtime_total_hours%` to display total hours played.

## Notes

- Requires RankSystem for `%player_name_format%`.
- Placeholders are case-sensitive (e.g., `%player_name%`).
- Updates every 20 ticks (1 second) for real-time data.
- Ensure dependencies are installed for full functionality.