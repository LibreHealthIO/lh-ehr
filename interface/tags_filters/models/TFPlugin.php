<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 5/19/16
 * Time: 11:21 AM
 */
use PluginSystem\AbstractPlugin;

class TFPlugin extends AbstractPlugin
{
    public function migrationsLocation()
    {
        return __DIR__."/../migrations";
    }

    public function startFileLocation()
    {
        return __DIR__."/../start.php";
    }

    public function pluginName()
    {
        return "Tags & Filters";
    }
}