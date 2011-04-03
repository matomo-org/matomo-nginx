# Nginx configuration for Piwik

## Introduction 

   This is a nginx configuration for running [Piwik](http://piwik.org "Piwik").
   It assumes that the domain affect to Piwik is `stats.example.com`.

   Change accordingly to reflect your server setup.

## Features

   1. Filtering of invalid HTTP `Host` headers.

   2. Filtering of referrer when serving the piwik JS or any other
      static file.

   3. Hiding of all text files.

   4. Constraining of PHP file handling. Only `index.php` and
      `piwik.php` are allowed. All other attempts to run a PHP file
      return a 404.

   5. IPv6 and IPv4 support.

   6. Possibility of using **Apache** as a backend for dealing with
      PHP. Meaning using Nginx as
      [reverse proxy](http://wiki.nginx.org/HttpProxyModule "Nginx Proxy Module").


## Nginx as a Reverse Proxy: Proxying to Apache for PHP

   If you **absolutely need** to use the rather _bad habit_ of
   deploying web apps relying on `.htaccess`, or you just want to use
   Nginx as a reverse proxy. The config allows you to do so. Note that
   this provides some benefits over using only Apache, since Nginx is
   much faster than Apache. Furthermore you can use the proxy cache
   and/or use Nginx as a load balancer. 

## Installation

   1. Move the old `/etc/nginx` directory to `/etc/nginx.old`.
   
   2. Clone the git repository from github:
   
      `git clone https://github.com/perusio/piwik-nginx.git`
   
   3. Edit the `sites-available/stats.example.com` configuration file to
      suit your requirements. Namely replacing stats.example.com with
      **your** domain.
   
   4. Setup the PHP handling method. It can be:
   
      + Upstream HTTP server like Apache with mod_php. To use this
        method comment out the 
          `include upstream_phpcgi.conf;`
        line in `nginx.conf` and uncomment the lines:
        
            include reverse_proxy.conf;
            include upstream_phpapache.conf;

        Now you must set the proper address and port for your
        backend(s) in the `upstream_phpapache.conf`. By default it
        assumes the loopback `127.0.0.1` interface on port
        `8080`. Adjust accordingly to reflect your setup.

        Comment out **all**  `fastcgi_pass` directives in either
        `drupal_boost.conf` or `drupal_boost_drush.conf`, depending
        which config layout you're using. Uncomment out all the
        `proxy_pass` directives. They have a comment around them,
        stating these instructions.
      
      + FastCGI process using php-cgi. In this case an
        [init script](https://github.com/perusio/php-fastcgi-debian-script
        "Init script for php-cgi") is
        required. This is how the server is configured out of the
        box. It uses UNIX sockets. You can use TCP sockets if you prefer.
      
      + [PHP FPM](http://www.php-fpm.org "PHP FPM"), this requires you
        to configure your fpm setup, in Debian/Ubuntu this is done in
        the `/etc/php5/fpm` directory.
        
      Check that the socket is properly created and is listening. This
      can be done with `netstat`, like this for UNIX sockets:
      
        `netstat --unix -l`
         
      or like this for TCP sockets:    
                  
        `netstat -t -l`
   
      It should display the PHP CGI socket.
   
      Note that the default socket type is UNIX and the config assumes
      it to be listening on `unix:/tmp/php-cgi/php-cgi.socket`, if
      using the `php-cgi`, or in `unix:/var/run/php-fpm.sock` using
      `php-fpm` and that you should **change** to reflect your setup
      by editing `upstream_phpcgi.conf`.


   5. Create the `/etc/nginx/sites-enabled` directory and enable the
      virtual host using one of the methods described below.
    
   6. Reload Nginx:
   
      `/etc/init.d/nginx reload`
   
   7. Check that your site is working using your browser.
   
   8. Remove the `/etc/nginx.old` directory.
   
   9. Done.

## Acessing the php-fpm status and ping pages

   You can get the
   [status and a ping](http://forum.nginx.org/read.php?3,56426) pages
   for the running instance of `php-fpm`. There's a
   `php_fpm_status.conf` file with the configuration for both
   features.
   
   + the **status page** at `/fpm-status`;
     
   + the **ping page** at `/ping`.

   For obvious reasons these pages are acessed only from a given set
   of IP addresses. In the suggested configuration only from
   localhost and non-routable IPs of the 192.168.1.0 network.
    
   To enable the status and ping pages uncomment the line in the
   `stats.example.com` virtual host configuration file.


## Valid referers and resource usage constraining

   Note that this configuration assumes that you're stating exactly
   **which** hosts can use your Piwik installation. In the example
   config, for all static files, i.e., images, Javascript, Flash and
   CSS there's a `valid_referers` block where all allowed hosts are
   enumerated. You should replace the `*.mysite.com` and
   `othersite.com` with the hosts where you want Piwik to be used for
   analytics.
   
   If that's too much of an hassle for you, then just comment out the
   `valid_referers` block.
   
   If you're using this configuration and you're not getting any
   results for a particular site where you have Piwik enabled, then
   first check for the `valid_referers` block. To see if that host is
   enumerated there.
   
   
## Getting the latest Nginx packaged for Debian or Ubuntu

   I maintain a [debian repository](http://debian.perusio.net/unstable
   "my debian repo") with the
   [latest](http://nginx.org/en/download.html "Nginx source download")
   version of Nginx. This is packaged for Debian **unstable** or
   **testing**. The instructions for using the repository are
   presented on this [page](http://debian.perusio.net/debian.html
   "Repository instructions").
 
   It may work or not on Ubuntu. Since Ubuntu seems to appreciate more
   finding semi-witty names for their releases instead of making clear
   what's the status of the software included. Is it
   **stable**? Is it **testing**? Is it **unstable**? The package may
   work with your currently installed environment or not. I don't have
   the faintest idea which release to advise. So you're on your
   own. Generally the APT machinery will sort out for you any
   dependencies issues that might exist.


## My other Nginx configs on github

   + [Drupal](https://github.com/perusio/drupal-with-nginx "Drupal
     Nginx configuration")
     
   + [WordPress](https://github.com/perusio/wordpress-nginx "WordPress Nginx
     configuration")
     
   + [Chive](https://github.com/perusio/chive-nginx "Chive Nginx
     configuration")

## Securing your PHP configuration

   I have created a small shell script that parses your `php.ini` and
   sets a sane environment, be it for **development** or
   **production** settings. 
   
   Grab it [here](https://github.com/perusio/php-ini-cleanup "PHP
   cleanup script").
