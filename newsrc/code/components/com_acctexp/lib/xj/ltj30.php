<?php
class xJ
{
	function getDBArray( $db )
	{
		return xJ::getDBArray( $db );
	}

	function sendMail( $sender, $sender_name, $recipient, $subject, $message, $html=null, $cc=null, $bcc=null, $attach=null )
	{
		JUTility::sendMail( $sender, $sender_name, $recipient, $subject, $message, $html, $cc, $bcc, $attach );
	}
}

?>
