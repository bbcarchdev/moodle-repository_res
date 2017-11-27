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
 * Repository plugin which uses an external application to enable users to
 * select RES media URLs as resources.
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

class repository_res extends repository {

    /**
     * Create a default instance of the plugin when the plugin starts.
     * This will point at the default BBC-maintained RES Moodle plugin
     * service.
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
     */
    public static function get_instance_option_names() {
        $optionNames = array('pluginservice_url');
        return array_merge(parent::get_instance_option_names(), $optionNames);
    }

    /**
     * An instance can be configured to point at any RES Moodle plugin service
     * instance, but defaults to the one maintained by the BBC.
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
     */
    public function get_listing($path = null, $page = null) {
        // Load external filepicker.
        $callbackUrl = new moodle_url('/') .
                       'repository/res/callback.php?repo_id=' . $this->id;

        $pluginserviceUrl = $this->get_option('pluginservice_url') .
                            '?callback=' . urlencode($callbackUrl);

        return array(
            'nologin' => true,
            'norefresh' => true,
            'nosearch' => true,
            'object' => array(
                'type' => 'text/html',
                'src' => $pluginserviceUrl
            )
        );
    }

    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }
}
