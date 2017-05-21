### Editing pages content ###

Please edit files in views/ dir. Do not change files names.

### Editing SEO data ###

SEO configuration for video is placed in views/index.html file line 261.
SEO configuration is placed in app/config.php file line 32.

### Changing CSS and JS files ###

All static resources are available in the src/ directory.
Use the Gulp default task to trigger Browser Sync and Watch on JS, CSS and HTML/PHP files.

Wrapper for all HTML is in main.php file.

### HTTPS redirection on dev/local enviroment ###

To disable HTTPS rediretion create file `dev_enviroment` in website root directory.