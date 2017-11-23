; <?php exit; ?> DO NOT REMOVE THIS LINE
; If you want to change some of these default values, the best practise is to override
; them in your configuration file in config/config.ini.php. If you directly edit this file,
; you will lose your changes when you upgrade Piwik.
; For example if you want to override action_title_category_delimiter,
; edit config/config.ini.php and add the following:
; [General]
; action_title_category_delimiter = "-"

;--------
; WARNING - YOU SHOULD NOT EDIT THIS FILE DIRECTLY - Edit config.ini.php instead.
;--------

[database]
host =
username =
password =
dbname =
tables_prefix =
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql
; if charset is set to utf8, Piwik will ensure that it is storing its data using UTF8 charset.
; it will add a sql query SET at each page view.
; Piwik should work correctly without this setting but we recommend to have a charset set.
charset = utf8

[database_tests]
host = localhost
username = "@USERNAME@"
password =
dbname = piwik_tests
tables_prefix = piwiktests_
port = 3306
adapter = PDO\MYSQL
type = InnoDB
schema = Mysql
charset = utf8

[tests]
; needed in order to run tests.
; if Piwik is available at http://localhost/dev/piwik/ replace @REQUEST_URI@ with /dev/piwik/
; note: the REQUEST_URI should not contain "plugins" or "tests" in the PATH
http_host   = localhost
remote_addr = "127.0.0.1"
request_uri = "@REQUEST_URI@"
port =

; access key and secret as listed in AWS -> IAM -> Users
aws_accesskey = ""
aws_secret = ""
; key pair name as listed in AWS -> EC2 -> Key Pairs. Key name should be different per user.
aws_keyname = ""
; PEM file can be downloaded after creating a new key pair in AWS -> EC2 -> Key Pairs
aws_pem_file = "<path to pem file>"
aws_securitygroups[] = "default"
aws_region = "us-east-1"
aws_ami = "ami-ac24bac4"
aws_instance_type = "c3.large"

[log]
; possible values for log: screen, database, file
log_writers[] = screen

; log level, everything logged w/ this level or one of greater severity
; will be logged. everything else will be ignored. possible values are:
; ERROR, WARN, INFO, DEBUG
log_level = WARN

; if configured to log in a file, log entries will be made to this file
logger_file_path = tmp/logs/piwik.log

[Cache]
; available backends are 'file', 'array', 'null', 'redis', 'chained'
; 'array' will cache data only during one request
; 'null' will not cache anything at all
; 'file' will cache on the filesystem
; 'redis' will cache on a Redis server, use this if you are running Piwik with multiple servers. Further configuration in [RedisCache] is needed
; 'chained' will chain multiple cache backends. Further configuration in [ChainedCache] is needed
backend = chained

[ChainedCache]
; The chained cache will always try to read from the fastest backend first (the first listed one) to avoid requesting
; the same cache entry from the slowest backend multiple times in one request.
backends[] = array
backends[] = file

[RedisCache]
; Redis server configuration.
host = "127.0.0.1"
port = 6379
; instead of host and port a unix socket path can be configured
unix_socket = ""
timeout = 0.0
password = ""
database = 14
; In case you are using queued tracking: Make sure to configure a different database! Otherwise queued requests might
; be flushed

[Debug]
; if set to 1, the archiving process will always be triggered, even if the archive has already been computed
; this is useful when making changes to the archiving code so we can force the archiving process
always_archive_data_period = 0;
always_archive_data_day = 0;
; Force archiving Custom date range (without re-archiving sub-periods used to process this date range)
always_archive_data_range = 0;

; if set to 1, all the SQL queries will be recorded by the profiler
; and a profiling summary will be printed at the end of the request
; NOTE: you must also set [log] log_writers[] = "screen" to enable the profiler to print on screen
enable_sql_profiler = 0

; If set to 1, all requests to piwik.php will be forced to be 'new visitors'
tracker_always_new_visitor = 0

; if set to 1, all SQL queries will be logged using the DEBUG log level
log_sql_queries = 0

[DebugTests]
; When set to 1, standalone plugins (those with their own git repositories)
; will be loaded when executing tests.
enable_load_standalone_plugins_during_tests = 0

[Development]
; Enables the development mode where we avoid most caching to make sure code changes will be directly applied as
; some caches are only invalidated after an update otherwise. When enabled it'll also performs some validation checks.
; For instance if you register a method in a widget we will verify whether the method actually exists and is public.
; If not, we will show you a helpful warning to make it easy to find simple typos etc.
enabled = 0

; if set to 1, javascript files will be included individually and neither merged nor minified.
; this option must be set to 1 when adding, removing or modifying javascript files
; Note that for quick debugging, instead of using below setting, you can add `&disable_merged_assets=1` to the Piwik URL
disable_merged_assets = 0

[General]

