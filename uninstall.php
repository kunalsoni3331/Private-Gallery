<?php

/*

 * Removing Plugin data using uninstall.php

 * the below function clears the database table on uninstall

 * only loads this file when uninstalling a plugin.

 */



/*

 * exit uninstall if not called by WP

 */

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {

    exit();

}



/*

 * Making WPDB as global

 * to access database information.

 */

global $wpdb;



/*******************************************************************************

*

*  REMOVE TABLES AND ALL DATA FROM THE DATABASE

*  Table names are stored in the option 'narnoo_ecommerce_db_tables' as JSON.

*  We have to return this information and process it as an array. Once done

*  we can loop through the array and delete the tables. We also have to delete

*  the options after this process is completed

*

*******************************************************************************/

// table names

delete_option("ncm_version");

delete_option("narnoo_ecommerce_db_tables");

