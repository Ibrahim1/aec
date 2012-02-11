AEC - Account Control Expiration - Membership Manager
copyright 2006-2012 Copyright (C) David Deutsch
author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version

This package contains Free Software. Please read about Free Software here: http://www.fsf.org/about/what-is-free-software

If you want to deploy this in a Joomla 1.5.x CMS, please install (in this order):

com_acctexp_*.zip
plugin_aecaccess_*.zip
plugin_aecerror_*.zip
plugin_aecrouting_*.zip
plugin_aecuser_*.zip
mod_acctexp_*.zip

The individual packages are:

com_acctexp_*.zip - The AccountExpirationControl Component

This is the AEC Component. Please always install this first.

The Component in itself will not restrict access to content or to the system, but (for example) give you options to modify user accounts to have the system grant different access rights.

mod_acctexp_*.zip - The AEC Module

This is the AEC Module. It lets you display the individual account expiration date to your users. It is also required for certain Micro Integrations which can display Javascript code or other extra information in the Module and thus, for example, display affiliate tracking code and similar information.

aecaccess_plugin_*.zip - AECaccess Plugin - Access Manager

This Plugin manages the Joomla login based on AEC access status. This plugin does not in itself restrict access to content.

aecerror_plugin_*.zip -  AECerror Plugin - Error Manager

This Plugin handles error behavior of your Joomla CMS. For example, instead of redirecting your users to a standard "you are not allowed to access this page, please log in", you can, with this plugin, forward them to a special AEC NotAllowed page, which explains the Membership system on your site.

aecrouting_plugin_*.zip - AECrouting Plugin - Routing Manager

A general purpose router for AEC related events such as registration (where, according to your settings, the AEC might redirect to the plan selection screen first).

aecuser_plugin_*.zip - AECuser Plugin - User Manager

Certain functions within the AEC need to intercept user registrations to store extra data and propagate changes to a user account to internal functions. This is why the AECuser Plugin is required for most systems.