; the following settings control whether Unique Visitors `nb_uniq_visitors` and Unique users `nb_users` will be processed for different period types.
; year and range periods are disabled by default, to ensure optimal performance for high traffic Piwik instances
; if you set it to 1 and want the Unique Visitors to be re-processed for reports in the past, drop all piwik_archive_* tables
; it is recommended to always enable Unique Visitors and Unique Users processing for 'day' periods
enable_processing_unique_visitors_day = 1
enable_processing_unique_visitors_week = 1
enable_processing_unique_visitors_month = 1
enable_processing_unique_visitors_year = 0
enable_processing_unique_visitors_range = 0

; controls whether Unique Visitors will be processed for groups of websites. these metrics describe the number
; of unique visitors across the entire set of websites, so if a visitor visited two websites in the group, she
; would still only be counted as one. only relevant when using plugins that group sites together
enable_processing_unique_visitors_multiple_sites = 0

; The list of periods that are available in the Piwik calendar
; Example use case: custom date range requests are processed in real time,
; so they may take a few minutes on very high traffic website: you may remove "range" below to disable this period
enabled_periods_UI = "day,week,month,year,range"
enabled_periods_API = "day,week,month,year,range"

; whether to enable subquery cache for Custom Segment archiving queries
enable_segments_subquery_cache = 0
; Any segment subquery that matches more than segments_subquery_cache_limit IDs will not be cached,
; and the original subquery executed instead.
segments_subquery_cache_limit  = 100000
; TTL: Time to live for cache files, in seconds. Default to 60 minutes
segments_subquery_cache_ttl  = 3600

; when set to 1, all requests to Piwik will return a maintenance message without connecting to the DB
; this is useful when upgrading using the shell command, to prevent other users from accessing the UI while Upgrade is in progress
maintenance_mode = 0

; Defines the release channel that shall be used. Currently available values are:
; "latest_stable", "latest_beta", "latest_2x_stable", "latest_2x_beta"
release_channel = "latest_stable"

; character used to automatically create categories in the Actions > Pages, Outlinks and Downloads reports
; for example a URL like "example.com/blog/development/first-post" will create
; the page first-post in the subcategory development which belongs to the blog category
action_url_category_delimiter = /

; similar to above, but this delimiter is only used for page titles in the Actions > Page titles report
action_title_category_delimiter = /

; the maximum url category depth to track. if this is set to 2, then a url such as
; "example.com/blog/development/first-post" would be treated as "example.com/blog/development".
; this setting is used mainly to limit the amount of data that is stored by Piwik.
action_category_level_limit = 10

; minimum number of websites to run autocompleter
autocomplete_min_sites = 5

; maximum number of websites showed in search results in autocompleter
site_selector_max_sites = 15

; if set to 1, shows sparklines (evolution graph) in 'All Websites' report (MultiSites plugin)
show_multisites_sparklines = 1

; number of websites to display per page in the All Websites dashboard
all_websites_website_per_page = 50

; if set to 0, the anonymous user will not be able to use the 'segments' parameter in the API request
; this is useful to prevent full DB access to the anonymous user, or to limit performance usage
anonymous_user_enable_use_segments_API = 1

; if browser trigger archiving is disabled, API requests with a &segment= parameter will still trigger archiving.
; You can force the browser archiving to be disabled in most cases by setting this setting to 1
; The only time that the browser will still trigger archiving is when requesting a custom date range that is not pre-processed yet
browser_archiving_disabled_enforce = 0

; By default, users can create Segments which are to be processed in Real-time.
; Setting this to 0 will force all newly created Custom Segments to be "Pre-processed (faster, requires archive.php cron)"
; This can be useful if you want to prevent users from adding much load on the server.
; Notes:
;  * any existing Segment set to "processed in Real time", will still be set to Real-time.
;    this will only affect custom segments added or modified after this setting is changed.
;  * when set to 0 then any user with at least 'view' access will be able to create pre-processed segments.
enable_create_realtime_segments = 1

; Whether to enable the "Suggest values for segment" in the Segment Editor panel.
; Set this to 0 in case your Piwik database is very big, and suggested values may not appear in time
enable_segment_suggested_values = 1

; By default, any user with a "view" access for a website can create segment assigned to this website.
; Set this to "admin" or "superuser" to require that users should have at least this access to create new segments.
; Note: anonymous user (even if it has view access) is not allowed to create or edit segment.
; Possible values are "view", "admin", "superuser"
adding_segment_requires_access = "view"

; Whether it is allowed for users to add segments that affect all websites or not. If there are many websites
; this admin option can be used to prevent users from performing an action that will have a major impact
; on Piwik performance.
allow_adding_segments_for_all_websites = 1

