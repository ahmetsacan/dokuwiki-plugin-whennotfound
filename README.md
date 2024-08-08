# Dokuwiki whennotfound plugin

whennotfound plugin handles non-existent pages and does one of the actions:

* "startpage": If the non-existent page is a namespace (folder) and an index page exists within,  redirect to that index page. The names of the index pages to look for are configurable.
* "translation": Check if an available translation of the requested page exists and if so, redirect to it.
* "pagelist": Show the list of subpages (requires the [indexmenu plugin](https://www.dokuwiki.org/plugin:indexmenu)).
* "search": Will redirect to the search page (if dokuwiki's search action is not disabled).
* "send404": Send a 404 error page.

You can configure which of these actions to take and in what order to try them in the plugin's configuration settings.
