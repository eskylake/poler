<?php
namespace App\core;

/**
 * All needed paths are defined in this class.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */

class Path
{
    /**
     * Directory separator symbol.
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Parent directory path.
     */
    const PARENT_PATH = '..' . self::DS;

    /**
     * App directory path.
     */
    const APP_PATH = self::PARENT_PATH . 'app' . self::DS;

    /**
     * Core directory path.
     */
    const CORE_PATH = __DIR__ . self::DS;

    /**
     * Controllers directory path.
     */
    const CONTROLLER_PATH = self::APP_PATH . 'controllers' . self::DS;

    /**
     * Global controller namespace.
     */
    const CONTROLLER_GLOBAL_NAMESPACE = "\\App\\controllers\\";

    /**
     * Controllers namespace.
     */
    const CONTROLLER_NAMESPACE = "App\\controllers\\";

    /**
     * Models directory path.
     */
    const MODEL_PATH = self::APP_PATH . 'models' . self::DS;

    /**
     * Views directory path.
     */
    const VIEW_PATH = self::APP_PATH . 'views' . self::DS;

    /**
     * Layouts directory path.
     */
    const LAYOUT_PATH = self::VIEW_PATH . 'layouts' . self::DS;

    /**
     * Public directory path.
     */
    const PUBLIC_PATH = self::PARENT_PATH . 'public' . self::DS;
}