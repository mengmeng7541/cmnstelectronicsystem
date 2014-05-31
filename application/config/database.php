<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'common';
$active_record = TRUE;



$db['order_machine']['hostname'] = 'dblib:host=140.116.176.21;dbname=order_machine';
#$db['default']['port'] = 1433;
$db['order_machine']['username'] = 'sa';
$db['order_machine']['password'] = '601877';
$db['order_machine']['database'] = 'order_machine';
$db['order_machine']['dbdriver'] = 'pdo';
$db['order_machine']['dbprefix'] = '';
$db['order_machine']['pconnect'] = FALSE;
$db['order_machine']['db_debug'] = TRUE;
$db['order_machine']['cache_on'] = FALSE;
$db['order_machine']['cachedir'] = '';
$db['order_machine']['char_set'] = 'utf8';
$db['order_machine']['dbcollat'] = 'utf8_general_ci';
$db['order_machine']['swap_pre'] = '';
$db['order_machine']['autoinit'] = TRUE;
$db['order_machine']['stricton'] = FALSE;

$db['order_class']['hostname'] = 'dblib:host=140.116.176.21;dbname=order_class';
#$db['default']['port'] = 1433;
$db['order_class']['username'] = 'sa';
$db['order_class']['password'] = '601877';
$db['order_class']['database'] = 'order_class';
$db['order_class']['dbdriver'] = 'pdo';
$db['order_class']['dbprefix'] = '';
$db['order_class']['pconnect'] = FALSE;
$db['order_class']['db_debug'] = TRUE;
$db['order_class']['cache_on'] = FALSE;
$db['order_class']['cachedir'] = '';
$db['order_class']['char_set'] = 'utf8';
$db['order_class']['dbcollat'] = 'utf8_general_ci';
$db['order_class']['swap_pre'] = '';
$db['order_class']['autoinit'] = TRUE;
$db['order_class']['stricton'] = FALSE;

$db['work']['hostname'] = 'dblib:host=140.116.176.21;dbname=work';
#$db['default']['port'] = 1433;
$db['work']['username'] = 'sa';
$db['work']['password'] = '601877';
$db['work']['database'] = 'work';
$db['work']['dbdriver'] = 'pdo';
$db['work']['dbprefix'] = '';
$db['work']['pconnect'] = FALSE;
$db['work']['db_debug'] = TRUE;
$db['work']['cache_on'] = FALSE;
$db['work']['cachedir'] = '';
$db['work']['char_set'] = 'utf8';
$db['work']['dbcollat'] = 'utf8_general_ci';
$db['work']['swap_pre'] = '';
$db['work']['autoinit'] = TRUE;
$db['work']['stricton'] = FALSE;






$db['common']['hostname'] = "127.0.0.1";
$db['common']['username'] = "cmnst";
$db['common']['password'] = "2080103";
$db['common']['database'] = "cmnst_common";
$db['common']['dbdriver'] = "mysql";
$db['common']['dbprefix'] = "";
$db['common']['pconnect'] = FALSE;
$db['common']['db_debug'] = TRUE;
$db['common']['cache_on'] = FALSE;
$db['common']['cachedir'] = "";
$db['common']['char_set'] = "utf8";
$db['common']['dbcollat'] = "utf8_general_ci";
$db['common']['swap_pre'] = "";
$db['common']['autoinit'] = TRUE;
$db['common']['stricton'] = FALSE;

$db['clock']['hostname'] = "127.0.0.1";
$db['clock']['username'] = "cmnst";
$db['clock']['password'] = "2080103";
$db['clock']['database'] = "cmnst_clock";
$db['clock']['dbdriver'] = "mysql";
$db['clock']['dbprefix'] = "";
$db['clock']['pconnect'] = FALSE;
$db['clock']['db_debug'] = TRUE;
$db['clock']['cache_on'] = FALSE;
$db['clock']['cachedir'] = "";
$db['clock']['char_set'] = "utf8";
$db['clock']['dbcollat'] = "utf8_general_ci";
$db['clock']['swap_pre'] = "";
$db['clock']['autoinit'] = TRUE;
$db['clock']['stricton'] = FALSE;

$db['reward']['hostname'] = "127.0.0.1";
$db['reward']['username'] = "cmnst";
$db['reward']['password'] = "2080103";
$db['reward']['database'] = "cmnst_reward";
$db['reward']['dbdriver'] = "mysql";
$db['reward']['dbprefix'] = "";
$db['reward']['pconnect'] = FALSE;
$db['reward']['db_debug'] = TRUE;
$db['reward']['cache_on'] = FALSE;
$db['reward']['cachedir'] = "";
$db['reward']['char_set'] = "utf8";
$db['reward']['dbcollat'] = "utf8_general_ci";
$db['reward']['swap_pre'] = "";
$db['reward']['autoinit'] = TRUE;
$db['reward']['stricton'] = FALSE;

