<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}
$chmod = function($args){
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
    else{
      WP_CLI::success('wp-cli/find-command установлен');
    }
    $find_options = array(
                      'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                      'parse'      => 'json', // Parse captured STDOUT to JSON array.
                      'launch'     => false,  // Reuse the current process.
                      'exit_error' => true,   // Halt script execution on error.
                    );
    $paths = WP_CLI::runcommand('find '. $args[0] .'  --field=wp_path --format=json', $find_options);
    foreach ($paths as $key => $path) {
        //WP_CLI::line($path);
        $path_options = array(
                       'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                        );
        $config_path = WP_CLI::runcommand('config path --path=' . $path .' ',$path_options);
        //WP_CLI::line($config_path);
        $isfail = chmod($config_path, $args[1]);
        if($isfail == true){
         WP_CLI::success( 'Права доступа изменены для '. $config_path .'' );
        }
        else{
         WP_CLI::warning( 'Права доступа не изменены для '. $config_path .'' );
        }
    }

};
WP_CLI::add_command('chmod', $chmod);
