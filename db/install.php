<?php
/**
 * Setup for repository_res plugin.
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */

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
