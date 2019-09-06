<?php
namespace App\Utilities;

use App\Models\Auth\User;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Installer
{

    /**
     * Requirements
     *
     * @var array
     */
    private static $requirements = [];


    protected static $onlyInstance;


    /**
     * @return mixed
     */
    protected static function getself()
    {
        if (static::$onlyInstance === null)
        {
            static::$onlyInstance = new Installer;
        }
        return static::$onlyInstance;
    }


    /**
     * @return mixed
     */
    public static function checkEnvironmentRequirements()
    {
        if (ini_get('safe_mode')) {
            self::$requirements[] = trans('install.requirements.disabled', ['feature' => 'Safe Mode']);
        }

        if (ini_get('register_globals')) {
            self::$requirements[] = trans('install.requirements.disabled', ['feature' => 'Register Globals']);
        }

        if (ini_get('magic_quotes_gpc')) {
            self::$requirements[] = trans('install.requirements.disabled', ['feature' => 'Magic Quotes']);
        }

        if (!ini_get('file_uploads')) {
            self::$requirements[] = trans('install.requirements.enabled', ['feature' => 'File Uploads']);
        }

        if (!function_exists('proc_open')) {
            self::$requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_open']);
        }

        if (!function_exists('proc_close')) {
            self::$requirements[] = trans('install.requirements.enabled', ['feature' => 'proc_close']);
        }

        if (!class_exists('PDO')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'MySQL PDO']);
        }

        if (!extension_loaded('openssl')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'OpenSSL']);
        }

        if (!extension_loaded('tokenizer')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'Tokenizer']);
        }

        if (!extension_loaded('mbstring')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'mbstring']);
        }

        if (!extension_loaded('curl')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'cURL']);
        }

        if (!extension_loaded('xml')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'XML']);
        }

        if (!extension_loaded('zip')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'ZIP']);
        }

        if (!extension_loaded('fileinfo')) {
            self::$requirements[] = trans('install.requirements.extension', ['extension' => 'FileInfo']);
        }

        if (!is_writable(base_path('storage/app'))) {
            self::$requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app']);
        }

        if (!is_writable(base_path('storage/app/uploads'))) {
            self::$requirements[] = trans('install.requirements.directory', ['directory' => 'storage/app/uploads']);
        }

        if (!is_writable(base_path('storage/framework'))) {
            self::$requirements[] = trans('install.requirements.directory', ['directory' => 'storage/framework']);
        }

        if (!is_writable(base_path('storage/logs'))) {
            self::$requirements[] = trans('install.requirements.directory', ['directory' => 'storage/logs']);
        }

        return static::getself();
    }


    /**
     * Get requirements
     * @return array
     */
    public function getRequirements()
    {
        return self::$requirements;
    }


    /**
     * Creates an env file
     * @throws \Exception
     */
    public static function createEnvFile()
    {
        // Rename file
        if (is_file(base_path('.env.example'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
        }
        // Update .env file
        static::updateEnvFile([
            'APP_KEY'   =>  'base64:'.base64_encode(random_bytes(32)),
        ]);
    }

    /**
     * Final touches to the env file
     */
    public static function finalTouches()
    {
        // Update .env file
        static::updateEnvFile([
            'APP_LOCALE'    =>  session('locale'),
            'APP_INSTALLED' =>  'true',
            'APP_DEBUG'     =>  'false',
        ]);
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function updateEnvFile($data)
    {
        if( !is_file(base_path('.env')) ) {
            // create the env file
            static::createEnvFile();
        }

        if (empty($data) || !is_array($data) ) {
            return false;
        }

        $env = file_get_contents(base_path('.env'));
        $env = explode("\n", $env);
        foreach ($data as $data_key => $data_value) {
            $updated = false;
            foreach ($env as $env_key => $env_value) {
                $entry = explode('=', $env_value, 2);
                // Check if new or old key
                if ($entry[0] == $data_key) {
                    $env[$env_key] = $data_key . '=' . $data_value;
                    $updated = true;
                } else {
                    $env[$env_key] = $env_value;
                }
            }
            // Lets create if not available
            if (!$updated) {
                $env[] = $data_key . '=' . $data_value;
            }
        }
        $env = implode("\n", $env);
        file_put_contents(base_path('.env'), $env);
        return true;
    }


    /**
     * @param $host
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @return bool
     */
    public static function createDbTables($host, $port, $database, $username, $password)
    {
        if (!static::isDbValid($host, $port, $database, $username, $password)) {
            return false;
        }

        // Set database details
        static::saveDbVariables($host, $port, $database, $username, $password);

        // Try to increase the maximum execution time
        set_time_limit(300); // 5 minutes
        // Create tables
        Artisan::call('migrate', ['--force' => true]);

        // Create Roles
        Artisan::call('db:seed', ['--class' => 'RolesPermissionsTableSeeder', '--force' => true]);

        // Generate the JWT keys
        Artisan::call('jwt:secret');

        return true;
    }


    /**
     * Check if the database exists and is accessible.
     *
     * @param $host
     * @param $port
     * @param $database
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public static function isDbValid($host, $port, $database, $username, $password)
    {
        Config::set('database.connections.install_test', [
            'host'      => $host,
            'port'      => $port,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'driver'    => env('DB_CONNECTION', 'mysql'),
            'charset'   => env('DB_CHARSET', 'utf8mb4'),
        ]);
        try {
            DB::connection('install_test')->getPdo();
        } catch (\Exception $e) {
            return false;
        }
        // Purge test connection
        DB::purge('install_test');
        return true;
    }


    /**
     * @param $host
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public static function saveDbVariables($host, $port, $database, $username, $password)
    {
        $prefix = strtolower(Str::random(3) . '_');
        // Update .env file
        static::updateEnvFile([
            'DB_HOST'       =>  $host,
            'DB_PORT'       =>  $port,
            'DB_DATABASE'   =>  $database,
            'DB_USERNAME'   =>  $username,
            'DB_PASSWORD'   =>  $password,
            'DB_PREFIX'     =>  $prefix,
        ]);
        $con = env('DB_CONNECTION', 'mysql');
        // Change current connection
        $db = Config::get('database.connections.' . $con);
        $db['host'] = $host;
        $db['database'] = $database;
        $db['username'] = $username;
        $db['password'] = $password;
        $db['prefix'] = $prefix;
        Config::set('database.connections.' . $con, $db);
        DB::purge($con);
        DB::reconnect($con);
    }


    /**
     * @param $name
     * @param $email
     * @param $locale
     * @return Company
     */
    public static function createCompany($name, $email, $locale)
    {
        // Create company
        $company = Company::create([
            'domain' => '',
        ]);
        // Set settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->set([
            'general.company_name'          => $name,
            'general.company_email'         => $email,
            'general.default_currency'      => 'USD',
            'general.default_locale'        => $locale,
        ]);
        setting()->save();

        return $company;
    }


    /**
     * @param $email
     * @param $password
     * @param $name
     * @param $locale
     * @param $company
     * @return User
     */
    public static function createUser($email, $password, $name, $locale, $company)
    {
        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'locale' => $locale,
        ]);

        // Attach admin role
        $user->roles()->attach(1);

        // Attach company
        $user->companies()->attach($company->id);
        return $user;
    }



}