; When archiving segments for the first time, this determines the oldest date that will be archived.
; This option can be used to avoid archiving (for isntance) the lastN years for every new segment.
; Valid option values include: "beginning_of_time" (start date of archiving will not be changed)
;                              "segment_last_edit_time" (start date of archiving will be the earliest last edit date found,
;                                                        if none is found, the created date is used)
;                              "segment_creation_time" (start date of archiving will be the creation date of the segment)
;                              lastN where N is an integer (eg "last10" to archive for 10 days before the segment creation date)
process_new_segments_from = "beginning_of_time"

; this action name is used when the URL ends with a slash /
; it is useful to have an actual string to write in the UI
action_default_name = index

; default language to use in Piwik
default_language = en

; default number of elements in the datatable
datatable_default_limit = 10

; Each datatable report has a Row Limit selector at the bottom right.
; By default you can select from 5 to 500 rows. You may customise the values below
; -1 will be displayed as 'all' and it will export all rows (filter_limit=-1)
datatable_row_limits = "5,10,25,50,100,250,500,-1"

; default number of rows returned in API responses
; this value is overwritten by the '# Rows to display' selector.
; if set to -1, a click on 'Export as' will export all rows independently of the current '# Rows to display'.
API_datatable_default_limit = 100

; When period=range, below the datatables, when user clicks on "export", the data will be aggregate of the range.
; Here you can specify the comma separated list of formats for which the data will be exported aggregated by day
; (ie. there will be a new "date" column). For example set to: "rss,tsv,csv"
datatable_export_range_as_day = "rss"

; This setting is overridden in the UI, under "User Settings".
; The date and period loaded by Piwik uses the defaults below. Possible values: yesterday, today.
default_day = yesterday
; Possible values: day, week, month, year.
default_period = day

; Time in seconds after which an archive will be computed again. This setting is used only for today's statistics.
; This setting is overriden in the UI, under "General Settings".
; This setting is only used if it hasn't been overriden via the UI yet, or if enable_general_settings_admin=0
time_before_today_archive_considered_outdated = 150

; Time in seconds after which an archive will be computed again. This setting is used only for week's statistics.
; If set to "-1" (default), it will fall back to the UI setting under "General settings" unless enable_general_settings_admin=0
; is set. In this case it will default to "time_before_today_archive_considered_outdated";
time_before_week_archive_considered_outdated = -1

; Same as config setting "time_before_week_archive_considered_outdated" but it is only applied to monthly archives
time_before_month_archive_considered_outdated = -1

; Same as config setting "time_before_week_archive_considered_outdated" but it is only applied to yearly archives
time_before_year_archive_considered_outdated = -1

; Same as config setting "time_before_week_archive_considered_outdated" but it is only applied to range archives
time_before_range_archive_considered_outdated = -1

; This setting is overriden in the UI, under "General Settings".
; The default value is to allow browsers to trigger the Piwik archiving process.
; This setting is only used if it hasn't been overridden via the UI yet, or if enable_general_settings_admin=0
enable_browser_archiving_triggering = 1

; By default, Piwik will force archiving of range periods from browser requests, even if enable_browser_archiving_triggering
; is set to 0. This can sometimes create too much of a demand on system resources. Setting this option to 0 and
; disabling browser trigger archiving will make sure ranges are not archived on browser request. Since the cron
; archiver does not archive any custom date ranges, you must either disable range (using enabled_periods_API and enabled_periods_UI)
; or make sure the date ranges users' want to see will be processed somehow.
archiving_range_force_on_browser_request = 1

; By default Piwik runs OPTIMIZE TABLE SQL queries to free spaces after deleting some data.
; If your Piwik tracks millions of pages, the OPTIMIZE TABLE queries might run for hours (seen in "SHOW FULL PROCESSLIST \g")
; so you can disable these special queries here:
enable_sql_optimize_queries = 1

; By default Piwik is purging complete date range archives to free spaces after deleting some data.
; If you are pre-processing custom ranges using CLI task to make them easily available in UI,
; you can prevent this action from happening by setting this parameter to value bigger than 1
purge_date_range_archives_after_X_days = 1

; MySQL minimum required version
; note: timezone support added in 4.1.3
minimum_mysql_version = 4.1

; PostgreSQL minimum required version
minimum_pgsql_version = 8.3

; Minimum advised memory limit in php.ini file (see memory_limit value)
; Set to "-1" to always use the configured memory_limit value in php.ini file.
minimum_memory_limit = 128

; Minimum memory limit enforced when archived via ./console core:archive
; Set to "-1" to always use the configured memory_limit value in php.ini file.
minimum_memory_limit_when_archiving = 1024

; Piwik will check that usernames and password have a minimum length, and will check that characters are "allowed"
; This can be disabled, if for example you wish to import an existing User database in Piwik and your rules are less restrictive
disable_checks_usernames_attributes = 0

; Piwik will use the configured hash algorithm where possible.
; For legacy data, fallback or non-security scenarios, we use md5.
hash_algorithm = whirlpool

; by default, Piwik uses PHP's built-in file-based session save handler with lock files.
; For clusters, use dbtable.
session_save_handler = files

