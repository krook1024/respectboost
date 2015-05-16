<?php
	function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);   
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
	
	class SMF {
		protected $site, $user, $pass, $ch, $logged_in = false, $url, $start, $topic, $msg, $board, $secure, $which;
		public $darab;	
		
		public function __construct($_site, $cookies_file) {
			$this->site = $_site;
			$this->ch = curl_init();
			curl_setopt($this->ch, CURLOPT_USERAGENT, "Opera/9.6 (Windows NT 5.1; U; pl)"); 
			curl_setopt($this->ch, CURLOPT_TIMEOUT, '50'); 
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); 
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($this->ch, CURLOPT_REFERER, $this->site);
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookies_file);
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookies_file);
			$headers = array( 
                 "Cache-Control: no-cache", 
                ); 
			curl_setopt($curl1, CURLOPT_HTTPHEADER, $headers);
		}
		
		function SMF_login($user, $pass) {
			curl_setopt($this->ch, CURLOPT_URL, $this->site . 'index.php?action=login');
			curl_setopt($this->ch, CURLOPT_POST, 0);
			$buffer = curl_exec($this->ch);
			$sessionid =  substr($buffer, strpos($buffer, 'hashLoginPassword(this, ') + 25, 32);
			if(!$sessionid) {
				$this->SMF_login($user, $pass);
			}
			$sha_pass = sha1(sha1(strtolower($user) . $pass) . $sessionid);
			$data = "user=$user&passwrd=&cookieneverexp=on&hash_passwrd=$sha_pass"; 
			curl_setopt($this->ch, CURLOPT_URL, $this->site . 'index.php?action=login2');
			curl_setopt($this->ch, CURLOPT_POST, 1); 
			curl_setopt($this->ch,CURLOPT_POSTFIELDS, $data); 
			$buffer = curl_exec($this->ch); 
			$pos = strpos($buffer, 'Fórumon eltöltött összes idő'); 
			if($pos === false) {
				return 0; 
			} else {
				$this->logged_in = true;
				return 1;
			} 
		}
		
		function SMF_getsecure() {
			curl_setopt($this->ch, CURLOPT_URL, $this->site . "index.php?topic=57190.msg531013");
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->ch, CURLOPT_POST, 0);
			$thing = curl_exec($this->ch);
			
			$secure = get_string_between($thing, 'sa=up;msg=', 'topic');
			$secure = get_string_between($secure, ';', ';');
			
			return $secure;
		}
		function SMF_hitposts($id, $start = 0, $which = 0) {			
			$file = fopen($this->site . "index.php?action=profile;area=showposts;u=$id;start=$start", "r");
			$secure = $this -> SMF_getsecure();
			while(!feof($file)){
				$line = fgets($file);
				if (strpos($line, 'topic=')) {
					$topic = get_string_between($line, "topic=", ".");
					$msg = get_string_between($line, ".msg", "#");
					$board = get_string_between($line, "board=", ".");
					
					//echo $board . "<br>" . $topic . "<br>" . $msg . "<hr>";
					
					if($which == 0) $this -> SMF_giverespect($topic, $msg, $board, $secure);
					elseif($which == 1) $this -> SMF_giveminus($topic, $msg, $board, $secure);
					$darab ++;					
				} 
			}
			fclose($file);
		}

		function SMF_getdarab() {
			return $darab;
		}
		
		function SMF_giverespect($topic, $msg, $board, $secure) {		
			curl_setopt($this->ch, CURLOPT_URL, $this->site . "index.php?action=gpbp;sa=down;msg=$msg;$secure;board=$board;topic=$topic");
			curl_setopt($this->ch, CURLOPT_POST, 0);
			curl_exec($this->ch);
			curl_setopt($this->ch, CURLOPT_URL, $this->site . "index.php?action=gpbp;sa=up;msg=$msg;$secure;board=$board;topic=$topic");
			curl_setopt($this->ch, CURLOPT_POST, 0);			
			curl_exec($this->ch);
		}
		
		function SMF_giveminus($topic, $msg, $board, $secure) {
			curl_setopt($this->ch, CURLOPT_URL, $this->site . "index.php?action=gpbp;sa=up;msg=$msg;$secure;board=$board;topic=$topic");
			curl_setopt($this->ch, CURLOPT_POST, 0);
			curl_exec($this->ch);
			curl_setopt($this->ch, CURLOPT_URL, $this->site . "index.php?action=gpbp;sa=down;msg=$msg;$secure;board=$board;topic=$topic");
			curl_setopt($this->ch, CURLOPT_POST, 0);
			curl_exec($this->ch);
		}
	}
?>
