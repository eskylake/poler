<?php

/**
 * This statement will require the env.php file if it exists. 
 */
if (file_exists('../env.php')) {
    require_once '../env.php';
}

/**
 * This is a global function to get env value specified by a key.
 * 
 * @param mixed $key the env parameter key name.
 * @param mixed $default the default value that is returned when the key is not defined in env.
 * 
 * @return string|null the founded value of the key in env.
 */
function env($key, $default = null): ?string
{
    $value = getenv($key); // Get the value specified by the given key from php env parameters.

    /**
     * Check the returned value from env.
     * If the value doesn't exist in env then the default value will be returned.
     */
    if ($value === false) {
        return $default;
    }

    return $value;
}

/**
 * This function dumps information about a variable.
 * 
 * @param mixed $value the value to get its dump information.
 * 
 * @return void
 */
function dd($value = null)
{
    /**
     * Pre tag is used to define the block of preformatted text which preserves the text spaces,
     * line breaks, tabs, and other formatting characters which are ignored by web browsers.
     */
    echo "<pre>";
    var_dump($value);
    die;
}

/**
 * This function print well formed information about a variable.
 * 
 * @param mixed $value the value to get its information.
 * 
 * @return void
 */
function pd($value = null)
{
    /**
     * Pre tag is used to define the block of preformatted text which preserves the text spaces,
     * line breaks, tabs, and other formatting characters which are ignored by web browsers.
     */
    echo "<pre>";
    print_r($value);
    die;
}