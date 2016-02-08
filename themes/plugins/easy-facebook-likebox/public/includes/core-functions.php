<?php
if(!function_exists('efbl_time_ago')){ 
	function efbl_time_ago($date,$granularity=2) {
		//Preparing strings to translate
		$date_time_strings = array("second" => __('second', 'easy-facebook-likebox'), 
								   "seconds" =>  __('seconds', 'easy-facebook-likebox'), 
								   "minute" => __('minute', 'easy-facebook-likebox'), 
								   "minutes" => __('minutes', 'easy-facebook-likebox'), 
								   "hour" => __('hour', 'easy-facebook-likebox'), 
								   "hours" => __('hours', 'easy-facebook-likebox'), 
								   "day" => __('day', 'easy-facebook-likebox'), 
								   "days" => __('days', 'easy-facebook-likebox'),
								   "week" => __('week', 'easy-facebook-likebox'),
								   "weeks" => __('weeks', 'easy-facebook-likebox'), 
								   "month"  => __('month', 'easy-facebook-likebox'), 
								   "months"  => __('months', 'easy-facebook-likebox'), 
								   "year" => __('year', 'easy-facebook-likebox'),  
								   "years" => __('years', 'easy-facebook-likebox'),
								   "decade" => __('decade', 'easy-facebook-likebox'),
								   );
		
		$ago_text = __('ago', 'easy-facebook-likebox');
		$date = strtotime($date);
		$difference = time() - $date;
		$periods = array('decade' => 315360000,
			'year' => 31536000,
			'month' => 2628000,
			'week' => 604800, 
			'day' => 86400,
			'hour' => 3600,
			'minute' => 60,
			'second' => 1);
	
		foreach ($periods as $key => $value) {
			if ($difference >= $value) {
				$time = floor($difference/$value);
				$difference %= $value;
				$retval .= ($retval ? ' ' : '').$time.' ';
				$retval .= (($time > 1) ? $date_time_strings[$key.'s'] : $date_time_strings[$key] );
				$granularity--;
			}
			if ($granularity == '0') { break; }
		}
		 
		return ''.$retval.' '.$ago_text;      
	}
}

if(!function_exists('jws_fetchUrl')){
//Get JSON object of feed data
	function jws_fetchUrl($url){
		//Can we use cURL?
		if(is_callable('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$feedData = curl_exec($ch);
			curl_close($ch);
		//If not then use file_get_contents
		} elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
			$feedData = @file_get_contents($url);
		//Or else use the WP HTTP API
		} else {
			if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
			$request = new WP_Http;
			$result = $request->request($url);
			$feedData = $result['body'];
		}
	/*    echo $feedData;
		exit;*/
		return $feedData;
		
	}
}

if(!function_exists('ecff_stripos_arr')){
	function ecff_stripos_arr($haystack, $needle) {
		 
		if(!is_array($needle)) $needle = array($needle);
		foreach($needle as $what) {
			if(($pos = stripos($haystack, ltrim($what) ))!==false) return $pos;
		}
		return false;
	}
}

if(!function_exists('ecff_makeClickableLinks')){
	function ecff_makeClickableLinks($text)
	{
		return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
		
	}
}

if(!function_exists('ecff_hastags_to_link')){
	function ecff_hastags_to_link($text){
		
		return preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1#<a href="https://www.facebook.com/hashtag/\2" target="_blank">\2</a>', $text);
	}
}