<?php
/**
 * Contains methods for querying the Goo.gl API
 * 
 */

require_once "microblogposter_curl.php";

 
class MicroblogPoster_Googl
{


    /**
     * Contains last error message
     * @var string
     * @access protected
     */
    protected $error_message;
    
    
    /**
     * Google API Key
     *
     * @var string
     * @access protected
     */
    protected $api_key = "";
    
    
    /**
     * MicroblogPoster_Googl Constructor
     *
     * @access public
     */	
    public function __construct()
    {
        
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
    public function setCredentials($apikey)
    {
        $this->api_key = $apikey;
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
        $googl_api_client_id_name = "microblogposter_plg_googl_api_client_id";
        $googl_api_client_secret_name = "microblogposter_plg_googl_api_client_secret";
        $googl_api_refresh_token_name = "microblogposter_plg_googl_api_refresh_token";
        $googl_api_client_id_value = get_option($googl_api_client_id_name, "");
        $googl_api_client_secret_value = get_option($googl_api_client_secret_name, "");
        $googl_api_refresh_token_value = get_option($googl_api_refresh_token_name, "");
        
        if($googl_api_client_id_value && $googl_api_client_secret_value && $googl_api_refresh_token_value)
        {
            $url = "https://accounts.google.com/o/oauth2/token";
            $post_args = array(
                'refresh_token' => $googl_api_refresh_token_value,
                'grant_type' => 'refresh_token',
                'client_id' => $googl_api_client_id_value,
                'client_secret' => $googl_api_client_secret_value
            );
            $curl = new MicroblogPoster_Curl();
            $json_res = $curl->send_post_data($url, $post_args);
            $response = json_decode($json_res, true);

            if(isset($response['access_token']) && isset($response['token_type']) && $response['token_type'] == 'Bearer')
            {
                $url = 'https://www.googleapis.com/urlshortener/v1/url';
                $headers = array(
                    'Authorization' => "Bearer " . $response['access_token'],
                    'Content-type'  => 'application/json'
                );
                $curl = new MicroblogPoster_Curl();
                $curl->set_headers($headers);
                $post_args = array(
                    'longUrl' => $long_url
                );
                $results_enc = $curl->send_post_data_json($url, json_encode($post_args));
                $results = json_decode($results_enc, true);

                if(isset($results['id']) && isset($results['longUrl']))
                {
                    return $results['id'];
                }
                else
                {
                    $log_data = array();
                    $log_data['account_id'] = 0;
                    $log_data['account_type'] = "goo.gl";
                    $log_data['username'] = 'None';
                    $log_data['post_id'] = 0;
                    $log_data['action_result'] = 0;
                    $log_data['update_message'] = '';
                    $log_data['log_message'] = $results_enc;
                    MicroblogPoster_Poster::insert_log($log_data);
                    return false;
                }
            }
            else
            {
                $log_data = array();
                $log_data['account_id'] = 0;
                $log_data['account_type'] = "goo.gl";
                $log_data['username'] = 'None';
                $log_data['post_id'] = 0;
                $log_data['action_result'] = 0;
                $log_data['update_message'] = '';
                $log_data['log_message'] = $json_res;
                MicroblogPoster_Poster::insert_log($log_data);
                return false;
            }
        }
    }
    
}   


?>