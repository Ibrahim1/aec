<?php
/**
 * @version $Id: mi_vbulletin.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - vBulletin
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_vbulletin
{

	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_VBULLETIN;
		$info['desc'] = _AEC_MI_DESC_VBULLETIN;

		return $info;
	}

	function checkInstallation()
	{
		$database = &JFactory::getDBO();

		$conf =& JFactory::getConfig();

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array( $conf->getValue('config.dbprefix') .'_acctexp_mi_vbulletinpw', $tables );
	}

	function install()
	{
		$database = &JFactory::getDBO();

		$query = 'CREATE TABLE IF NOT EXISTS `#__acctexp_mi_vbulletinpw`'
		. ' (`id` int(11) NOT NULL auto_increment,'
		. '`userid` int(11) NOT NULL,'
		. '`vbulletinpw` varchar(255) NOT NULL default \'1\','
		. '`vbulletinsalt` varchar(255) NOT NULL default \'1\','
		. ' PRIMARY KEY (`id`)'
		. ')'
		;
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings()
	{
		$database = $this->getDB();

		$vbdb = $this->getDB();

		if ( !empty( $this->settings['table_prefix'] ) ) {
			$prefix = $this->settings['table_prefix'];
		} else {
			$prefix = 'vb_';
		}

		$query = 'SELECT `usergroupid`, `title`'
			 	. ' FROM ' . $prefix . 'usergroup'
			 	;
	 	$database->setQuery( $query );
	 	$groups = $database->loadObjectList();

		$sg		= array();
		$sg2	= array();
		if ( !empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$sg[] = mosHTML::makeOption( $group->usergroupid, $group->title );
			}
		}

         // Explode the Groups to Exclude
         if ( !empty($this->settings['groups_exclude'] ) ) {
     		$selected_groups_exclude = array();

     		foreach ( $this->settings['groups_exclude'] as $group_exclude ) {
     			$selected_groups_exclude[]->value = $group_exclude;
     		}
     	} else {
     		$selected_groups_exclude			= '';
     	}

		$settings = array();

		$settings['use_altdb']		= array( 'list_yesno' );
		$settings['dbms']			= array( 'inputC' );
		$settings['dbhost']			= array( 'inputC' );
		$settings['dbuser']			= array( 'inputC' );
		$settings['dbpasswd']		= array( 'inputC' );
		$settings['dbname']			= array( 'inputC' );
		$settings['table_prefix']	= array( 'inputC' );

		$s = array( 'group', 'group_exp', 'groups_exclude', 'remove_group', 'remove_group_exp' );

		foreach ( $s as $si ) {
			$v = null;
			if ( isset( $this->settings[$si] ) ) {
				$v = $this->settings[$si];
			}

			$settings['lists'][$si]	= mosHTML::selectList( $sg, $si.'[]', 'size="10" multiple="true"', 'value', 'text', $v );
		}

		$settings['set_group']				= array( 'list_yesno' );
		$settings['group']					= array( 'list' );
		$settings['set_group_exp']			= array( 'list_yesno' );
		$settings['group_exp']				= array( 'list' );
		$settings['rebuild']				= array( 'list_yesno' );
		$settings['remove']					= array( 'list_yesno' );

		$settings['create_user']			= array( 'list_yesno' );

		$userfields = $this->getUserFields( $vbdb );

		if ( !empty( $userfields ) ) {
			foreach ( $userfields as $key ) {
				$ndesc = _MI_MI_VBULLETIN_CREATE_FIELD . ": " . $key;

				$settings['create_user_'.$key]	= array( 'inputC', $ndesc, $ndesc );
			}

			$settings['update_user']			= array( 'list_yesno' );

			foreach ( $userfields as $key ) {
				$ndesc = _MI_MI_VBULLETIN_UPDATE_FIELD . ": " . $key;

				$settings['update_user_'.$key]	= array( 'inputC', $ndesc, $ndesc );
			}

			$settings['update_user_exp']		= array( 'list_yesno' );

			foreach ( $userfields as $key ) {
				$ndesc = _MI_MI_VBULLETIN_UPDATE_FIELD_EXP . ": " . $key;

				$settings['update_user_exp_'.$key]	= array( 'inputC', $ndesc, $ndesc );
			}
		}

		return $settings;
	}

	function Defaults()
	{
		$settings = array();

		$settings['use_altdb']		= 0;
		$settings['dbms']			= 'mysqli';
		$settings['dbhost']			= 'localhost';
		$settings['dbuser']			= '';
		$settings['dbpasswd']		= '';
		$settings['dbname']			= '';
		$settings['table_prefix']	= 'vb_';

		return $settings;
	}

	function action( $request )
	{
		global $mainframe;

		$database = &JFactory::getDBO();

		$vbdb = $this->getDB();

		$vbUserId = $this->vbUserid( $vbdb, $request->metaUser->cmsUser->email );

		if ( empty( $vbUserId ) && empty( $this->settings['create_user'] ) ) {
			return null;
		} elseif ( empty( $vbUserId ) ) {
			$vbulletinpw = new vbulletinpw( $database );
			$vbulletinpw->loadUserID( $request->metaUser->userid );

			$password = $vbulletinpw->vbulletinpw;

			$fields = $this->getUserFields( $vbdb );

			$content = array();
			if ( !empty( $fields ) ) {
				foreach ( $fields as $key ) {
					if ( !empty( $this->settings['create_user_'.$key] ) ) {
						$content[$key] = $this->settings['create_user_'.$key];
					}
				}
			}

			$content['joindate']		= ( time() + ( $mainframe->getCfg( 'offset' ) * 3600 ) );
			$content['passworddate']	= date( 'Y-m-d', ( time() + ( $mainframe->getCfg( 'offset' ) * 3600 ) ) );
			$content['usertitle']		= 'Junior Member';

			if ( empty( $content['username'] ) ) {
				$content['username']	= $request->metaUser->cmsUser->username;
			}

			$content['password']		= $vbulletinpw->vbulletinpw;
			$content['salt']			= $vbulletinpw->vbulletinsalt;
			$content['email']			= $request->metaUser->cmsUser->email;

			$this->createUser( $vbdb, $content );

			$vbUserId = $this->vbUserid( $vbdb, $request->metaUser->cmsUser->email );
		} elseif ( $this->settings['update_user'] ) {
			$fields = $this->getUserFields( $vbdb );

			$content = array();
			foreach ( $fields as $key ) {
				if ( !empty( $this->settings['update_user_'.$key] ) ) {
					$content[$key] = $this->settings['update_user_'.$key];
				}
			}

			$this->updateUser( $vbdb, $content );
		}

		if ( $vbUserId ) {
			$groups = $this->userGroup( $vbdb, $vbUserId );

			if ( $this->settings['set_group'] ) {
				foreach ( $this->settings['group'] as $groupid ) {
					if ( !empty( $groups ) ) {
						if ( in_array( $groupid, $groups ) ) {
							continue;
						}
					}

					$this->assignGroup( $vbdb, $vbUserId, $groupid );
				}
			}
		}

		return true;
	}

	function expiration_action( $request )
	{
		$vbdb = $this->getDB();

		$vbUserId = $this->vbUserid( $vbdb, $request->metaUser->cmsUser->email );

		if ( empty( $vbUserId ) && empty( $this->settings['create_user'] ) ) {
			return null;
		}

		if ( $this->settings['update_user_exp'] ) {
			$fields = $this->getUserFields( $vbdb );

			$content = array();
			foreach ( $fields as $key ) {
				if ( !empty( $this->settings['update_user_exp_'.$key] ) ) {
					$content[$key] = $this->settings['update_user_exp_'.$key];
				}
			}

			$this->updateUser( $vbdb, $content );
		}

		$groups = $this->userGroup( $vbdb, $vbUserId );

		if ( $this->settings['set_group_exp'] ) {
			foreach ( $this->settings['group_exp'] as $groupid ) {
				if ( !empty( $groups ) ) {
					if ( in_array( $groupid, $groups ) ) {
						continue;
					}
				}

				$this->assignGroup( $vbdb, $vbUserId, $groupid );
			}
		}

		return true;
	}

	function vbUserid( $db, $email )
	{
		$query = 'SELECT `userid`'
				. ' FROM ' . $this->settings['table_prefix'] . 'user'
				. ' WHERE LOWER( `email` ) = \'' . $email . '\''
				;
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function createUser( $db, $fields )
	{
		$query = 'INSERT INTO ' . $this->settings['table_prefix'] . 'user'
				. ' (`' . implode( '`, `', array_keys( $fields ) ) . '`)'
				. ' VALUES ( \'' . implode( '\', \'', array_values( $fields ) ) . '\' )'
				;
		$db->setQuery( $query );

		$db->query();
	}

	function updateUser( $db, $userid, $fields )
	{
		$set = array();
		foreach ( $fields as $key => $value ) {
			if ( !empty( $value ) ) {
				$set[] = '`' . $key . '` = \'' . $value . '\'';
			}
		}

		$query = 'UPDATE ' . $this->settings['table_prefix'] . 'user'
				. ' SET ' . implode( ', ', $set )
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		return $db->query();
	}

	function getUserFields( $db )
	{
		// Exclude all standard fields so that we can write possible custom fields
		$excluded = array(	"userid", "usergroupid", "membergroupids", "displaygroupid", "username", "password", "passworddate", "email",
							"styleid", "parentemail", "icq", "aim", "yahoo", "msn", "skype", "showvbcode",
							"showbirthday", "customtitle", "joindate", "daysprune", "lastvisit", "lastactivity", "lastpost",
							"lastpostid", "posts", "reputation", "reputationlevelid", "timezoneoffset", "pmpopup", "avatarid", "avatarrevision",
							"profilepicrevision", "sigpicrevision", "options", "birthday_search", "maxposts", "startofweek", "ipaddress", "referrerid",
							"languageid", "emailstamp", "threadedmode", "autosubscribe", "pmtotal", "pmunread", "salt", "ipoints",
							"infractions", "warnings", "infractiongroupids", "infractiongroupid", "adminoptions", "profilevisits", "friendcount", "friendreqcount",
							"vmunreadcount", "vmmoderatedcount", "socgroupinvitecount", "socgroupreqcount", "pcunreadcount", "pcmoderatedcount", "gmmoderatedcount", "importuserid"
							);

		$query = 'SHOW COLUMNS FROM #__user';
		$db->setQuery( $query );

		$fields = $db->loadResultArray();

		$return = array();
		if ( !empty( $fields ) ) {
			foreach ( $fields as $key ) {
				if ( !in_array( $key, $excluded ) ) {
					$return[] = $key;
				}
			}
		}

		return $return;
	}

	function userGroup( $db, $userid )
	{
		$query = 'SELECT `usergroupid`'
				. ' FROM ' . $this->settings['table_prefix'] . 'user'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		return $db->loadResultArray();
	}

	function assignGroup( $db, $userid, $groupid )
	{
		$query = 'UPDATE ' . $this->settings['table_prefix'] . 'user'
				. ' SET ' . '`usergroupid` = \'' . $groupid . '\''
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$db->setQuery( $query );

		$db->query();
	}

	function getDB()
	{
        if ( !empty( $this->settings['use_altdb'] ) ) {
	        $options = array(	'driver'	=> $this->settings['dbms'],
								'host'		=> $this->settings['dbhost'],
								'user'		=> $this->settings['dbuser'],
								'password'	=> $this->settings['dbpasswd'],
								'database'	=> $this->settings['dbname'],
								'prefix'	=> $this->settings['table_prefix']
								);

	        $database = &JDatabase::getInstance($options);
        } else {
        	$database = &JFactory::getDBO();
        }

		return $database;
	}

	function on_userchange_action( $request )
	{
		$database = &JFactory::getDBO();

		$vbulletinpw = new vbulletinpw( $database );
		$apwid = $vbulletinpw->getIDbyUserID( $request->row->id );

		if ( $apwid ) {
			$vbulletinpw->load( $apwid );
		} else {
			$vbulletinpw->load(0);
			$vbulletinpw->userid = $request->row->id;
		}

		if ( isset( $request->post['password_clear'] ) ) {
			$password = crypt( $request->post['password_clear'] );

		} elseif ( !empty( $request->post['password'] ) ) {
			$password = $request->post['password'];
		} elseif ( !empty( $request->post['password2'] ) ) {
			$password = $request->post['password2'];
		} elseif ( !$apwid ) {
			// No new password and no existing password - nothing to be done here
			return;
		}

		$vbulletinpw->vbulletinsalt	= $database->getEscaped( $vbulletinpw->saltgen() );
		$vbulletinpw->vbulletinpw	= $vbulletinpw->hash( $password, $vbulletinpw->vbulletinsalt );

		$vbulletinpw->check();
		$vbulletinpw->store();

		return true;
	}

}

class vbulletinpw extends JTable
{
	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $userid 			= null;
	/** @var string */
	var $vbulletinpw		= null;
	/** @var string */
	var $vbulletinsalt		= null;

	function vbulletinpw( &$db )
	{
		parent::__construct( '#__acctexp_mi_vbulletinpw', 'id', $db );
	}

	function loadUserID( $userid )
	{
		$uid = $this->getIDbyUserID( $userid );

		return $this->load( $uid );
	}

	function getIDbyUserID( $userid )
	{
		$database = &JFactory::getDBO();

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_mi_vbulletinpw'
				. ' WHERE `userid` = \'' . $userid . '\''
				;
		$database->setQuery( $query );
		return $database->loadResult();
	}

	function hash( $password, $salt )
	{
		return md5( md5( $password ) . $salt );
	}

	function saltgen( $length=16 )
	{
		return AECToolbox::randomstring( $length, true );
	}
}

?>