$db['nanomark']['hostname'] = "127.0.0.1";
$db['nanomark']['username'] = "cmnst";
$db['nanomark']['password'] = "2080103";
$db['nanomark']['database'] = "cmnst_nanomark";
$db['nanomark']['dbdriver'] = "mysql";
$db['nanomark']['dbprefix'] = "";
$db['nanomark']['pconnect'] = FALSE;
$db['nanomark']['db_debug'] = TRUE;
$db['nanomark']['cache_on'] = FALSE;
$db['nanomark']['cachedir'] = "";
$db['nanomark']['char_set'] = "utf8";
$db['nanomark']['dbcollat'] = "utf8_general_ci";
$db['nanomark']['swap_pre'] = "";
$db['nanomark']['autoinit'] = TRUE;
$db['nanomark']['stricton'] = FALSE;

$db['facility']['hostname'] = "127.0.0.1";
$db['facility']['username'] = "cmnst";
$db['facility']['password'] = "2080103";
$db['facility']['database'] = "cmnst_facility";
$db['facility']['dbdriver'] = "mysql";
$db['facility']['dbprefix'] = "";
$db['facility']['pconnect'] = FALSE;
$db['facility']['db_debug'] = TRUE;
$db['facility']['cache_on'] = FALSE;
$db['facility']['cachedir'] = "";
$db['facility']['char_set'] = "utf8";
$db['facility']['dbcollat'] = "utf8_general_ci";
$db['facility']['swap_pre'] = "";
$db['facility']['autoinit'] = TRUE;
$db['facility']['stricton'] = FALSE;

$db['access']['hostname'] = "127.0.0.1";
$db['access']['username'] = "cmnst";
$db['access']['password'] = "2080103";
$db['access']['database'] = "cmnst_access";
$db['access']['dbdriver'] = "mysql";
$db['access']['dbprefix'] = "";
$db['access']['pconnect'] = FALSE;
$db['access']['db_debug'] = TRUE;
$db['access']['cache_on'] = FALSE;
$db['access']['cachedir'] = "";
$db['access']['char_set'] = "utf8";
$db['access']['dbcollat'] = "utf8_general_ci";
$db['access']['swap_pre'] = "";
$db['access']['autoinit'] = TRUE;
$db['access']['stricton'] = FALSE;

$db['curriculum']['hostname'] = "127.0.0.1";
$db['curriculum']['username'] = "cmnst";
$db['curriculum']['password'] = "2080103";
$db['curriculum']['database'] = "cmnst_curriculum";
$db['curriculum']['dbdriver'] = "mysql";
$db['curriculum']['dbprefix'] = "";
$db['curriculum']['pconnect'] = FALSE;
$db['curriculum']['db_debug'] = TRUE;
$db['curriculum']['cache_on'] = FALSE;
$db['curriculum']['cachedir'] = "";
$db['curriculum']['char_set'] = "utf8";
$db['curriculum']['dbcollat'] = "utf8_general_ci";
$db['curriculum']['swap_pre'] = "";
$db['curriculum']['autoinit'] = TRUE;
$db['curriculum']['stricton'] = FALSE;

$db['cash']['hostname'] = "127.0.0.1";
$db['cash']['username'] = "cmnst";
$db['cash']['password'] = "2080103";
$db['cash']['database'] = "cmnst_cash";
$db['cash']['dbdriver'] = "mysql";
$db['cash']['dbprefix'] = "";
$db['cash']['pconnect'] = FALSE;
$db['cash']['db_debug'] = TRUE;
$db['cash']['cache_on'] = FALSE;
$db['cash']['cachedir'] = "";
$db['cash']['char_set'] = "utf8";
$db['cash']['dbcollat'] = "utf8_general_ci";
$db['cash']['swap_pre'] = "";
$db['cash']['autoinit'] = TRUE;
$db['cash']['stricton'] = FALSE;

$db['test']['hostname'] = "127.0.0.1";
$db['test']['username'] = "cmnst";
$db['test']['password'] = "2080103";
$db['test']['database'] = "test";
$db['test']['dbdriver'] = "mysql";
$db['test']['dbprefix'] = "";
$db['test']['pconnect'] = FALSE;
$db['test']['db_debug'] = TRUE;
$db['test']['cache_on'] = FALSE;
$db['test']['cachedir'] = "";
$db['test']['char_set'] = "utf8";
$db['test']['dbcollat'] = "utf8_general_ci";
$db['test']['swap_pre'] = "";
$db['test']['autoinit'] = TRUE;
$db['test']['stricton'] = FALSE;

//$db['accounting']['hostname'] = "127.0.0.1";
//$db['accounting']['username'] = "cmnst";
//$db['accounting']['password'] = "2080103";
//$db['accounting']['database'] = "cmnst_curriculum";
//$db['accounting']['dbdriver'] = "mysql";
//$db['accounting']['dbprefix'] = "";
//$db['accounting']['pconnect'] = FALSE;
//$db['accounting']['db_debug'] = TRUE;
//$db['accounting']['cache_on'] = FALSE;
//$db['accounting']['cachedir'] = "";
//$db['accounting']['char_set'] = "utf8";
//$db['accounting']['dbcollat'] = "utf8_general_ci";
//$db['accounting']['swap_pre'] = "";
//$db['accounting']['autoinit'] = TRUE;
//$db['accounting']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */