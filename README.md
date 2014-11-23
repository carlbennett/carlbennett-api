# CarlBennett API
## Preface
- Designed to use the common MVC (Controller, Model, View) paradigm without the use of a PHP framework.
- Provides an API layer for Carl Bennett's website.
- Implements external services such as HipChat webhooks and weather reporting.

## Installation
1. Clone the repository into a directory accessible by your web server, such as: ```/home/nginx/carlbennett-api/```
2. Ensure PHP is available to your web server. The following packages are required by this project:
 - [php-pecl-geoip](https://php.net/manual/book.geoip.php)
 - [php-pecl-http](https://php.net/manual/http.install.php)
 - (newrelic)[https://docs.newrelic.com/docs/agents/php-agent/getting-started/new-relic-php] _(optional)_
3. Tell your web server about the new site by creating a new virtualhost/server config entry for this site.
 - This project expects to handle the entire request URI itself and not have it pre-processed by your web server. Your server should map requests to the ```/static/``` directory, and if the request cannot be mapped there (i.e. the client would get a 404 Not Found), then should fallback to letting ```/main.php``` handle the request.
4. Access the API using a URL such as [local.api.carlbennett.me/status](https://local.api.carlbennett.me/status).
