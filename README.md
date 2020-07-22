# CLI_wpconfig_cmod
Массовое изменение прав доступа к wp-config
# Требования 
 * WP-CLI
 * wp-cli/find- command (в случае отсутсвия при первом запуске команды chmod предложит установить автоматически)
# Установка
Выполнить команду 
```
wp package install zaitsev-adnrey/CLI_wpconfig_cmod
```
# Использование
```
$wp chmod <path> <rule>
```
* path - каталог в котором будет проводится поиск WP 
* rule - права доступа к файлу в числовом формате.

# Пример
```
$wp chmod /var/www/ 640
```
