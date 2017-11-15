<?php
/**
 * Set plugin access permissions
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'repository/res:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'user' => CAP_ALLOW
        )
    )
);
