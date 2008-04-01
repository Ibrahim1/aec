<?
	require_once("payread_post_api.php");
/**
 * auth.php - Example code for verifying that authorization was successful
 * 
 * This file should echo the word "TRUE" if everything is ok, and "FALSE" if there is
 * a problem.
 * 
 * This file will be called by the Pay&Read POST API when a successful
 * authorization has been performed. The base URL that will be called needs 
 * to be specified when the initial call to Pay&Read (inside the XML file)
 * and will be padded with two new parameters.
 * Example of steps to use this functionality:
 * 1. Construct your authorization notification URL and add it to the XML file
 * using set_authorize_notification_url(). Normally, the url will contain 
 * some kind of reference to the shops internal sale id, like
 * "http://www.myshop.com/auth.php?id=S1234567"
 * Of course, there are several other URLs, and other data as well that has to be 
 * added to the XML file, but this chapter only deals with this specific parameter.
 * 
 * 2. The XML file, containing amongst others this auhorization_notification_url is
 * then sent to Pay&Read, where the actual monetary transaction takes place. 
 * One of these steps is that the Pay&Read server checks if there are enough funds 
 * to allow the user to process with the transaction. 
 * 
 * 3. When this step is successfully performed, Pay&Read pads the 
 * authorization_notification_url with two additional
 * parameters, and fetches it. The callback url will look similar to this:
 * "http://www.myshop.com/auth.php?id=S1234567&payread_payment_id=XXXXXXXX&md5hash=F0F0F0F0F0F0F0F0F0F0F0F0F0F0F0F0"
 * 
 * This means that there has to be a receiver page somewhere at the shop, where
 * these parameters are parsed. Normally, no other action needs to be taken,
 * the merchant site just has to check that the parameters are correct. This example file
 * uses the payread_post_api to check that the incoming parameters are correct.  
 *
 * Filename: auth.php 
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
			 * If we would like to perform some action for the purchase here, just fetch the 
			 * part of the URL that identifies your purchase (e.g. id) and make the 
			 * changes to your database or similar 
			 * However, notice that there will be a second callback when the payment is
			 * fully performed, which means that normally, you don't have to do anything here,
			 * but return "TRUE"
			 */
			echo "TRUE";
			exit();
		}
	}
	echo "FALSE " . get_request_url();
?>
