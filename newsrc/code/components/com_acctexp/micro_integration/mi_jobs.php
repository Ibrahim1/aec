<?php
/**
 * @version $Id: mi_jobs.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Jobs!
 * @copyright 2010 Copyright (C) Daniel Lowhorn, David Deutsch
 * @author Daniel Lowhorn, David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_jobs
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JOBS;
		$info['desc'] = _AEC_MI_DESC_JOBS;

		return $info;
	}

	function Settings()
	{
		$settings          = array();

		$settings['subscription_type']			= array( 'list' );
		$settings['default_resume_title']		= array( 'inputC' );
		$settings['default_resume_status']		= array( 'list' );
    	$settings['default_resume_language']	= array( 'inputC' );
		$settings['default_company_title']		= array( 'inputC' );
        $settings['default_company_country']	= array( 'inputC' );
		$settings['default_company_status']		= array( 'list' );

        $settings['lists'] = array();

		$typelist = array();
		$typelist[] = mosHTML::makeOption ( 'job_seeker' , 'Job Seeker' );
		$typelist[] = mosHTML::makeOption ( 'employer' , 'Employer' );
		
		$settings['lists']['subscription_type'] = mosHTML::selectList( $typelist,'subscription_type','size=4', 'value', 'text' , $this->settings['subscription_type'] );

		$drs_typelist = array();
		$drs_typelist[] = mosHTML::makeOption ( '0' , 'Unpublished' );
		$drs_typelist[] = mosHTML::makeOption ( '1' , 'Published' );
		
		$settings['lists']['default_resume_status'] = mosHTML::selectList( $drs_typelist,'default_resume_status','size=4', 'value', 'text' , $this->settings['default_resume_status'] );
		$settings['lists']['default_company_status'] = mosHTML::selectList( $drs_typelist,'default_company_status','size=4', 'value', 'text' , $this->settings['default_company_status'] );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_SET4_MYSQL, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function action( $request )
	{
		if ( $this->settings['subscription_type'] == 'job_seeker' ) {
			$resumes = $this->getResumeList( $request->metaUser->userid );

			if ( !count( $resumes ) ) {
				$this->createDummyResume( $request );
			} else {
				$this->publishResumes( $request->metaUser->userid );
			}
		} else {						
			$companies = $this->getCompanyList( $request->metaUser->userid );

			if ( !count( $companies ) ) {
				$this->createDummyCompany( $request );
			} else {
				$this->publishJobs( $companies );

				$this->publishCompanies( $request->metaUser->userid );
			}
		}
		
		return true;
	}

	function expiration_action( $request )
	{
		if ( $this->settings['subscription_type'] == 'job_seeker' ) {
			$this->unpublishResumes( $request->metaUser->userid );
		} else {
			$companies = $this->getCompanyList( $request->metaUser->userid );
			
			$this->unpublishJobs( $companies );
			
			$this->unpublishCompanies( $request->metaUser->userid );
		}
	}

	function getCompanyList( $userid )
	{
		$query = 'SELECT `id`'
				. ' FROM `#__jobs_companies`'
				. ' WHERE `memberid` = \'' . $userid . '\'';

		$database->setQuery( $query );

		return $database->loadResultArray();
	}

	function createDummyCompany( $request )
	{
		$title = AECToolbox::rewriteEngineRQ( $this->settings['default_company_title'], $request );

		$fields = array(	'title'			=> $title,
							'alias'			=> $this->getAlias( $title ),
							'country'		=> AECToolbox::rewriteEngineRQ( $this->settings['default_company_country'], $request ),
							'contactemail'	=> $request->metaUser->cmsUser->email,
							'description'	=> '',
							'address'		=> '',
							'companyurl'	=> '',
							'published'		=> $this->settings['default_company_status'],
							'memberid'		=> $request->metaUser->userid,
							'created'		=> date( 'Y-m-d H:i:s' )
							);

		$this->createCompany( $fields );
	}

	function createCompany( $fields )
	{
		$database = &JFactory::getDBO();

		$query = 'INSERT INTO #__jobs_companies'
				. ' (`' . implode( '`, `', array_keys( $fields ) ) . '`)'
				. ' VALUES ( \'' . implode( '\', \'', array_values( $fields ) ) . '\' )'
				;
		$database->setQuery( $query );

		$database->query();
	}

	function publishCompanies( $userid )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_jobs`'
				. ' SET `published` = \'1\''
				. ' WHERE `memberid` = \'' . $userid . '\''
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function unpublishCompanies( $userid )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_jobs`'
				. ' SET `published` = \'0\''
				. ' WHERE `memberid` = \'' . $$userid . '\''
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function publishJobs( $company_list )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_jobs`'
				. ' SET `published` = \'1\''
				. ' WHERE `company_id` (' . implode( ',', $company_list ) . ')'
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function unpublishJobs( $company_list )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_jobs`'
				. ' SET `published` = \'0\''
				. ' WHERE `company_id` (' . implode( ',', $company_list ) . ')'
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function getResumeList( $userid )
	{
		$query = 'SELECT `id`'
				. ' FROM `#__jobs_resumes`'
				. ' WHERE `memberid` = \'' . $userid . '\'';

		$database->setQuery( $query );

		return $database->loadResultArray();
	}

	function createDummyResume( $request )
	{
		$title = AECToolbox::rewriteEngineRQ( $this->settings['default_resume_title'], $request );

		$fields = array(	'title'			=> $title,
							'language'		=> ucwords(strtolower(AECToolbox::rewriteEngineRQ( $this->settings['default_resume_language'], $request ))),
							'published'		=> $this->settings['default_company_status'],
							'name'			=> $request->metaUser->cmsUser->username,
							'email_address'	=> $request->metaUser->cmsUser->email,
							'memberid'		=> $request->metaUser->userid,
							'created'		=> date( 'Y-m-d H:i:s' ),
							'alias'			=> $this->getAlias( $title )
							);

		$this->createResume( $fields );
	}

	function createResume( $fields )
	{
		$database = &JFactory::getDBO();

		$query = 'INSERT INTO #__jobs_resumes'
				. ' (`' . implode( '`, `', array_keys( $fields ) ) . '`)'
				. ' VALUES ( \'' . implode( '\', \'', array_values( $fields ) ) . '\' )'
				;
		$database->setQuery( $query );

		$database->query();
	}

	function publishResumes( $userid )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_resumes`'
				. ' SET `published` = \'1\''
				. ' WHERE `memberid` = \'' . $userid . '\'';
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function unpublishResumes( $userid )
	{
		$database = &JFactory::getDBO();

		$query = 'UPDATE `#__jobs_resumes`'
				. ' SET `published` = \'0\''
				. ' WHERE `memberid` = \'' . $userid . '\'';
				;

		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	function getAlias( $string )
	{
		$string = htmlentities( utf8_decode($string));
		$string = str_replace( ' ','-',$string);
		$string = preg_replace( "@[^A-Za-z0-9\-_]+@i","",$string);

		return strtolower($string);
	}
}
?>
