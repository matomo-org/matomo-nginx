# Nginx Configuration for Matomo

This is a small nginx configuration that should help you get your own Matomo instance running and start collecting your own analytics.

## I already know nginx

In this case it should be enough to just take `sites-available/matomo.conf`, check if everything is configured as you like it and enable the config.

## I want to get started

- Clone this repository or [download it as a zip](https://github.com/matomo-org/matomo-nginx/archive/master.zip) then move its contents to `/etc/nginx/` (or wherever you store your nginx config).
- Read through `sites-available/matomo.conf` and modify it to fit your use case:
	- Set `server_name` to the domain(s) of your Matomo instance
	- Set the path to your SSL certificate (We recommend you make sure your Matomo instance is reachable only via HTTPS. If you don't have an SSL certificate for your domain yet, check out [Let's Encrypt](https://letsencrypt.org/).)
	- In case you want to support old browsers, you'll need to modify `ssl.conf` according to your needs. The [Mozilla SSL Config Generator](https://mozilla.github.io/server-side-tls/ssl-config-generator/) can help you.
	- Replace `/var/www/matomo/` with the path to your Matomo instance.
- Configure PHP (this depends on your OS and PHP setup):
	- If you are using fastcgi (which is probably the case) set `fastcgi_pass` to the path of your PHP socket file.
	- You can also specify a TCP port.
- Go to the `sites-enabled` folder of your nginx config directory.
- Enable the Matomo config by creating a symlink: `sudo ln -s ../sites-available/matomo.conf`
- Test for syntax errors in your config: `sudo nginx -t`
- Restart nginx: `sudo systemctl restart nginx`

If you need to refer to the legacy nginx Matomo configuration, find it here: https://github.com/matomo-org/matomo-nginx/tree/1.0.99

## Tips

- Never use Matomo without HTTPS.
- Make sure you have configured Nginx to accept only modern and secure cryptography:
	- Check your website with https://www.ssllabs.com/ssltest/
	- Compare your Nginx config with the "modern" template from https://mozilla.github.io/server-side-tls/ssl-config-generator/
	- This template is used by default in the `ssl.conf` file.
	- Decide if keeping outdated ciphers and TLS protocols enabled to be able to track ancient browsers is worth the risk of a downgrade attack affecting all your visitors (and admins)
	- Do not support SSLv3 and consider disabling TLSv1 and TLSv1.1.
- Add `server_tokens off;` to your config to disable the `server: nginx` header on all requests and the nginx version on error pages.
- If you have enabled gzip compression (which greatly improves performance), be aware of the [BREACH](https://en.wikipedia.org/wiki/BREACH) vulnerability.
- Consider enabling the [`Strict-Transport-Security`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security) header, but keep in mind the implications.
- Keep HTTP/2 enabled as it brings performance benefits for sites with many small files (e.g. icons).

Do you know how to improve this config? Open a pull request or GitHub issue!
