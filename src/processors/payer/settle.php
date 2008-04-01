<?
	require_once("payread_post_api.php");
/**
 * settle.php - Example code for verifying that settlement was successful
 * 
 * This file should echo the word "TRUE" if everything is ok, and "FALSE" if there is
 * a problem/error.
 * 
 * This file will be called by the Pay&Read POST API when a successful
 * settlement has been performed. See the file "auth.php" for a brief 
 * explanation on how to use this.
 * 
 * Normally, this is the file where you place the code that marks your transaction as
 * completed. When this file is called, and the Pay&Read parameters are ok, you
 * can be sure that the transaction has been performed in full.
 *
 * Filename: settle.php 
 * Project: payread_post_api
 * Created on 2005-nov-23
 * 
 * @author  Pay&Read Installation <installation@pay-read.se>
 * @package payread_post_api
 * 
 */	
 
	/**
	 * This is a simple helper function to create the request URL.
	 * It's not neccesary to fetch the URL this way, the URL could
	 * be stored somewhere else (e.g. in a database etc) while the 
	 * purchase is performed.
	 */
	function get_request_url(){
		return ($_SERVER["SERVER_PORT"]=="80" ? "http://" : "https://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

		/**
		 * Note: In some PHP versions REQUEST_URI will return nothing.
		 * If you are having problem with auth.php returning false and
		 * it seems to be the validate_callback_url-function that causes
		 * this problem, please try the method below request the correct URL.
		 */
		 //return ($_SERVER["SERVER_PORT"]=="80" ? "http://" : "https://").$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
	}
	
	$postAPI=new payread_post_api();
	if (
		/*
		 * Only Pay&Read external servers should be allowed to perform
		 * authentication.
		 */
		$_SERVER["REMOTE_ADDR"]=="83.241.130.100" || 
		$_SERVER["REMOTE_ADDR"]=="83.241.130.101" ||
		$_SERVER["REMOTE_ADDR"]=="10.4.49.11" ||
		$_SERVER["REMOTE_ADDR"]=="192.168.100.222" ||   
		$_SERVER["REMOTE_ADDR"]=="127.0.0.1" ||
		$_SERVER["REMOTE_ADDR"]=="217.151.207.84" ||
		$_SERVER["REMOTE_ADDR"]=="83.241.130.102")
	{
		/*
		 * This function call uses payread_post_api to perform a validity check of the 
		 * caller URL. This method basically performs an md5 check to make sure that 
		 * the caller is indeed Pay&Read.
		 */
		if ($postAPI->validate_callback_url(get_request_url())==true)
		{
			/*
			 * The Calling IP address is verified to be a Payread server
			 * and the url was properly "signed" by Pay&Read
			 * 
			 * This example file just checks the validity of the calling URL,
			 * but normally you would mark your purchase in the database as completed,
			 * since the payment has been completed. 
			 */
			echo "TRUE";
			exit();
		}
	}
	echo "FALSE";
?>