; If set to 1, Piwik will automatically redirect all http:// requests to https://
; If SSL / https is not correctly configured on the server, this will break Piwik
; If you set this to 1, and your SSL configuration breaks later on, you can always edit this back to 0
; it is recommended for security reasons to always use Piwik over https
force_ssl = 0

; login cookie name
login_cookie_name = piwik_auth

; By default, the auth cookie is set only for the duration of session.
; if "Remember me" is checked, the auth cookie will be valid for 14 days by default
login_cookie_expire = 1209600

; The path on the server in which the cookie will be available on.
; Defaults to empty. See spec in https://curl.haxx.se/rfc/cookie_spec.html
login_cookie_path =

; email address that appears as a Sender in the password recovery email
; if specified, {DOMAIN} will be replaced by the current Piwik domain
login_password_recovery_email_address = "password-recovery@{DOMAIN}"
; name that appears as a Sender in the password recovery email
login_password_recovery_email_name = Piwik

; email address that appears as a Reply-to in the password recovery email
; if specified, {DOMAIN} will be replaced by the current Piwik domain
login_password_recovery_replyto_email_address = "no-reply@{DOMAIN}"
; name that appears as a Reply-to in the password recovery email
login_password_recovery_replyto_email_name = "No-reply"

; By default when user logs out they are redirected to Piwik "homepage" usually the Login form.
; Uncomment the next line to set a URL to redirect the user to after they log out of Piwik.
; login_logout_url = http://...

; Set to 1 to disable the framebuster on standard Non-widgets pages (a click-jacking countermeasure).
; Default is 0 (i.e., bust frames on all non Widget pages such as Login, API, Widgets, Email reports, etc.).
enable_framed_pages = 0

; Set to 1 to disable the framebuster on Admin pages (a click-jacking countermeasure).
; Default is 0 (i.e., bust frames on the Settings forms).
enable_framed_settings = 0

; language cookie name for session
language_cookie_name = piwik_lang

; standard email address displayed when sending emails
noreply_email_address = "noreply@{DOMAIN}"

; standard email name displayed when sending emails. If not set, a default name will be used.
noreply_email_name = ""

; feedback email address;
; when testing, use your own email address or "nobody"
feedback_email_address = "feedback@piwik.org"

; using to set reply_to in reports e-mail to login of report creator
scheduled_reports_replyto_is_user_email_and_alias = 0

; scheduled reports truncate limit
; the report will be rendered with the first 23 rows and will aggregate other rows in a summary row
; 23 rows table fits in one portrait page
scheduled_reports_truncate = 23

; during archiving, Piwik will limit the number of results recorded, for performance reasons
; maximum number of rows for any of the Referrers tables (keywords, search engines, campaigns, etc.)
datatable_archiving_maximum_rows_referrers = 1000
; maximum number of rows for any of the Referrers subtable (search engines by keyword, keyword by campaign, etc.)
datatable_archiving_maximum_rows_subtable_referrers = 50

; maximum number of rows for the Users report
datatable_archiving_maximum_rows_userid_users = 50000

; maximum number of rows for the Custom Variables names report
; Note: if the website is Ecommerce enabled, the two values below will be automatically set to 50000
datatable_archiving_maximum_rows_custom_variables = 1000
; maximum number of rows for the Custom Variables values reports
datatable_archiving_maximum_rows_subtable_custom_variables = 1000

; maximum number of rows for any of the Actions tables (pages, downloads, outlinks)
datatable_archiving_maximum_rows_actions = 500
; maximum number of rows for pages in categories (sub pages, when clicking on the + for a page category)
; note: should not exceed the display limit in Piwik\Actions\Controller::ACTIONS_REPORT_ROWS_DISPLAY
; because each subdirectory doesn't have paging at the bottom, so all data should be displayed if possible.
datatable_archiving_maximum_rows_subtable_actions = 100

; maximum number of rows for any of the Events tables (Categories, Actions, Names)
datatable_archiving_maximum_rows_events = 500
; maximum number of rows for sub-tables of the Events tables (eg. for the subtables Categories>Actions or Categories>Names).
datatable_archiving_maximum_rows_subtable_events = 100

; maximum number of rows for other tables (Providers, User settings configurations)
datatable_archiving_maximum_rows_standard = 500

; maximum number of rows to fetch from the database when archiving. if set to 0, no limit is used.
; this can be used to speed up the archiving process, but is only useful if you're site has a large
; amount of actions, referrers or custom variable name/value pairs.
archiving_ranking_query_row_limit = 50000

; maximum number of actions that is shown in the visitor log for each visitor
visitor_log_maximum_actions_per_visit = 500

; by default, the real time Live! widget will update every 5 seconds and refresh with new visits/actions/etc.
; you can change the timeout so the widget refreshes more often, or not as frequently
live_widget_refresh_after_seconds = 5

