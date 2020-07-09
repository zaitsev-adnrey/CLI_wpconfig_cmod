# CLI_wpconfig_cmod
Массовое изменение прав доступа к wp-config
# Требования 
 WP-CLI
# Установка
Выполнить команду 
```
wp package install zaitsev-adnrey/CLI_wpconfig_cmod
```
# Использование
```
$wp cmod <path> <rule>
```
* path - каталог в котором будет проводится поиск WP 
* rule - права доступа к файлу в числовом формате.

# Пример
```
$wp cmod /var/www/ 0640
```
