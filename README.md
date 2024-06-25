### Placeholder Plugin for PocketMine-MP

This plugin provides a powerful placeholder API for creating dynamic placeholders in PocketMine-MP servers.

### Features

- **Dynamic Placeholder Support:** Easily create custom placeholders using callbacks.
- **Integration with Scoreboard:** Seamlessly integrate placeholders into your scoreboard plugin for displaying dynamic player information.
- **Flexible Configuration:** Configure placeholders and their behavior directly through the `config.yml` file.
- **Compatible with PocketMine-MP:** Designed for compatibility with PocketMine-MP server software.

### Installation

1. Download the latest release from the [releases page](https://github.com/MohamadRZ4/Placeholder/releases).
2. Place the `Placeholder` folder into your PocketMine-MP `plugins` directory.
3. Restart your server to load the plugin.

### Usage

#### Creating Custom Placeholders

You can create custom placeholders by implementing the `PlaceholderProvider` interface:

```php
use MohamadRZ\Placeholder\PlaceholderManager;

class ExamplePlaceholder implements PlaceholderProvider {
    
    public function registerPlaceholders(): void {
        PlaceholderManager::addPlaceholder("EXAMPLE", function($player) {
            // Replace with your custom logic to return a string value
            return "Example Placeholder Value";
        });
    }
}
```

#### Using Placeholders in Scoreboard

Integrate placeholders into your scoreboard configuration (`config.yml`):

```yaml
scoreboard_lines:
  - "%EXAMPLE%"
```

### API Documentation

For detailed API usage and available methods, refer to the [API Documentation](https://github.com/MohamadRZ4/Placeholder/wiki).

### Compatibility

- **PocketMine-MP Version:** 1.14 and above
- **API Version:** 3.0.0 and above

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

### Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

### Support

For any questions or issues, please open an [issue](https://github.com/MohamadRZ4/Placeholder/issues) here on GitHub.
