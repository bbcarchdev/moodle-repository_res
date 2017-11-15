# RES Moodle plugin

## Description

This plugin enables users to find media items indexed by the [Research and Education Space (RES)](http://res.space/) and insert them into [Moodle](http://moodle.org/), the popular virtual learning environment.

RES is an open data platform built by the BBC. The platform indexes and organises the digital collections of libraries, museums, broadcasters and galleries to make their content more discoverable, accessible and usable to those in UK education and research. Images, TV and radio programmes, documents and text from world class organisations such as The British Museum, British Library, The National Archives, Europeana, Wellcome Trust and the BBC are all being indexed by RES. The majority of the media indexed by RES is free to use for educational purposes.

The plugin searches the RES index, which is completely free to use. By default, only media which can be used by anyone are shown in search results. A user can optionally expand the search to include media which are restricted to particular groups of users (for example, [Authorised users of BoB National](http://bufvc.ac.uk/tvandradio/bob) who can access TV and radio broadcasts on demand).

## How it works

Once installed, the RES Moodle plugin is available as a repository plugin, and can be accessed as follows:

1. Go to a course.
2. Click on the settings icon (cog in the top right) and select *Turn editing on* for the course.
3. Click the *+ Add activity or resource* link.
4. In the *Add an activity or resource* pop-up, select the *URL* radio button (at the bottom of the list) and click *Add*.
5. Click the *Choose a link...* button, then select **RES** from the list of available plugins.

The search interface enables simple search and visualisation of the [RES API](http://acropolis.org.uk/), showing descriptions of media items and thumbnails where available. The optional audience expander drop-down (see `screenshots/moodle_plugin_blank.png`) can be used to include media which are only available to certain audiences.

Selecting a media item triggers a pop-up which can be used to insert the item's URL into Moodle. The URL of the item may point to a full web page (denoted as *Playable media* in the plugin), an embeddable image, video or audio item (denoted as *Embeddable media*), or a web page (denoted as *Web pages*).

Note that some playable media or web pages may prompt the user for authentication credentials or require agreement to terms and conditions before media are shown. By contrast, embeddable media should be openly accessible.

## Technical details

The plugin code is available at [`moodle-repository_res`](https://github.com/bbcarchdev/moodle-repository_res).

This code connects to a [`res_search_service`](https://github.com/bbcarchdev/res_search_service) instance. res_search_service is a piece of middleware which runs as a standalone web service with HTML UI. It converts RDF from the RES platform to JSON, and does the logic to find RES resources with associated media.

In the Moodle context, the res_search_service UI acts as an [external file chooser](https://docs.moodle.org/dev/Repository_plugins_embedding_external_file_chooser): a user can search for topics with related media, choose a topic, then select a media item. The URL of the item is then incorporated into a piece of Moodle content.

res_search_service uses [`liblod-php`](https://github.com/bbcarchdev/liblod-php) to communicate with the RES API. liblod-php is a standalone PHP library for Linked Open Data (NB it isn't specific to RES).

[`res_moodle_plugin_distro_maker`](https://github.com/bbcarchdev/res_moodle_plugin_distro_maker) is a set of scripts which pull together moodle-respository_res, res_search_service and liblod-php, as well as their dependencies, into a single distributable zip file. This can be installed into Moodle as a plugin in the usual way.

Alternatively, res_search_service can run on a dedicated server and the Moodle plugin configured to talk to it remotely. res_search_service can even be run on its own as a simple UI for accessing the RES API.

Finally, [`res_moodle_stack`](https://github.com/bbcarchdev/res_moodle_stack) provides a [Docker](http://www.docker.com/) configuration for testing the plugin inside a Moodle instance. This runs Moodle and the plugin distribution (made by `res_moodle_plugin_distro_maker`) on Apache and MariaDB.

## Contributing

Please log any issues on [github](https://github.com/bbcarchdev/moodle-repository_res/issues). Code contributions via github pull requests are welcome.

## Author

Elliot Smith - elliot.smith@bbc.co.uk

## Licence

This project is licensed under the terms of the [GNU General Public License (GPL) version 3](https://www.gnu.org/licenses/gpl-3.0.txt), as required by the [Moodle plugin directory](https://docs.moodle.org/dev/Plugin_contribution_checklist#Licensing).

Copyright © 2017 BBC

Lightbulb icon from https://octicons.github.com/, released under the [SIL OFL](http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL).
