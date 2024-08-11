# Dokuwiki whennotfound plugin (previously named autosearch)

whennotfound plugin handles non-existent pages and does one of the actions:

* "startpage": If the non-existent page is a namespace (folder) and an index page exists within,  redirect to that index page. The names of the index pages to look for are configurable.
* "translation": Check if an available translation of the requested page exists and if so, redirect to it.
* "pagelist": Show the list of subpages (requires the [indexmenu plugin](https://www.dokuwiki.org/plugin:indexmenu)).
* "search": Will redirect to the search page (if dokuwiki's search action is not disabled).
* "send404": Send a 404 error page.
* "PAGE:...": Redirect to a specific page (e.g, PAGE:mynotfoundpage). These pages are resolved by finding the nearest such page in the folders of and above the requested page. Use an extra colon if you want the page to be resolved from the root of the wiki (e.g., PAGE::mynotfoundpage).

You can configure which of these actions to take and in what order to try them in the plugin's configuration settings. Multiple actions (as well as multiple PAGE: actions) can be specified as a comma-spearated list.
