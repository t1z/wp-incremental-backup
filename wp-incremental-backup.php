<?php
/**
 * Plugin Name: WP Incremental Backup
 * Plugin URI: https://github.com/t1z/wp-incremental-backup
 * Description: Create incremental backups of WordPress files&db
 * Author: t1z
 * Author URI: https://github.com/t1z
 * Version: 0.8.0
 *
 * ChangeLog
 * 0.2.0 First public version
 * 0.2.1 Detect server soft
 * 0.2.2 Write .htaccess for Apache
 * 0.2.3 Admin notices & fix indentation
 * 0.2.4 Admin notices message
 * 0.2.5 Change output dir location and fix .htaccess writing
 * 0.2.6 nginx access file
 * 0.2.7 nginx access file fix
 * 0.2.8 insert .sql.zip as media (commented out)
 * 0.2.9 client and server working together
 * 0.3.0 unlink files after processing
 * 0.3.1 allow cleanup of generated files
 * 0.3.2 comment out attachment creation
 * 0.3.3 move download_file function, allow download from list
 * 0.3.4 fix archive file paths
 * 0.3.5 fix download when no filename is specified
 * 0.3.6 client gets latest filename
 * 0.4.0 concatenation of backup (with deletion of files) works
 * 0.4.1 fix 'tar: argument list too long' problem
 *       (http://stackoverflow.com/questions/23817787/bash-bin-tar-argument-list-too-long-when-compressing-many-files-with-tar)
 * 0.4.2 push forgotten constants file
 * 0.4.3 allow generate&download only by admins
 * 0.4.4 fix how we get latest zip (glob not scandir)
 * 0.4.5 add a log function
 * 0.5.0 add more elaborate error handling
 * 0.6.0 major rework: handle long processing time to avoid PHP/HTTP timeouts
 * 0.7.0 yet another major rework: split archiving process for huge WP installs
 * 0.8.0 another rework: replace big archiving process with many ones (1 per tarball)
 *
 * ToDo
 *   - exclude output_dirs
 *   - encrypt files: mcrypt/GPG/...?
 *   - make it compatible with other platforms (Drupal, Joomla, all PHP frameworks)
 *
 * Different cases:
 * - upload media
 * - delete media
 * - add plugin
 * - delete plugin
 * - add theme
 * - delete theme
 * - edit plugin/theme file
 */
require 'vendor/autoload.php';
require 'class-t1z-incremental-backup-wp-plugin.php';
if (preg_match('/http[s]?\:\/\/localhost\/.*/', site_url()) && WP_DEBUG === true) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}
$walker = new T1z_Incremental_Backup_WP_Plugin();