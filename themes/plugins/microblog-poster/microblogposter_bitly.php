<?php
/**
 * Contains methods for querying the Bitly API
 * 
 */

require_once "microblogposter_curl.php";

 
class MicroblogPoster_Bitly
{

    /**
     * Instance of MicroblogPoster_Curl
     * @access protected
     * @var object $browser
     */
    protected $browser;


    /**
     * Contains last error message
     * @var string
     * @access protected
     */
    protected $error_message;
    
    /**
     * Bitly API username
     *
     * @var string
     * @access protected
     */
    protected $bitly_username = "";
    
    /**
     * Bitly API Key
     *
     * @var string
     * @access protected
     */
    protected $bitly_api_key = "";
    
    /**
     * Bitly Access token
     *
     * @var string
     * @access protected
     */
    protected $bitly_access_token = "";
    
    
    /**
     * MicroblogPoster_Bitly Constructor
     *
     * @access public
     */	
    public function __construct()
    {
        $this->browser = new MicroblogPoster_Curl();
        $useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.9) Gecko/20100824 Firefox/3.6.9";
        $this->browser->set_user_agent($useragent);
    }
    
    /**
     * Returns the error text
     *
     * @return string
     * @access public
     */
    public function getError()
    {
        return $this->error_message;
    }
    
    /**
    * Set credentials
    *
    * @return string
    * @access public
    */
    public function setCredentials($user, $apikey, $accesstoken)
    {
        $this->bitly_username = $user;
        $this->bitly_api_key = $apikey;
        $this->bitly_access_token = $accesstoken;
    }
    
    /**
     * Get the shortened url
     *
     * @param string $long_url
     * @return string
     * @access public
     */		
    public function shorten($long_url)
    {
        if($this->bitly_access_token)
        {
            $url = "https://api-ssl.bitly.com/v3/shorten?access_token={$this->bitly_access_token}";
            $url .= "&longUrl=".urlencode($long_url);
        }
        else
        {
            $url = "https://api-ssl.bitly.com/v3/shorten?login={$this->bitly_username}";
            $url .= "&apiKey={$this->bitly_api_key}&longUrl=".urlencode($long_url);
        }
        
        
        $results = $this->browser->fetch_url($url);
        if($results === false)
        {
            $this->error_message = "Error : " . $this->browser->get_error_msg();
            return false;
        }
        
        $results = json_decode($results, true);
        
        if($results['status_code'] != 200)
        {
            $this->error_message = "Error : Ivalid response from Bitly API.";
            return false;
        }
        
        return $results['data']['url'];
    }
    
}   


?>