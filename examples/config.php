<?php
/**
 * Copy this file to config.php to load the default application and set the 
 * following values to the proper values
 * 
 * Obtained in Afosto, used for OAUTH2.0
 * Redirect uri is the url to redirect to after access has been approved by
 * the Afosto user
 */
define('CLIENT_ID', '');
define('CLIENT_SECRET', '');
define('REDIRECT_URI', '');

/**
 * Used in Whoops (error handler), and is optional. Supported editors are:
 * - sublime
 * - emacs
 * - textmate
 * - macvim
 * - xdebug
 * - phpstorm
 */
define('EDITOR', null);

/**
 * Prefix for incomming webhooks (http://...), applied when using the
 * webhook getLink method
 */
define('WEBHOOK_BASE', '');

/**
 * Protect incomming webhooks with a user specified key that is given along
 * in the url
 */
define('WEBHOOK_KEY', '');

