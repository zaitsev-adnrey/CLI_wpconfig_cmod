<?php

if ( ! class_exists( 'WP_CLI' ) ) {
    return;
}
function findwp($catalog){
    $find_options = array(
                      'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                      'parse'      => 'json', // Parse captured STDOUT to JSON array.
                      'launch'     => false,  // Reuse the current process.
                      'exit_error' => true,   // Halt script execution on error.
                    );
    $paths = WP_CLI::runcommand('find '. $catalog .'  --field=wp_path --format=json', $find_options);
    return $paths;
}
WP_CLI::add_hook( 'before_add_command:chmod', $issetwpfind );
function issetwpfind(){
        $install_options = array(
                      'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                      'parse'      => 'json', // Parse captured STDOUT to JSON array.
                      'launch'     => false,  // Reuse the current process.
                      'exit_error' => true,   // Halt script execution on error.
                    );
    $packages=WP_CLI::runcommand('package list --fields=name --format=json',$install_options);
    foreach ($packages as $key => $package){
        if ($package['name']=='wp-cli/find-command'){
         $installed = true;
        }
    
    }
    if($installed != true){
     WP_CLI::error( 'Не установлен пакет wp-cli/find-command', $exit = false );
     WP_CLI::confirm( "Установить сейчас?", $assoc_args );
     $options = array(
                    'return'     => false,   // Return 'STDOUT'; use 'all' for full object.
                    'parse'      => 'json', // Parse captured STDOUT to JSON array.
                    'launch'     => false,  // Reuse the current process.
                    'exit_error' => true,   // Halt script execution on error.
                     );
     WP_CLI::runcommand('package install wp-cli/find-command',$options);
    }
}
/**
 * Изменение прав доступа wp-config на всех найденых сайтах
 * Пример wp cmod /var/www 0640
 *
 * @when before_wp_load
 */
$chmod = function($args){
    issetwpfind();
    $paths = findwp($args[0]);
    foreach ($paths as $path) {
        //WP_CLI::line($path);
        $path_options = array(
                       'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                        );
        $config_path = WP_CLI::runcommand('config path --path=' . $path .' ',$path_options);
        //WP_CLI::line($config_path);
        system("chmod -c " . $args[1] . " ". $config_path ."");
    }

};
WP_CLI::add_command('chmod', $chmod);
/**
 * Просмотр текущих прав доступа wp-config на всех найденых сайтах
 * Пример wp lsl /var/www 
 *
 * @when before_wp_load
 */
$config_lsl = function($args){
    issetwpfind();
    $paths = findwp($args[0]);
    foreach ($paths as $key => $path) {
        //WP_CLI::line($path);
        $path_options = array(
                       'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                        );
        $config_path = WP_CLI::runcommand('config path --path=' . $path .' ',$path_options);
         system("ls -l ". $config_path ."");
    }
};
WP_CLI::add_command('lsl', $config_lsl);
