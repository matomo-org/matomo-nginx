# Nginx configuration for Piwik

## Introduction 

   This is a nginx configuration for running [Piwik](http://piwik.org "Piwik").
   It assumes that the domain assigned to Piwik is `stats.example.com`.

   Change this according to your server setup.

## Features

   1. Filtering of invalid HTTP `Host` headers.

   2. Filtering of referrer when serving the Piwik JS or any other
      static files.

   3. Hiding of all text files.

   4. Restricted handling of PHP files. Only `index.php` and
      `piwik.php` are allowed. All other attempts to run a PHP file
      return a 404.

   5. IPv6 and IPv4 support.

   6. Possibility of using **Apache** as a backend for dealing with
      PHP. This means using Nginx as a
      [reverse proxy](http://wiki.nginx.org/HttpProxyModule "Nginx
      Proxy Module").
   
   7. Static files use the OS [buffer cache](http://wiki.nginx.org/HttpCoreModule#open_file_cache).
   
   8. Caching of most Piwik pages with exceptions for the installation
      and administrative tasks.
   
   9. `piwik.php` is cached with a long TTL (2h). Hence faster to
      register an access.
      
   10. Inline `robots.txt` that disables all crawling.  

## Nginx as a Reverse Proxy: Proxying to Apache for PHP

   This applies if you **absolutely need** to use the rather _bad habit_ of
   deploying web apps relying on `.htaccess`, or you just want to use
   Nginx as a reverse proxy. The config allows you to do so. Note that
   this provides some benefits over using only Apache, since Nginx is
   much faster than Apache. Furthermore you can use the proxy cache
   and/or use Nginx as a load balancer. 

## IPv6 and IPv4

The configuration of the example vhosts uses **separate** sockets for
IPv6 and IPv4. This way is simpler for those not (yet) having IPv6
support to disable it by commenting out the
[`listen`](http://nginx.org/en/docs/http/ngx_http_core_module.html#listen)
directive with the `ipv6only=on` parameter.

Note that the IPv6 address uses an IP _stolen_ from the
[IPv6 Wikipedia page](https://en.wikipedia.org/wiki/IPv6). You **must
replace** the indicated address by **your** address.

## Installation

 1. Move the old `/etc/nginx` directory to `/etc/nginx.old`.
   
 2. Clone the git repository from github:
   
    `git clone https://github.com/perusio/piwik-nginx.git /etc/nginx`
   
 3. Edit the `sites-available/stats.example.com.conf` configuration
    file to suit your needs. Especially replace stats.example.com
    with **your** domain.
   
 4. Setup the PHP handling method. It can be:   
   + Upstream HTTP server like Apache with mod_php. To use this
     method comment out the `include upstream_phpcgi.conf;`
     line in `nginx.conf` and uncomment the lines:
        
          include reverse_proxy.conf;
          include upstream_phpapache.conf;

      Now you must set the proper address and port for your
      backend(s) in the `upstream_phpapache.conf`. By default it
      assumes the loopback `127.0.0.1` interface on port
      `8080`. Adjust accordingly to reflect your setup.

      Comment out **all** `fastcgi_pass` directives in
      `stats.example.com.conf` Uncomment out all the `proxy_pass`
      directives. They have a comment around them, stating these
      instructions.      
   + FastCGI process using php-cgi. In this case an
     [init script](https://github.com/perusio/php-fastcgi-debian-script
     "Init script for php-cgi") is
     required. 
   + [PHP FPM](http://www.php-fpm.org "PHP FPM"), this requires you to
     configure your fpm setup, in Debian/Ubuntu this is done in the
     `/etc/php5/fpm` directory. This is how the server is configured
     out of the box. It uses UNIX sockets. You can use TCP sockets if
     you prefer.
        
     Look [here](https://github.com/perusio/php-fpm-example-config) for
     an **example configuration** of `php-fpm`.
        
     Check that the socket is properly created and is listening. This
     can be done with `netstat`, like this for UNIX sockets:
      
         netstat --unix -l
         
      or like this for TCP sockets:    
                  
         netstat -t -l
   
      It should display the PHP CGI socket.
   
      Note that the default socket type is UNIX and the config assumes
      it to be listening on `unix:/tmp/php-cgi/php-cgi.socket`, if
      using the `php-cgi`, or in `unix:/var/run/php-fpm.sock` using
      `php-fpm` and that you should **change** to reflect your setup
      by editing `upstream_phpcgi.conf`.

 5. Setup the cache for `piwik.php`. It depends if you use either
    FastCGI or Apache for processing PHP.       
  + **FastCGI**: Create the `/var/cache/nginx/fcgicache` directory
      if you're serving PHP with php-fpm or php cgi. This directory
      must be owned by the unpriveleged nginx user. In debian it's
      `www-data`.    
  + **Apache**: Create the `/var/cache/nginx/proxycache` directory
      if you're serving PHP with Apache. This directory must be owned
      by the unpriveleged nginx user. In debian it's
      `www-data`. Comment out all the lines where `fcgi_cache` is
      referenced. You must uncomment the line `include
      proxy_cache_zone.conf;` on `nginx.conf`.

 6. Create the `/etc/nginx/sites-enabled` directory and enable the
    virtual host using one of the methods described below.
    
    Note that if you're using the
    [nginx_ensite](http://github.com/perusio/nginx_ensite) script
    described below it **creates** the `/etc/nginx/sites-enabled`
    directory if it doesn't exist the first time you run it for
    enabling a site.
    
 7. Reload Nginx:
   
    `/etc/init.d/nginx reload`
   
 8. Check that your site is working using your browser.
   
 9. Remove the `/etc/nginx.old` directory.
   
 10. Done.

## Caching status
   
   You can check if the responses are being cached or not. For the
   short term cache check for a `X-Piwik-Cache` header with a
   `HIT/EXPIRED/MISS/UPDATING/STALE` value. The same applies to the
   long cache: check for a `X-Piwik-Long-Cache` header with a
   `HIT/EXPIRED/MISS/UPDATING/STALE` value.

   Example: 
   
       curl -I stats.example.com/piwik.php
       HTTP/1.1 200 OK
       Server: nginx
       Date: Mon, 02 Jan 2012 13:17:15 GMT
       Content-Type: text/html
       Connection: keep-alive
       Keep-Alive: timeout=10
       Vary: Accept-Encoding
       Expires: Thu, 01 Jan 1970 00:00:01 GMT
       Cache-Control: no-cache
       X-Piwik-Long-Cache: MISS

## Acessing the php-fpm status and ping pages

You can get the
[status and ping](http://forum.nginx.org/read.php?3,56426) pages for
the running instance of `php-fpm`. There is a `php_fpm_status.conf`
file with the configuration for both features.
   
 + the **status page** at `/fpm-status`;
     
 + the **ping page** at `/ping`.

For obvious reasons access to these pages is restricted to a given set
of IP addresses. In the suggested configuration only from localhost
and non-routable IPs of the 192.168.1.0 network.

The allowed hosts are defined in a geo block in file
`php_fpm_status_allowed_hosts.conf`. You should edit the predefined IP
addresses to suit your setup.
    
To enable the status and ping pages uncomment the line in the
`stats.example.com.conf` virtual host configuration file.

## Valid referrers and resource usage constraining

   Note that this configuration assumes that you are stating exactly
   **which** hosts can use your Piwik installation. In the example
   config, for all static files, i.e., images, Javascript, Flash and
   CSS there is a `valid_referers` block where all allowed hosts are
   enumerated. You should replace the `*.mysite.com` and
   `othersite.com` with the hosts where you want Piwik to be used for
   analytics.
   
   If that is too much of an hassle for you, then just comment out the
   `valid_referers` block.
   
   If you are using this configuration and you are not getting any
   results for a particular site where you have Piwik enabled, then
   first check for the `valid_referers` block. To see if that host is
   enumerated there.

## Blacklisting User Agents and Referers

  There are some serious issues with some User Agents out there. Some
  are operated in a bandwidth hogging fashion. Implementing bots that
  use and abuse the site is bandwidth. Even more serious is when the User
  Agent is used for exploits. Trying to penetrate/crack the site
  through crafted scripts running under the cloak of a _well meaning_
  bot.
  
  The same applies to Referers where shady sites sent traffic to you
  that only hijacks the bandwith and curtail the correct usage of
  Piwik.
  
  There is a blacklist of User Agents and Referers that is disabled by
  default. You have to **enable it** explicitly.
  
  Uncomment the `include blacklist.conf` line in the `nginx.conf`
  configuration file to enable User Agent and Referer blacklisting. Of
  course you can define your own list of blacklisted User Agents.
  
## Getting the latest Nginx packaged for Debian or Ubuntu

   There's a [Debian repository](http://debian.perusio.net/unstable
   "my Debian repo") with the
   [latest](http://nginx.org/en/download.html "Nginx source download")
   version of Nginx. This is packaged for Debian **unstable** or
   **testing**. The instructions for using the repository are
   presented on this [page](http://debian.perusio.net/debian.html
   "Repository instructions").
 
   It may work on Ubuntu. Since Ubuntu seems to appreciate more
   finding semi-witty names for their releases instead of making clear
   what is the status of the software included. Is it
   **stable**? Is it **testing**? Is it **unstable**? The package may
   work with your currently installed environment or not. I don't have
   the faintest idea which release to advise. So you are on your
   own. Generally the APT machinery will sort out for you any
   dependencies issues that might exist.

## Other Nginx configs on github

   + [Drupal](https://github.com/perusio/drupal-with-nginx "Drupal
     Nginx configuration")
     
   + [WordPress](https://github.com/perusio/wordpress-nginx "WordPress Nginx
     configuration")
     
   + [Chive](https://github.com/perusio/chive-nginx "Chive Nginx
     configuration")

   + [Redmine](https://github.com/perusio/redmine-nginx "Redmine Nginx
     configuration")

   + [SquirrelMail](https://github.com/perusio/squirrelmail-nginx
     "SquirrelMail Nginx configuration")

## Securing your PHP configuration

   There's a small shell script that parses your `php.ini` and
   sets a sane environment, be it for **development** or
   **production** settings. 
   
   Grab it [here](https://github.com/perusio/php-ini-cleanup "PHP
   cleanup script").

## Keeping PHP always running

   php-fpm is hardly perfect, or better said, it happens quite often
   that PHP has extremely long running processes that can completely
   clog PHP processing. To obviate that I've created a shell script
   that relaunches php-fpm whenever PHP hangs.
   
   Grab it [here](https://github.com/perusio/php-relaunch-web).

## Authors

   This configuration is maintained by
   [celogeek](https://github.com/geistteufel) and
   [perusio](https://github.com/perusio).
