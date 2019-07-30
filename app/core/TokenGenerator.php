<?php
namespace App\core;

/**
 * This class contains all methods to generate tokens to be used in the project.
 * 
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class TokenGenerator
{
    /**
     * Generate csrf token.
     * 
     * @return string hex of random bytes.
     */
    public static function csrfToken(): string
    {
        return bin2hex(random_bytes(64));
    }
}