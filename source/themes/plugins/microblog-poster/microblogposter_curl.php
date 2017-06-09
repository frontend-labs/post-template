<?php
/**
 * Simple OOP wrapper around cURL php lib.
 *
 */
class MicroblogPoster_Curl
{
	/**
	 * Curl handler
	 * @access private
	 * @var resource
	 */
	var $ch ;

	/**
	 * set debug to true in order to get usefull output
	 * @access private
	 * @var string
	 */
	var $debug = false;

	/**
	 * Contain last error message if error occured
	 * @access private
	 * @var string
	 */
	var $error_msg;


	/**
	 * MicroblogPoster_Curl constructor
	 * @param boolean debug
	 * @access public
	 */
	public function __construct($debug = false)
	{
		$this->debug = $debug;
		$this->init();
	}

	/**
	 * Init Curl session
	 * @access public
	 */
	function init()
	{
		// initialize curl handle
		$this->ch = curl_init();

		//set various options

		//set error in case http return code bigger than 300
		//curl_setopt($this->ch, CURLOPT_FAILONERROR, 1);

		// use gzip if possible
		curl_setopt($this->ch,CURLOPT_ENCODING , 'gzip, deflate');

		// do not veryfy ssl
		// this is important for windows
		// as well for being able to access pages with non valid cert
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

		//do not verify host for ssl mathch
		curl_setopt($this->ch,CURLOPT_SSL_VERIFYHOST, 0);
	}

	/**
	 * Set username/pass for basic http auth
	 * @param string user
	 * @param string pass
	 * @access public
	 */
	function set_credentials($username,$password)
	{
		curl_setopt($this->ch, CURLOPT_USERPWD, "$username:$password");
	}

	/**
	 * Set referrer
	 * @param string referrer url
	 * @access public
	 */
	function set_referrer($referrer_url)
	{
		curl_setopt($this->ch, CURLOPT_REFERER, $referrer_url);
	}

	/**
	 * Set client's useragent
	 * @param string user agent
	 * @access public
	 */
	function set_user_agent($useragent)
	{
		curl_setopt($this->ch, CURLOPT_USERAGENT, $useragent);
	}

	/**
	 * Set to receive output headers in all output functions
	 * @param boolean true to include all response headers with output, false otherwise
	 * @access public
	 */
	function include_response_headers($value)
	{
		curl_setopt($this->ch, CURLOPT_HEADER, $value);
	}

