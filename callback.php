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
 * Callback handler for accepting media item selections from the RES Moodle search service.
 *
 * See https://docs.moodle.org/dev/Repository_plugins_embedding_external_file_chooser
 *
 * When a piece of media is selected in the RES Moodle plugin service,
 * the user's browser is forwarded to this page. The URL contains a
 * query string variable, "media", which is a JSON-encoded representation of the
 * selected piece of media.
 *
 * It also contains the secret key for the repo in the "repo_key" query string
 * variable. This is used to construct a sourcekey property in the resource
 * object, to verify that the selected media comes from a registered source.
 *
 * An example of the decoded JSON:
 *
 * {
 *   "sourceUri":"http://bbcimages.acropolis.org.uk/6311090#id",
 *   "uri":"http://bbcimages.acropolis.org.uk/6311090/player",
 *   "mediaType":"image",
 *   "license":"",
 *   "label":"A Blue Tit visits a bird feeder",
 *   "description":"A Blue Tit bird eating nuts from a bird feeder.",
 *   "thumbnail":"http://bbcimages.acropolis.org.uk/6311090/media/6311090-200x200.jpeg",
 *   "date":"2008-09-15",
 *   "location":"http://sws.geonames.org/2635167/"
 * }
 *
 * Some of the properties of this object are then used to populate the pop-up
 * in Moodle which enables the piece of media to be selected.
 *
 * @package   repository_res
 * @copyright BBC 2017
 * @author    Elliot Smith <elliot.smith@bbc.co.uk>
 * @license   GPL v3 - https://www.gnu.org/licenses/gpl-3.0.txt
 */

require(__DIR__ . '/../../config.php');
require_login();

// Extract and decode media from querystring
if (!isset($_GET['media'])) {
    die('media parameter must be set');
}

$media = $_GET['media'];
$selected = json_decode($_GET['media']);

if (!$selected) {
    die('media JSON could not be decoded');
}

$uri = $selected->uri;
$label = $selected->label;

$thumbnail = '';
if (property_exists($selected, 'thumbnail')) {
    $thumbnail = $selected->thumbnail;
}

$date = '';
if (property_exists($selected, 'date')) {
    $date = $selected->date;
}

// Construct the source key to verify that the selected media is from a
// trusted source
if (!isset($_GET['repo_key'])) {
    die('repo_key must be provided to verify source of media');
}

$repo_key = $_GET['repo_key'];
$sourcekey = sha1($uri . $repo_key . sesskey());

// Construct HTML which will populate the file picker pop-up
$html = <<<HTML
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <script type="text/javascript">
    window.onload = function() {
        var resource = {};
        resource.source = "$uri";
        resource.sourcekey = "$sourcekey";
        resource.title = "$label";
        resource.thumbnail = "$thumbnail";
        resource.datecreated = "$date";
        resource.author = "";
        resource.license = "";
        parent.M.core_filepicker.select_file(resource);
    }
    </script>
</head>
<body></body>
</html>
HTML;

// Output the generated HTML.
header('Content-Type: text/html; charset=utf-8');
echo $html;
