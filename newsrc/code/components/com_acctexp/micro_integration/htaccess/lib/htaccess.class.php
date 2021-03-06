<?php
/**
 * Class for handling htaccess of Apache
 * @version $Id: htaccess.class.php 16 2007-06-27 09:04:04Z mic $
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Micro Integrations - htaccess
 * @copyright Intertribe - Internetservices Germany
 * @author Sven Wagener <sven.wagener@intertribe.de>
 * @license Unknown
 * @include Function:_include_
 */

class htaccess{
	var $fHtaccess	= ''; // path and filename for htaccess file
	var $fHtgroup	= ''; // path and filename for htgroup file
	var $fPasswd	= ''; // path and filename for passwd file

	var $authType	= 'Basic'; // Default authentification type
	var $authName	= 'Internal area'; // Default authentification name

	/**
	* Initialising class htaccess
	*/
	public function htaccess(){
	}

	/**
	* Sets the filename and path of .htaccess to work with
	* @param string	$filename	the name of htaccess file
	*/
	public function setFHtaccess($filename){
		$this->fHtaccess=$filename;
	}

	/**
	* Sets the filename and path of the htgroup file for the htaccess file
	* @param string	$filename	the name of htgroup file
	*/
	public function setFHtgroup($filename){
		$this->fHtgroup=$filename;
	}

 	/**
	* Sets the filename and path of the password file for the htaccess file
	* @param string	$filename	the name of htgroup file
	*/
	public function setFPasswd($filename){
		$this->fPasswd=$filename;
	}

	/**
	* Adds a user to the password file
	* @param string $username	 Username
	* @param string $password	 Password for Username
  	*/
	public function addUser($username,$password){
		if ( empty( $username ) ) {
			return true;
		}

		// checking if user already exists
		$file=@fopen($this->fPasswd,"r");
		$isAlready=false;
		while($line=@fgets($file,200)){
			$lineArr=explode(":", $line, 2);
			if($username==$lineArr[0]){
				$isAlready=true;
			 }
		}
		fclose($file);
		if($isAlready==false){
			$file=fopen($this->fPasswd,"a");

			$newLine=$username.":".$password;
			fputs($file,$newLine."\n");
			fclose($file);
			return true;
		}else{
			return false;
		}
	}

	/**
	* Adds a group to the htgroup file
	* @param string $groupname	 Groupname
  	*/
	public function addGroup($groupname){
		$file=fopen($this->fHtgroup,"a");
		fclose($file);
	}

	/**
	* Deletes a user in the password file
	* @param string $username	 Username to delete
	* @return boolean $deleted	Returns true if user have been deleted otherwise false
  	*/
	public function delUser($username){
		// Reading names from file
		$file=fopen($this->fPasswd,"r");
		$i=0;
		$newUserlist = array();
		while($line=fgets($file,200)){
			$lineArr=explode(":",$line);
			if($username!=$lineArr[0]){
				$newUserlist[$i][0]=$lineArr[0];
				$newUserlist[$i][1]=$lineArr[1];
				$i++;
			}else{
				$deleted=true;
			}
		}
		fclose($file);

		// Writing names back to file (without the user to delete)
		$file=fopen($this->fPasswd,"w");
		for($i=0;$i<count($newUserlist);$i++){
			fputs($file,$newUserlist[$i][0].":".$newUserlist[$i][1]);
		}
		fclose($file);

		if($deleted==true){
			return true;
		}else{
			return false;
		}
	}

   	/**
	* Returns an array of all users in a password file
 	* @return array $$newUserlist	All usernames of a password file in an array
	* @see setPasswd()
  	*/
	public function getUsers(){
		// Reading names from file
		$newUserlist = array();

		$file=fopen($this->fPasswd,"r");
		$x=0;
		for ($i=0; $line=fgets($file,4096); $i++) {
			$lineArr = explode(":",$line);
			$newUserlist[] = $lineArr[0];
			$x++;
		}

		return $newUserlist;
	}

	/**
	* Sets a password to the given username
	* @param string $username		The name of the User for changing password
	* @param string $new_password		New Password for the User
	* @return boolean $isSet		Returns true if password have been set
  	*/
	public function setPasswd($username,$new_password){
		// Reading names from file
		$newUserlist="";

		$file=fopen($this->fPasswd,"r");
		$x=0;
		for ($i=0; $line=fgets($file,4096); $i++) {
			$lineArr = explode(":",$line);
			if ($username != $lineArr[0] && $lineArr[0] != "" && $lineArr[1] != "") {
				$newUserlist[$i][0] = $lineArr[0];
				$newUserlist[$i][1] = $lineArr[1];
				$x++;
			} else if ($lineArr[0] != "" && $lineArr[1] != "") {
				$newUserlist[$i][0] = $lineArr[0];
				$newUserlist[$i][1] = $new_password."\n";
				$isSet = true;
				$x++;
			}
		}

		fclose($file);

		unlink($this->fPasswd);

		/// Writing names back to file (with new password)
		$file = fopen($this->fPasswd,"w");
		for ($i=0; $i<count($newUserlist); $i++) {
			$content = $newUserlist[$i][0] . ":" . $newUserlist[$i][1];
			fputs($file,$content);
		}
		fclose($file);

		if ($isSet==true) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Sets the Authentification type for Login
	* @param string $authtype	 Authentification type as string
  	*/
	public function setAuthType($authtype){
		$this->authType=$authtype;
	}

	/**
	* Sets the Authentification Name (Name of the login area)
	* @param string $authname	 Name of the login area
  	*/
	public function setAuthName($authname){
		$this->authName=$authname;
	}

	/**
	* Writes the htaccess file to the given Directory and protects it
  	*/
	public function addLogin(){
	   $file=fopen($this->fHtaccess,"w+");
	   fputs($file,"Order allow,deny\n");
	   fputs($file,"Allow from all\n");
	   fputs($file,"AuthType ".$this->authType."\n");
	   fputs($file,"AuthUserFile ".$this->fPasswd."\n\n");
	   fputs($file,"AuthName \"".$this->authName."\"\n");
	   fputs($file,"require valid-user\n");
	   fclose($file);
	}

	/**
	* Deletes the protection of the given directory
  	*/
	public function delLogin(){
		unlink($this->fHtaccess);
	}
}
