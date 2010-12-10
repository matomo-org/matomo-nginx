# Nginx configuration for Piwik

## Introduction 

   This is a nginx configuration for running [Piwik](http://piwik.org "Piwik).
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
   what's the status of the software included, meaning. Is it
   **stable**? Is it **testing**? Is it **unstable**? The package may
   work with your currently installed environment or not. I don't have
   the faintest idea which release to advise. So you're on your
   own. Generally the APT machinery will sort out for you any
   dependencies issues that might exist.
