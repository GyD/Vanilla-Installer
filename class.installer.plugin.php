<?php
if (!defined('APPLICATION')) {
    exit();
}
/*	Copyright 2015 GyD
*	This program is free software: you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License
*	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$PluginInfo['Installer'] = array(
  'Description' => 'Vanilla Installer',
  'Version' => '0.1.0',
  'RequiredApplications' => null,
  'RequiredTheme' => false,
  'RequiredPlugins' => false,
  'HasLocale' => false,
  'Author' => "GyD",
  'AuthorEmail' => 'github@gyd.be',
  'AuthorUrl' => 'http://github.com/GyD',
  'Hidden' => false
);

class Installer extends Gdn_Plugin
{
    /** @var array configuration */
    protected $config = array();

    /**
     * Install hook
     *
     * @return bool
     */
    public function setup()
    {
        if (!file_exists(PATH_CONF . '/config.installer.php')) {
            return false;
        }

        // Prepare Configuration array
        $Configuration = array();

        // Load file with $Configuration settings inside
        // Since we are going to work the the array directly, Gdn_Configuration was not used
        require_once PATH_CONF . '/config.installer.php';

        // store config
        $this->config = $Configuration;
        unset($Configuration);

        // Install Application
        $this->installApplications();
        // Install Plugins
        $this->installPlugins();

        // For each array entry save it into the configuration file
        foreach ($this->ArrayToDotNotation($this->config) as $key => $value) {
            Gdn::config()->SaveToConfig($key, $value);
        }
    }

    /**
     * Install the plugins
     */
    private function installApplications()
    {
        if (!array_key_exists('EnabledPlugins', $this->config) OR !is_array($this->config['EnabledPlugins'])) {
            return;
        }

        /** @var Gdn_ApplicationManager $ApplicationManager */
        $ApplicationManager = new Gdn_ApplicationManager();
        $Validation = new Gdn_Validation();
        $enabledApplications = $ApplicationManager->EnabledApplications();

        foreach ($this->config['EnabledApplications'] as $applicationName => $enabled) {
            if (false === $enabled) {
                if (array_key_exists($applicationName, $enabledApplications)) {
                    $ApplicationManager->DisableApplication($applicationName);
                }
            } else {
                if (!array_key_exists($applicationName, $enabledApplications)) {
                    $ApplicationManager->EnableApplication($applicationName, $Validation);
                }
            }
            unset($this->config['EnabledApplications'][$applicationName]);
        }

    }


    /**
     * Install the plugins
     */
    private function installPlugins()
    {
        if (!array_key_exists('EnabledPlugins', $this->config) OR !is_array($this->config['EnabledPlugins'])) {
            return;
        }

        /** @var Gdn_PluginManager $pluginManager */
        $pluginManager = Gdn::PluginManager();
        $Validation = new Gdn_Validation();
        $enabledPlugins = $pluginManager->EnabledPlugins();

        foreach ($this->config['EnabledPlugins'] as $pluginName => $enabled) {
            if (false === $enabled) {
                if (array_key_exists($pluginName, $enabledPlugins)) {
                    $pluginManager->DisablePlugin($pluginName);
                }
            } else {
                if (!array_key_exists($pluginName, $enabledPlugins)) {
                    $pluginManager->EnablePlugin($pluginName, $Validation);
                }
            }
            unset($this->config['EnabledApplications'][$pluginName]);
        }

    }

    private function ArrayToDotNotation($array)
    {
        $RecursiveIIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $strings = array();
        foreach ($RecursiveIIterator as $leafValue) {
            if (empty($leafValue)) {
                continue;
            }

            $keys = array();
            foreach (range(0, $RecursiveIIterator->getDepth()) as $depth) {
                $keys[] = $RecursiveIIterator->getSubIterator($depth)->key();
            }
            $strings[implode('.', $keys)] = $leafValue;
        }

        return $strings;
    }

}