	/**
	 * Set to include the specified headers.
	 * @param array $headers an array in the following form: array('name' => 'value', ...);
	 */
	function set_headers($headers)
	{
		$parsed_headers = array();
		
		if(is_array($headers))
		{		    
		    foreach ($headers as $name => $value)
		    {
			    $parsed_headers[] = $name . ': ' . $value;
		    }		  
		}

		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $parsed_headers);
	}

	/**
	 * This method is wrapper for CURLOPT_FAILONERROR method
	 * By default it is turned on
	 * By calling this method curl will ignore http errors
	 * @access public
	 */
	function ignore_http_errors()
	{
		//set error in case http returns code bigger than 300
		curl_setopt($this->ch, CURLOPT_FAILONERROR, 0);
	}



	/**
	 * Send post data to target URL
	 * return data returned from url or false if error occured
	 * @param string url
	 * @param mixed post data (assoc array ie. $foo['post_var_name'] = $value or as string like var=val1&var2=val2)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30)
	 * @return string data
	 * @access public
	 */
	function send_post_data($url, $postdata, $ip=null, $timeout=30)
	{
		//set various curl options first

		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//set method to post
		curl_setopt($this->ch, CURLOPT_POST, true);


		//generate post string
		$post_array = array();
		if(is_array($postdata))
		{
			foreach($postdata as $key=>$value)
			{
				$post_array[] = urlencode($key) . "=" . urlencode($value);
			}

			$post_string = implode("&",$post_array);

			if($this->debug)
			{
				echo "Url: $url\nPost String: $post_string\n";
			}
		}
		else
		{
			$post_string = $postdata;
		}

		// set post string
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);


		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}
                        
                        $result .= "Error Occured in Curl\n";
                        $result .= "Error number: " .curl_errno($this->ch) ."\n";
                        $result .= "Error message: " .curl_error($this->ch)."\n";
			
                        return $result;
		}
		else
		{
			return $result;
		}
	}

        /**
	 * Send post data to target URL
	 * return data returned from url or false if error occured
	 * @param string url
	 * @param mixed post data (assoc array ie. $foo['post_var_name'] = $value or as string like var=val1&var2=val2)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30)
	 * @return string data
	 * @access public
	 */
	function send_post_data_json($url, $postdata, $timeout=30)
	{
		//set various curl options first

		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);


		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//set method to post
		curl_setopt($this->ch, CURLOPT_POST, true);


		// set post string
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postdata);


		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

                        $result .= "Error Occured in Curl\n";
                        $result .= "Error number: " .curl_errno($this->ch) ."\n";
                        $result .= "Error message: " .curl_error($this->ch)."\n";
                        
			return $result;
		}
		else
		{
			return $result;
		}
	}
        
	/**
	 * fetch data from target URL
	 * return data returned from url or false if error occured
	 * @param string url
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30)
	 * @return string data
	 * @access public
	 */
	function fetch_url($url, $ip=null, $timeout=30)
	{
		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		//set method to get
		curl_setopt($this->ch, CURLOPT_HTTPGET,true);

		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			$result .= "Error Occured in Curl\n";
                        $result .= "Error number: " .curl_errno($this->ch) ."\n";
                        $result .= "Error message: " .curl_error($this->ch)."\n";
                        
                        return $result;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Fetch data from target URL
	 * and store it directly to file
	 * @param string url
	 * @param resource value stream resource(ie. fopen)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30)
	 * @return boolean true on success false othervise
	 * @access public
	 */
	function fetch_into_file($url, $fp, $ip=null, $timeout=30)
	{
		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL,$url);

		//set method to get
		curl_setopt($this->ch, CURLOPT_HTTPGET, true);

		// store data into file rather than displaying it
		curl_setopt($this->ch, CURLOPT_FILE, $fp);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
		}

		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//and finally send curl request
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return true;
		}
	}


	/**
	 * Set file location where cookie data will be stored and send on each new request
	 * @param string absolute path to cookie file (must be in writable dir)
	 * @access public
	 */
	function store_cookies($cookie_file)
	{
		// use cookies on each request (cookies stored in $cookie_file)
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
	}

	/**
	 * Set custom cookie
	 * @param string cookie
	 * @access public
	 */
	function set_cookie($cookie)
	{
		curl_setopt ($this->ch, CURLOPT_COOKIE, $cookie);
	}

	/**
	 * Set alternative port
	 * @param int $port
	 * @access public
	 */
	function set_port($port)
	{
		curl_setopt ($this->ch, CURLOPT_PORT, $port);
	}

	/**
	 * Get last URL info
	 * usefull when original url was redirected to other location
	 * @access public
	 * @return string url
	 */
	function get_effective_url()
	{
		return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
	}

	/**
	 * Get http response code
	 * @access public
	 * @return int
	 */
	function get_http_response_code()
	{
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}

	/**
	 * Return last error message and error number
	 * @return string error msg
	 * @access public
	 */
	function get_error_msg()
	{
		$err = "Error number: " .curl_errno($this->ch) ."\n";
		$err .="Error message: " .curl_error($this->ch)."\n";

		return $err;
	}

	/**
	 * Close curl session and free resource
	 * Usually no need to call this function directly
	 * in case you do you have to call init() to recreate curl
	 * @access public
	 */
	function close()
	{
		//close curl session and free up resources
		curl_close($this->ch);
	}
}


?>
