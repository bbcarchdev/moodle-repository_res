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
 * Setup for repository_res plugin.
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Install repository_res data into database.
 *
 * @return bool A status indicating success or failure
 */
function xmldb_repository_res_install() {
    global $CFG;

    require_once($CFG->dirroot.'/repository/lib.php');

    $resplugin = new repository_type('res', array(), true);

    return $resplugin->create(true);
}
