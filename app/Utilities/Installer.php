<?php
namespace App\Utilities;

use Illuminate\Support\Facades\File;

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


    public static function createEnvFile()
    {
        // Rename file
        if (is_file(base_path('.env.example'))) {
            File::move(base_path('.env.example'), base_path('.env'));
        }
        // Update .env file
        static::updateEnvFile([
            'APP_KEY'   =>  'base64:'.base64_encode(random_bytes(32)),
        ]);
    }

    public static function finalTouches()
    {
        // Update .env file
        static::updateEnvFile([
            'APP_LOCALE'    =>  session('locale'),
            'APP_INSTALLED' =>  'true',
            'APP_DEBUG'     =>  'false',
        ]);
        // Rename the robots.txt file
        try {
            File::move(base_path('robots.txt.dist'), base_path('robots.txt'));
        } catch (\Exception $e) {
            // nothing to do
        }
    }

    public static function updateEnvFile($data)
    {
        if (empty($data) || !is_array($data) || !is_file(base_path('.env'))) {
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
}
