<?php
/**
 * @version	$Id: aecrewrite.php $
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Rewrite
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentAECRewrite extends JPlugin
{
	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare( $context, &$article, &$params, $page=0 )
	{
		// See whether there is anything to replace
		if (JString::strpos($article->text, '{aecrewrite') === false) {
			return true;
		}

		if ( false ) {
			// component check
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			$regex = "#{aecrewrite}(.*?){/aecrewrite}#s";

			$article->text = preg_replace_callback( $regex, array(&$this, '_replace'), $article->text );
		}

		return true;
	}

	/**
	 * Replaces the matched tags.
	 *
	 * @param	array	An array of matches (see preg_match_all)
	 * @return	string
	 */
	protected function _replace( &$matches )
	{
		static $rwEngine;

		jimport('joomla.utilities.utility');

		include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

		if ( empty( $rwEngine->rewrite ) ) {
			$user = &JFactory::getUser();

			$rwEngine = new reWriteEngine();

			$metaUser = new metaUser( $user->id );

			$request = new stdClass();
			$request->metaUser = $metaUser;

			$rwEngine->resolveRequest( $request );
		}

		return $rwEngine->resolveJSONitem( $matches[1], true );
	}
}
