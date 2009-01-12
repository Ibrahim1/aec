AEC - Account Control Expiration - Membership Manager
copyright 2006-2008 Copyright (C) David Deutsch
author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version

This package contains Free Software. The contents are (alphabetical):

aecaccess_plugin_0_4.zip
aecerror_plugin_0_1.zip
aechacks_mambot_0_1.zip
aecuser_plugin_0_3.zip
com_acctexp_0_12_6RC2o.zip
mod_acctexp_0_12_4_6.zip

If you want to deploy this in a Joomla 1.0.x CMS, please install (in this order):

com_acctexp_0_12_6RC2o.zip
aechacks_mambot_0_1.zip
mod_acctexp_0_12_4_6.zip

If you want to deploy this in a Joomla 1.5.x CMS, please install (in this order):

ALWAYS order the joomla legacy plugin BEFORE all the AEC plugins!!!

com_acctexp_0_12_6RC2o.zip
aecaccess_plugin_0_4.zip
aecerror_plugin_0_1.zip
aecrouting_plugin_0_1.zip
aecuser_plugin_0_3.zip
mod_acctexp_0_12_4_6.zip


The individual packages are:

com_acctexp_0_12_6RC2o.zip - The AccountExpirationControl Component

This is the AEC Component. Please always install this first. The Component in itself will also not restrict access to content or to the system, but (for example) give you options to modify user accounts to have the system grant different access rights.

mod_acctexp_0_12_4_6.zip - The AEC Module

This is the AEC Module. It lets you display the individual account expiration date to your users. It is also required for certain Micro Integrations which can display Javascript code or other extra information in the Module and thus, for example, display affiliate tracking code and similar information.

aecaccess_plugin_0_4.zip - AECaccess Plugin - Access Manager

This Plugin manages the Joomla login. Please deactivate all other plugins of the group "access". You are able to add in functionality of other plugins by activating integration in the "Authentication" tab of your AEC Settings. This plugin does not in itself restrict access to content.

aecerror_plugin_0_1.zip -  AECerror Plugin - Error Manager

This Plugin handles error behavior of your Joomla CMS. For example, instead of redirecting your users to a standard "you are not allowed to access this page, please log in", you can, with this plugin, forward them to a special AEC NotAllowed page, which explains the Membership system on your site.

aecrouting_plugin_0_1.zip - AECrouting Plugin - Routing Manager

A general purpose router for AEC related events such as registration (where, according to your settings, the AEC might redirect to the plan selection screen first).

aechacks_mambot_0_1.zip - AEChacks Mambot - System Hacks Emulator

While previously, the AEC relied on hacking (editing) core Joomla files, this mambot emulates this behavior by intercepting certain calls and redirecting them to AEC functions. It may still be necessary to hack certain files, but the majority of systems will have a complete integration with this Mambot alone.

aecuser_plugin_0_3.zip - AECuser Plugin - User Manager

Certain functions within the AEC need to intercept user registrations to store extra data and propagate changes to a user account to internal functions. This is why the AECuser Plugin is required for most systems.
