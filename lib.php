<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Repository plugin which enables users to select RES media URLs as resources.
 *
 * This requires a RES Moodle plugin service to act as its back-end and to
 * present the file chooser.
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/repository/lib.php');

/**
 * Repository plugin providing access to the RES index for media searches.
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */
class repository_res extends repository {

    /**
     * Create a default instance of the plugin when the plugin starts.
     * This will point at the default BBC-maintained RES Moodle plugin
     * service.
     *
     * @return bool
     */
    public static function plugin_init() {
        $options = array(
            'name' => 'RES',
            'pluginservice_url' => getenv('PLUGINSERVICE_URL')
        );

        $id = repository::static_function('res', 'create', 'res', 0,
                                          context_system::instance(),
                                          $options, 0);

        return !empty($id);
    }

    /**
     * Expose the RES Moodle plugin service URL as a configuration option.
     *
     * @return array
     */
    public static function get_instance_option_names() {
        $optionnames = array('pluginservice_url');
        return array_merge(parent::get_instance_option_names(), $optionnames);
    }

    /**
     * An instance can be configured to point at any RES Moodle plugin service
     * instance, but defaults to the one maintained by the BBC.
     *
     * @param object $mform
     * @param string $classname
     */
    public static function instance_config_form($mform,
                                                $classname = 'repository_res') {
        parent::instance_config_form($mform, 'repository_res');

        $mform->setDefault('name', 'RES');

        $mform->addElement('text', 'pluginservice_url',
                           get_string('res:pluginservice_url', 'repository_res'),
                           array('size' => '60'));
        $mform->setType('pluginservice_url', PARAM_URL);
        $mform->setDefault('pluginservice_url', getenv('PLUGINSERVICE_URL'));
        $mform->addRule('pluginservice_url', get_string('required'),
                        'required', null, 'client');
    }

    /**
     * The listing comes from an external file picker (provided by the RES
     * Moodle plugin service).
     *
     * @param string $path
     * @param int $page
     * @return array
     */
    public function get_listing($path = null, $page = null) {
        // Load external filepicker.
        $callbackurl = new moodle_url('/repository/res/callback.php', ['repo_id' => $this->id]);

        $pluginserviceurl = $this->get_option('pluginservice_url') .
                            '?callback=' . urlencode($callbackurl);

        return array(
            'nologin' => true,
            'norefresh' => true,
            'nosearch' => true,
            'object' => array(
                'type' => 'text/html',
                'src' => $pluginserviceurl
            )
        );
    }

    /**
     * Return list of types of resource provided by this plugin.
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }
}
