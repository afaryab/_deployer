<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],



    /*
    * List of languages supported by InvoiceShelf.
    */
    'languages' => [
        ['code' => 'ar', 'name' => 'Arabic'],
        ['code' => 'nl', 'name' => 'Dutch'],
        ['code' => 'en', 'name' => 'English'],
        ['code' => 'fr', 'name' => 'French'],
        ['code' => 'de', 'name' => 'German'],
        ['code' => 'ja', 'name' => 'Japanese'],
        ['code' => 'it', 'name' => 'Italian'],
        ['code' => 'lv',
            'name' => 'Latvian'
        ],
        ['code' => 'pl', 'name' => 'Polish'],
        ['code' => 'pt_BR', 'name' => 'Portuguese (Brazilian)'],
        ['code' => 'sr', 'name' => 'Serbian Latin'],
        ['code' => 'ko', 'name' => 'Korean'],
        ['code' => 'es', 'name' => 'Spanish'],
        ['code' => 'sv', 'name' => 'Svenska'],
        ['code' => 'sk', 'name' => 'Slovak'],
        ['code' => 'vi', 'name' => 'Tiếng Việt'],
        ['code' => 'cs', 'name' => 'Czech'],
        ['code' => 'el', 'name' => 'Greek'],
        ['code' => 'hr', 'name' => 'Crotian'],
        ['code' => 'mk', 'name' => 'Macedonian'],
        ['code' => 'th', 'name' => 'ไทย'],
    ],

    /*
    * List of Fiscal Years
    */
    'fiscal_years' => [
        ['key' => 'settings.preferences.fiscal_years.january_december', 'value' => '1-12'],
        ['key' => 'settings.preferences.fiscal_years.february_january', 'value' => '2-1'],
        ['key' => 'settings.preferences.fiscal_years.march_february', 'value' => '3-2'],
        ['key' => 'settings.preferences.fiscal_years.april_march', 'value' => '4-3'],
        ['key' => 'settings.preferences.fiscal_years.may_april', 'value' => '5-4'],
        ['key' => 'settings.preferences.fiscal_years.june_may', 'value' => '6-5'],
        ['key' => 'settings.preferences.fiscal_years.july_june', 'value' => '7-6'],
        ['key' => 'settings.preferences.fiscal_years.august_july', 'value' => '8-7'],
        ['key' => 'settings.preferences.fiscal_years.september_august', 'value' => '9-8'],
        ['key' => 'settings.preferences.fiscal_years.october_september', 'value' => '10-9'],
        ['key' => 'settings.preferences.fiscal_years.november_october', 'value' => '11-10'],
        ['key' => 'settings.preferences.fiscal_years.december_november', 'value' => '12-11'],
    ],
];
