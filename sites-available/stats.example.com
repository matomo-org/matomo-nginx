# -*- mode: nginx; mode: flyspell-prog; mode: autopair; ispell-local-dictionary: "american" -*-
### Nginx configuration for Piwik.

server {
    ## This is to avoid the spurious if for sub-domain name
    ## rewriting. See http://wiki.nginx.org/Pitfalls#Server_Name.
    listen [::]:80;
    server_name www.stats.example.com;
    rewrite ^ $scheme://stats.example.com$request_uri? permanent;
} # server domain rewrite.

 
server {
    listen [::]:80;
    limit_conn arbeit 10;
    server_name stats.example.com;

    ## Parameterization using hostname of access and log filenames.
    access_log  /var/log/nginx/stats.example.com_access.log;
    error_log   /var/log/nginx/stats.example.com_error.log;

    ## Include the blacklist.conf file.
    include sites-available/blacklist.conf;

    ## Disable all methods besides HEAD, GET and POST.
    if ($request_method !~ ^(GET|HEAD|POST)$ ) {
        return 444;
    }

    root  /var/www/sites/stats.example.com/;
    index  index.php index.html;

    ## Disallow any usage of piwik assets if referer is non valid.
    location ~* ^.+\.(?:jpg|png|css|gif|jpeg|js|swf)$ {
        ## Defining the valid referers.
        valid_referers none blocked *.mysite.com othersite.com;
        if ($invalid_referer)  {
            return 444;
        }
        expires max;
        ## No need to bleed constant updates. Send the all shebang in one
        ## fell swoop.
        tcp_nodelay off;
    }

    ## Support for favicon. Return a 204 (No Content) if the favicon
    ## doesn't exist.
    location = /favicon.ico {
        try_files /favicon.ico =204;
    }

    ## Try all locations and relay to index.php as a fallback.
    location / {
        try_files $uri /index.php;        
    }

    ## Relay all index.php requests to fastcgi.
    location = /index.php {
        fastcgi_pass phpcgi;
    }

    ## Relay all piwik.php requests to fastcgi.
    location = /piwik.php {
        fastcgi_pass phpcgi;
    }
    

    ## Any other attempt to access PHP files returns a 404.
    location ~* ^.+\.php$ {
        return 404; 
    }
    
    ## Return a 404 for all text files.
    location ~* ^/(?:README|LICENSE[^.]*|LEGALNOTICE)(?:\.txt)*$ {
        return 404;        
    }

    # # The 404 is signaled through a static page.
    # error_page  404  /404.html;

    # ## All server error pages go to 50x.html at the document root.
    # error_page 500 502 503 504  /50x.html;
    # location = /50x.html {
    # 	root   /var/www/nginx-default;
    # }
} # server
