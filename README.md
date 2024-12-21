# PlaceholderAPI

PlaceholderAPI is a powerful and flexible placeholder system for PocketMine-MP. It allows you to easily manage placeholders and dynamically replace them in messages, commands, and more. Whether you're creating a server with custom features or just want to improve the player experience, PlaceholderAPI has you covered!

## Features

- **Dynamic Placeholder Replacement**: Easily replace placeholders with values in messages, commands, and more.
- **Custom Placeholder Support**: Create and register your own placeholders.
- **Cache Management**: Efficient caching system to improve performance and reduce redundant processing.
- **Multi-Page Listing**: List placeholders in paginated format with easy navigation.
- **Advanced API**: Provides a clean and simple API to integrate placeholders into your PocketMine-MP plugins.

## Installation

### Requirements
- PocketMine-MP 5.16.1 or higher

### Steps
1. Download the latest release of PlaceholderAPI from the [Releases page](https://github.com/yourusername/PlaceholderAPI/releases).
2. Place the `.phar` file in the `plugins/` directory of your PocketMine-MP server.
3. Start your server, and PlaceholderAPI will be automatically loaded.

### Example Placeholders
- `%online_players%`: Shows the current number of online players.
- `%max_players%`: Shows the maximum number of players allowed on the server.
- `%server_name%`: Displays the name of the server.

### Wiki
For more detailed documentation and guides, please visit our [wiki](https://github.com/MohamadRZ4/Placeholder/wiki).

### Placeholder Replacements
In your plugin or messages, you can use placeholders such as `%online_players%` and `%max_players%`. PlaceholderAPI will automatically replace them with the corresponding values.

```php
$text = "%online_players%/%max_players%";
$processedText = PlaceholderAPI::getInstance()->processPlaceholders($player, $text);
$this->getLogger()->info(processedText); // Output: 5/100 (Example output)
```
### Configuration
You can configure PlaceholderAPI by editing the config.yml file in the plugins/PlaceholderAPI/ directory. Options include cache expiration time and more.

```yaml
# PlaceholderAPI Config

# Enable or disable cache expiration
enable_cache_expiration: true  # Set to false to disable cache expiration

# The time (in seconds) after which the processed cache should be cleared
cache_expiration_time: 60  # 5 minutes
```
### Contributing
We welcome contributions to PlaceholderAPI! If you'd like to help improve the plugin, feel free to fork the repository and create a pull request.

