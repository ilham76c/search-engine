<?php namespace Config;

/**
 * Database Configuration
 *
 * @package Config
 */

class Database extends \CodeIgniter\Database\Config
{
	/**
	 * The directory that holds the Migrations
	 * and Seeds directories.
	 *
	 * @var string
	 */
	public $filesPath = APPPATH . 'Database/';

	/**
	 * Lets you choose which connection group to
	 * use if no other is specified.
	 *
	 * @var string
	 */
	public $defaultGroup = 'default';

	/**
	 * The default database connection.
	 *
	 * @var array
	 */
	public $default = [
		'DSN'      => '',
		'hostname' => 'ec2-54-85-80-92.compute-1.amazonaws.com',
		'username' => 'vgerdrhwteubsy',
		'password' => 'd5146bd0c0cd2edff27b811b0fa0388241e11b89957be95b5174921a3ddc2c3f',
		'database' => 'db8365ljem12rr',
		'DBDriver' => 'Postgre',
		'DBPrefix' => '',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 5432,
	];

	/**
	 * This database connection is used when
	 * running PHPUnit database tests.
	 *
	 * @var array
	 */
	public $tests = [
		'DSN'      => '',
		'hostname' => '127.0.0.1',
		'username' => '',
		'password' => '',
		'database' => ':memory:',
		'DBDriver' => 'SQLite3',
		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'cacheOn'  => false,
		'cacheDir' => '',
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// Ensure that we always set the database group to 'tests' if
		// we are currently running an automated test suite, so that
		// we don't overwrite live data on accident.
		if (ENVIRONMENT === 'testing')
		{
			$this->defaultGroup = 'tests';

			// Under Travis-CI, we can set an ENV var named 'DB_GROUP'
			// so that we can test against multiple databases.
			if ($group = getenv('DB'))
			{
				if (is_file(TESTPATH . 'travis/Database.php'))
				{
					require TESTPATH . 'travis/Database.php';

					if (! empty($dbconfig) && array_key_exists($group, $dbconfig))
					{
						$this->tests = $dbconfig[$group];
					}
				}
			}
		}
	}

	//--------------------------------------------------------------------

}
