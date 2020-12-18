<?php

if ( ! class_exists( 'WP_CLI' ) ) {
        return;
}

/**
 * Изменение прав доступа wp-config на всех найденых сайтах
 *
 */
class config_chmod extends WP_CLI_Command{
        /**
         * Вывод всех конфигов в заданном каталоге
         * Параметры
         * wp site-config check <path> [--grep={<string>}]
         * Пример wp site-config check /var/www/ --group=hosters
         * @when before_wp_load
         */
        function check( $args,$assoc_args) {
                $catalog = $args[0];
                $find_options = array(
                      'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                      'parse'      => 'json', // Parse captured STDOUT to JSON array.
                      'launch'     => false,  // Reuse the current process.
                      'exit_error' => true,   // Halt script execution on error.
                    );
                $paths = WP_CLI::runcommand('find '. $catalog .'  --field=wp_path --format=json', $find_options);
                foreach ($paths as $key => $path) {
                        //WP_CLI::line($path);
                        $path_options = array(
                                                   'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                                                                );
                        $config_path = WP_CLI::runcommand('config path --path=' . $path .' ',$path_options);
                        $grep = "";
                        if (! empty($assoc_args['grep'])){
                                        $grep = "| grep ". $assoc_args['grep'] ."";
                                }

                                system("ls -l --time-style=+ ". $config_path ." ". $grep ."");
                }
        }
        /**
         * Изменение прав на конфиги всех wp в каталоге
         * Параметры
         * wp site-config chmod <rulles> <path> [--grep={<string>}] [--sudo]
         * Пример wp site-config chmod 644 /var/www/ --grep=hosters
         * @when before_wp_load
         */
        function chmod( $args,$assoc_args) {
					$grep = "";
                                        if (!empty($assoc_args['grep'])){
                                                $grep = "| grep ". $assoc_args['grep'] ."";
                                        }
                                        $ls_options = array(
                                                   'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                                                                );
                                        $site_list = WP_CLI::runcommand('site-config check ' . $args[1] .' --grep=' . $grep .' ',$ls_options
                                              
                                        
                                        $sudo ="";
                                        
                                        if(!empty($assoc_args['sudo'])){
                                                        $sudo ="sudo";
                                        
                                                }
                                        //$chmod = "|cut -d ' ' -f7 |xargs ". $sudo ." chmod -c ". $args[1] ."";
                                        $chmod ="";
                                        $site_arr = explode(PHP_EOL, $site_list);
                                        foreach($site_arr as $site){
                                                        list($rulle,$number,$owner,$groupe,$weight,$void,$path)= explode(" ",$site);
                                                        system("".$sudo." chmod -c ". $args[0] ." ". $path ."");
                                                }
                                        //system("ls -l --time-style=+ ". $config_path ." ". $chmod ."");
        }
}
WP_CLI::add_command( 'site-config', 'config_chmod' );