; by default, the Live! real time visitor count widget will check to see how many visitors your
; website received in the last 3 minutes. changing this value will change the number of minutes
; the widget looks in.
live_widget_visitor_count_last_minutes = 3

; In "All Websites" dashboard, when looking at today's reports (or a date range including today),
; the page will automatically refresh every 5 minutes. Set to 0 to disable automatic refresh
multisites_refresh_after_seconds = 300

; Set to 1 if you're using https on your Piwik server and Piwik can't detect it,
; e.g., a reverse proxy using https-to-http, or a web server that doesn't
; set the HTTPS environment variable.
assume_secure_protocol = 0

; Set to 1 if you're using more than one server for your Piwik installation. For example if you are using Piwik in a
; load balanced environment, if you have configured failover or if you're just using multiple servers in general.
; By enabling this flag we will for example not allow the installation of a plugin via the UI as a plugin would be only
; installed on one server or a config one change would be only made on one server instead of all servers.
multi_server_environment = 0

; List of proxy headers for client IP addresses
; Piwik will determine the user IP by extracting the first IP address found in this proxy header.
;
; CloudFlare (CF-Connecting-IP)
;proxy_client_headers[] = HTTP_CF_CONNECTING_IP
;
; ISP proxy (Client-IP)
;proxy_client_headers[] = HTTP_CLIENT_IP
;
; de facto standard (X-Forwarded-For)
;proxy_client_headers[] = HTTP_X_FORWARDED_FOR

; List of proxy headers for host IP addresses
;
; de facto standard (X-Forwarded-Host)
;proxy_host_headers[] = HTTP_X_FORWARDED_HOST

; List of proxy IP addresses (or IP address ranges) to skip (if present in the above headers).
; Generally, only required if there's more than one proxy between the visitor and the backend web server.
;
; Examples:
;proxy_ips[] = 204.93.240.*
;proxy_ips[] = 204.93.177.0/24
;proxy_ips[] = 199.27.128.0/21
;proxy_ips[] = 173.245.48.0/20

; Whether to enable trusted host checking. This can be disabled if you're running Piwik
; on several URLs and do not wish to constantly edit the trusted host list.
enable_trusted_host_check = 1

; List of trusted hosts (eg domain or subdomain names) when generating absolute URLs.
;
; Examples:
;trusted_hosts[] = example.com
;trusted_hosts[] = stats.example.com

; List of Cross-origin resource sharing domains (eg domain or subdomain names) when generating absolute URLs.
; Described here: https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
;
; Examples:
;cors_domains[] = http://example.com
;cors_domains[] = http://stats.example.com
;
; Or you may allow cross domain requests for all domains with:
;cors_domains[] = *

