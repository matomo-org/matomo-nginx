# Nginx Configuration for Matomo

This is a small nginx configuration that should help you get your own Matomo instance running and start collecting your own analytics.


## I already know nginx

In this case it should be enough to just take the `sites-available/matomo.conf`, check if everything is configured as you like it and enable the config.

## I want to get started

- clone this repostitory or [download it as a zip](https://github.com/matomo-org/matomo-nginx/archive/master.zip) then move its content to `/etc/nginx/` (or wherever you store your nginx-config)
- read through the `sites-available/matomo.conf` and modify the settings to fit your use case:
	- set `server_name` to the domain(s) of your Matomo instance
	- set the path to your SSL certificate (I really recommend you to make sure your Matomo instance is only reachable via HTTPS. If you don't have an SSL certificate for your domain yet, check out [Let's Encrypt](https://letsencrypt.org/).)
	- do you want to support old browsers? Then you'll need to modify `ssl.conf` according to your need. (the [Mozilla SSL Config Generator](https://mozilla.github.io/server-side-tls/ssl-config-generator/) will help you)
	- replace `/var/www/matomo/` with the path to your Matomo instance
- configure PHP (this depends on your OS and PHP setup)
	- if you are using fastcgi (which is probably the case) set `fastcgi_pass` to the path of your PHP socket file
	- you can also specify a TCP port
- go to the `sites-enabled` folder of your nginx config directory
- enable the Matomo config by creating a symlink: `sudo ln -s ../sites-available/matomo.conf`
- test if there is a syntax error in your config: `sudo nginx -t`
- restart nginx: `sudo systemctl restart`


If you need to check the legacy nginx Matomo configuration, you can find it here: https://github.com/matomo-org/matomo-nginx/tree/1.0.99