; If you use this Piwik instance over multiple hostnames, Piwik will need to know
; a unique instance_id for this instance, so that Piwik can serve the right custom logo and tmp/* assets,
; independently of the hostname Piwik is currently running under.
; instance_id = stats.example.com

; The API server is an essential part of the Piwik infrastructure/ecosystem to
; provide services to Piwik installations, e.g., getLatestVersion and
; subscribeNewsletter.
api_service_url = http://api.piwik.org

; When the ImageGraph plugin is activated, report metadata have an additional entry : 'imageGraphUrl'.
; This entry can be used to request a static graph for the requested report.
; When requesting report metadata with $period=range, Piwik needs to translate it to multiple periods for evolution graphs.
; eg. $period=range&date=previous10 becomes $period=day&date=previous10. Use this setting to override the $period value.
graphs_default_period_to_plot_when_period_range = day

; When the ImageGraph plugin is activated, enabling this option causes the image graphs to show the evolution
; within the selected period instead of the evolution across the last n periods.
graphs_show_evolution_within_selected_period = 0

; The Overlay plugin shows the Top X following pages, Top X downloads and Top X outlinks which followed
; a view of the current page. The value X can be set here.
overlay_following_pages_limit = 300

; With this option, you can disable the framed mode of the Overlay plugin. Use it if your website contains a framebuster.
overlay_disable_framed_mode = 0

; By default we check whether the Custom logo is writable or not, before we display the Custom logo file uploader
enable_custom_logo_check = 1

; If php is running in a chroot environment, when trying to import CSV files with createTableFromCSVFile(),
; Mysql will try to load the chrooted path (which is incomplete). To prevent an error, here you can specify the
; absolute path to the chroot environment. eg. '/path/to/piwik/chrooted/'
absolute_chroot_path =

; In some rare cases it may be useful to explicitely tell Piwik not to use LOAD DATA INFILE
; This may for example be useful when doing Mysql AWS replication
enable_load_data_infile = 1

; By setting this option to 0:
; - links to Enable/Disable/Uninstall plugins will be hidden and disabled
; - links to Uninstall themes will be disabled (but user can still enable/disable themes)
enable_plugins_admin = 1

; By setting this option to 1, it will be possible for Super Users to upload Piwik plugin ZIP archives directly in Piwik Administration.
; Enabling this opens a remote code execution vulnerability where
; an attacker who gained Super User access could execute custom PHP code in a Piwik plugin.
enable_plugin_upload = 0

; By setting this option to 0 (e.g. in common.config.ini.php) the installer will be disabled.
enable_installer = 1

; By setting this option to 0, you can prevent Super User from editing the Geolocation settings.
enable_geolocation_admin = 1

; By setting this option to 0, the old log data and old report data features will be hidden from the UI
; Note: log purging and old data purging still occurs, just the Super User cannot change the settings.
enable_delete_old_data_settings_admin = 1

; By setting this option to 0, the following settings will be hidden and disabled from being set in the UI:
; - "Archiving Settings"
; - "Update settings"
; - "Email server settings"
enable_general_settings_admin = 1

; Disabling this will disable features like automatic updates for Piwik,
; its plugins and components like the GeoIP database, referrer spam blacklist or search engines and social network definitions
enable_internet_features = 1

; By setting this option to 0, it will disable the "Auto update" feature
enable_auto_update = 1

; By setting this option to 0, no emails will be sent in case of an available core.
; If set to 0 it also disables the "sent plugin update emails" feature in general and the related setting in the UI.
enable_update_communication = 1



; Comma separated list of plugin names for which console commands should be loaded (applies when Piwik is not installed yet)
always_load_commands_from_plugin=

; This controls whether the pivotBy query parameter can be used with any dimension or just subtable
; dimensions. If set to 1, it will fetch a report with a segment for each row of the table being pivoted.
; At present, this is very inefficient, so it is disabled by default.
pivot_by_filter_enable_fetch_by_segment = 0

; This controls the default maximum number of columns to display in a pivot table. Since a pivot table displays
; a table's rows as columns, the number of columns can become very large, which will affect webpage layouts.
; Set to -1 to specify no limit. Note: The pivotByColumnLimit query parameter can be used to override this default
; on a per-request basis;
pivot_by_filter_default_column_limit = 10

; If set to 0 it will disable advertisements for providers of Professional Support for Piwik.
piwik_professional_support_ads_enabled = 1

[Tracker]

; Piwik uses "Privacy by default" model. When one of your users visit multiple of your websites tracked in this Piwik,
; Piwik will create for this user a fingerprint that will be different across the multiple websites.
; If you want to track unique users across websites (for example when using the InterSites plugin) you may set this setting to 1.
; Note: setting this to 0 increases your users' privacy.
enable_fingerprinting_across_websites = 0

; Piwik uses first party cookies by default. If set to 1,
; the visit ID cookie will be set on the Piwik server domain as well
; this is useful when you want to do cross websites analysis
use_third_party_id_cookie = 0

; If tracking does not work for you or you are stuck finding an issue, you might want to enable the tracker debug mode.
; Once enabled (set to 1) messages will be logged to all loggers defined in "[log] log_writers" config.
debug = 0

; This option is an alternative to the debug option above. When set to 1, you can debug tracker request by adding
; a debug=1 query parameter in the URL. All other HTTP requests will not have debug enabled. For security reasons this
; option should be only enabled if really needed and only for a short time frame. Otherwise anyone can set debug=1 and
; see the log output as well.
debug_on_demand = 0

; This setting is described in this FAQ: https://piwik.org/faq/how-to/faq_175/
; Note: generally this should only be set to 1 in an intranet setting, where most users have the same configuration (browsers, OS)
; and the same IP. If left to 0 in this setting, all visitors will be counted as one single visitor.
trust_visitors_cookies = 0

; name of the cookie used to store the visitor information
; This is used only if use_third_party_id_cookie = 1
cookie_name = _pk_uid

; by default, the Piwik tracking cookie expires in 13 months (365 + 28 days)
; This is used only if use_third_party_id_cookie = 1
cookie_expire = 33955200;

; The path on the server in which the cookie will be available on.
; Defaults to empty. See spec in https://curl.haxx.se/rfc/cookie_spec.html
; This is used for the Ignore cookie, and the third party cookie if use_third_party_id_cookie = 1
cookie_path =

; set to 0 if you want to stop tracking the visitors. Useful if you need to stop all the connections on the DB.
record_statistics = 1

; length of a visit in seconds. If a visitor comes back on the website visit_standard_length seconds
; after their last page view, it will be recorded as a new visit. In case you are using the Piwik JavaScript tracker to
; calculate the visit count correctly, make sure to call the method "setSessionCookieTimeout" eg
; `_paq.push(['setSessionCookieTimeout', timeoutInSeconds=1800])`
visit_standard_length = 1800

; The window to look back for a previous visit by this current visitor. Defaults to visit_standard_length.
; If you are looking for higher accuracy of "returning visitors" metrics, you may set this value to 86400 or more.
; This is especially useful when you use the Tracking API where tracking Returning Visitors often depends on this setting.
; The value window_look_back_for_visitor is used only if it is set to greater than visit_standard_length
window_look_back_for_visitor = 0

; visitors that stay on the website and view only one page will be considered as time on site of 0 second
default_time_one_page_visit = 0

; Comma separated list of URL query string variable names that will be removed from your tracked URLs
; By default, Piwik will remove the most common parameters which are known to change often (eg. session ID parameters)
url_query_parameter_to_exclude_from_url = "gclid,fb_xd_fragment,fb_comment_id,phpsessid,jsessionid,sessionid,aspsessionid,doing_wp_cron,sid,pk_vid"

; if set to 1, Piwik attempts a "best guess" at the visitor's country of
; origin when the preferred language tag omits region information.
; The mapping is defined in core/DataFiles/LanguageToCountry.php,
enable_language_to_country_guess = 1

; When the `./console core:archive` cron hasn't been setup, we still need to regularly run some maintenance tasks.
; Visits to the Tracker will try to trigger Scheduled Tasks (eg. scheduled PDF/HTML reports by email).
; Scheduled tasks will only run if 'Enable Piwik Archiving from Browser' is enabled in the General Settings.
; Tasks run once every hour maximum, they might not run every hour if traffic is low.
; Set to 0 to disable Scheduled tasks completely.
scheduled_tasks_min_interval = 3600

; name of the cookie to ignore visits
ignore_visits_cookie_name = piwik_ignore

; Comma separated list of variable names that will be read to define a Campaign name, for example CPC campaign
; Example: If a visitor first visits 'index.php?piwik_campaign=Adwords-CPC' then it will be counted as a campaign referrer named 'Adwords-CPC'
; Includes by default the GA style campaign parameters
campaign_var_name = "pk_cpn,pk_campaign,piwik_campaign,utm_campaign,utm_source,utm_medium"

; Comma separated list of variable names that will be read to track a Campaign Keyword
; Example: If a visitor first visits 'index.php?piwik_campaign=Adwords-CPC&piwik_kwd=My killer keyword' ;
; then it will be counted as a campaign referrer named 'Adwords-CPC' with the keyword 'My killer keyword'
; Includes by default the GA style campaign keyword parameter utm_term
campaign_keyword_var_name = "pk_kwd,pk_keyword,piwik_kwd,utm_term"

; if set to 1, actions that contain different campaign information from the visitor's ongoing visit will
; be treated as the start of a new visit. This will include situations when campaign information was absent before,
; but is present now.
create_new_visit_when_campaign_changes = 1

; if set to 1, actions that contain different website referrer information from the visitor's ongoing visit
; will be treated as the start of a new visit. This will include situations when website referrer information was
; absent before, but is present now.
create_new_visit_when_website_referrer_changes = 0

; ONLY CHANGE THIS VALUE WHEN YOU DO NOT USE PIWIK ARCHIVING, SINCE THIS COULD CAUSE PARTIALLY MISSING ARCHIVE DATA
; Whether to force a new visit at midnight for every visitor. Default 1.
create_new_visit_after_midnight = 1

; maximum length of a Page Title or a Page URL recorded in the log_action.name table
page_maximum_length = 1024;

; Tracker cache files are the simple caching layer for Tracking.
; TTL: Time to live for cache files, in seconds. Default to 5 minutes.
tracker_cache_file_ttl = 300

; Whether Bulk tracking requests to the Tracking API requires the token_auth to be set.
bulk_requests_require_authentication = 0

; Whether Bulk tracking requests will be wrapped within a DB Transaction.
; This greatly increases performance of Log Analytics and in general any Bulk Tracking API requests.
bulk_requests_use_transaction = 1

; DO NOT USE THIS SETTING ON PUBLICLY AVAILABLE PIWIK SERVER
; !!! Security risk: if set to 0, it would allow anyone to push data to Piwik with custom dates in the past/future and even with fake IPs!
; When using the Tracking API, to override either the datetime and/or the visitor IP,
; token_auth with an "admin" access is required. If you set this setting to 0, the token_auth will not be required anymore.
; DO NOT USE THIS SETTING ON PUBLIC PIWIK SERVERS
tracking_requests_require_authentication = 1

; By default, Piwik accepts only tracking requests for up to 1 day in the past. For tracking requests with a custom date
; date is older than 1 day, Piwik requires an authenticated tracking requests. By setting this config to another value
; You can change how far back Piwik will track your requests without authentication. The configured value is in seconds.
tracking_requests_require_authentication_when_custom_timestamp_newer_than = 86400;

[Segments]
; Reports with segmentation in API requests are processed in real time.
; On high traffic websites it is recommended to pre-process the data
; so that the analytics reports are always fast to load.
; You can define below the list of Segments strings
; for which all reports should be Archived during the cron execution
; All segment values MUST be URL encoded.
;Segments[]="visitorType==new"
;Segments[]="visitorType==returning,visitorType==returningCustomer"

; If you define Custom Variables for your visitor, for example set the visit type
;Segments[]="customVariableName1==VisitType;customVariableValue1==Customer"

[Deletelogs]
; delete_logs_enable - enable (1) or disable (0) delete log feature. Make sure that all archives for the given period have been processed (setup a cronjob!),
; otherwise you may lose tracking data.
; delete_logs_schedule_lowest_interval - lowest possible interval between two table deletes (in days, 1|7|30). Default: 7.
; delete_logs_older_than - delete data older than XX (days). Default: 180
delete_logs_enable = 0
delete_logs_schedule_lowest_interval = 7
delete_logs_older_than = 180
delete_logs_max_rows_per_query = 100000
enable_auto_database_size_estimate = 1

[Deletereports]
delete_reports_enable                = 0
delete_reports_older_than            = 12
delete_reports_keep_basic_metrics    = 1
delete_reports_keep_day_reports      = 0
delete_reports_keep_week_reports     = 0
delete_reports_keep_month_reports    = 1
delete_reports_keep_year_reports     = 1
delete_reports_keep_range_reports    = 0
delete_reports_keep_segment_reports  = 0

[mail]
defaultHostnameIfEmpty = defaultHostnameIfEmpty.example.org ; default Email @hostname, if current host can't be read from system variables
transport = ; smtp (using the configuration below) or empty (using built-in mail() function)
port = ; optional; defaults to 25 when security is none or tls; 465 for ssl
host = ; SMTP server address
type = ; SMTP Auth type. By default: NONE. For example: LOGIN
username = ; SMTP username
password = ; SMTP password
encryption = ; SMTP transport-layer encryption, either 'ssl', 'tls', or empty (i.e., none).

[proxy]
type = BASIC ; proxy type for outbound/outgoing connections; currently, only BASIC is supported
host = ; Proxy host: the host name of your proxy server (mandatory)
port = ; Proxy port: the port that the proxy server listens to. There is no standard default, but 80, 1080, 3128, and 8080 are popular
username = ; Proxy username: optional; if specified, password is mandatory
password = ; Proxy password: optional; if specified, username is mandatory

[Plugins]
; list of plugins (in order they will be loaded) that are activated by default in the Piwik platform
Plugins[] = CorePluginsAdmin
Plugins[] = CoreAdminHome
Plugins[] = CoreHome
Plugins[] = WebsiteMeasurable
Plugins[] = Diagnostics
Plugins[] = CoreVisualizations
Plugins[] = Proxy
Plugins[] = API
Plugins[] = ExamplePlugin
Plugins[] = Widgetize
Plugins[] = Transitions
Plugins[] = LanguagesManager
Plugins[] = Actions
Plugins[] = Dashboard
Plugins[] = MultiSites
Plugins[] = Referrers
Plugins[] = UserLanguage
Plugins[] = DevicesDetection
Plugins[] = Goals
Plugins[] = Ecommerce
Plugins[] = SEO
Plugins[] = Events
Plugins[] = UserCountry
Plugins[] = VisitsSummary
Plugins[] = VisitFrequency
Plugins[] = VisitTime
Plugins[] = VisitorInterest
Plugins[] = ExampleAPI
Plugins[] = RssWidget
Plugins[] = Feedback
Plugins[] = Monolog

Plugins[] = Login
Plugins[] = UsersManager
Plugins[] = SitesManager
Plugins[] = Installation
Plugins[] = CoreUpdater
Plugins[] = CoreConsole
Plugins[] = ScheduledReports
Plugins[] = UserCountryMap
Plugins[] = Live
Plugins[] = CustomVariables
Plugins[] = PrivacyManager
Plugins[] = ImageGraph
Plugins[] = Annotations
Plugins[] = MobileMessaging
Plugins[] = Overlay
Plugins[] = SegmentEditor
Plugins[] = Insights
Plugins[] = Morpheus
Plugins[] = Contents
Plugins[] = BulkTracking
Plugins[] = Resolution
Plugins[] = DevicePlugins
Plugins[] = Heartbeat
Plugins[] = Intl
Plugins[] = Marketplace
Plugins[] = ProfessionalServices
Plugins[] = UserId
Plugins[] = CustomPiwikJs

[PluginsInstalled]
PluginsInstalled[] = Diagnostics
PluginsInstalled[] = Login
PluginsInstalled[] = CoreAdminHome
PluginsInstalled[] = UsersManager
PluginsInstalled[] = SitesManager
PluginsInstalled[] = Installation
PluginsInstalled[] = Monolog
PluginsInstalled[] = Intl

[APISettings]
; Any key/value pair can be added in this section, they will be available via the REST call
; index.php?module=API&method=API.getSettings
; This can be used to expose values from Piwik, to control for example a Mobile app tracking
SDK_batch_size = 10
SDK_interval_value = 30

; NOTE: do not directly edit this file! See notice at the top