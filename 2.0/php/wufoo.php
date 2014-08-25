<?php

class ApiExample {
	
	const API_KEY = 'PDD5-4N0J-4KRG-L6II';
	const SUBDOMAIN = 'quotebook';
	
	/* -------------------------------
			  FORMS
	------------------------------- */
	
	public function getFormsDropdown() {
		try {
			$curl = new WufooCurl();
			$response = $curl->getAuthenticated(
				'http://'.self::SUBDOMAIN.'.wufoo.com/api/v3/forms.json', self::API_KEY);
			$response = json_decode($response);
			echo $this->getFormsDropdownHtml($response->Forms);
		} catch (Exception $e) {
			print_r($e);
		}
	}
	
	private function getFormsDropdownHtml($forms) {
		$str = '<select>';
		if (count($forms)) {
			foreach ($forms as $form) {
				$str.= '<option value="'.$form->Hash.'">'.$form->Name.'</option>';
			}
		}
		return $str.'</select>';
	}
	
	/* -------------------------------
			  EMBED
	------------------------------- */
	
	public function embedSnippet() {
		try {
			$curl = new WufooCurl();
			$response = $curl->getAuthenticated(
				'http://'.self::SUBDOMAIN.'.wufoo.com/api/v3/forms/web-hook-example.json', self::API_KEY);
			$response = json_decode($response);
			$hash = $response->Forms[0]->Hash;
			echo $this->getFormSnippet($hash, self::SUBDOMAIN);
		} catch (Exception $e) {
			print_r($e);
		}
	}
	
	private function getFormSnippet($hash, $subdomain) {
		return '<script type="text/javascript">var host = (("https:" == document.location.protocol) ? "https://secure." : "http://");document.write(unescape("%3Cscript src=\'" + host + "wufoo.com/scripts/embed/form.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script>

		<script type="text/javascript">
		var '.$hash.' = new WufooForm();
		'.$hash.'.initialize({
		\'userName\':\''.$subdomain.'\', 
		\'formHash\':\''.$hash.'\', 
		\'autoResize\':true,
		\'height\':\'416\', 
		\'ssl\':true});
		'.$hash.'.display();
		</script>';
	}
	
	/* -------------------------------
			  SUBMIT
	------------------------------- */
	
	public function submitForm() {
	$params = array();
	
	foreach ($_POST as $key => $value) {
		$params[$key] = $value;
	}
	
	try {
		$curl = new WufooCurl();
		$response = $curl->post(
			$params,
			'https://'.self::SUBDOMAIN.'.wufoo.com/api/v3/forms/contact/entries.json', 
			self::API_KEY);
	} catch (Exception $e) {
		print_r($e);
	}

	}
	

	/* -------------------------------
			  LOGIN
	------------------------------- */
	
	public function login() {
		$subdomain = '';
		$curl = new WufooCurl();
		$params = array(
			'email' => 'name@domain.com',
			'password' => 'pw',
			'integrationKey' => 'getMeFromWufoo');
			
		if ($subdomain) {
			$params['subdomain'] = $subdomain;
		}
			
		try {
			$response = $curl->post(
				$params,
				'https://wufoo.com/api/v3/login.xml');
			echo htmlentities($response);
		} catch (Exception $e) {
			print_r($e);
		}
		
	}
}

class WufooCurl {
	
	public function __construct() {
		//set timeout here if you like.
	}
	
	public function getAuthenticated($url, $apiKey) {
		$this->curl = curl_init($url); 
		$this->setBasicCurlOptions();
		
		curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

		$response = curl_exec($this->curl);
		$this->setResultCodes();
		$this->checkForCurlErrors();
		$this->checkForGetErrors($response);
		curl_close($this->curl);
		return $response;
	}
	
	public function post($postParams, $url, $apiKey = '') {
		$this->curl = curl_init($url); 
		$this->setBasicCurlOptions();
		
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postParams);		
		if ($apiKey) curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

		$response = curl_exec($this->curl);
		$this->setResultCodes();
		$this->checkForCurlErrors();
		$this->checkForPostErrors($response);
		curl_close($this->curl);
		return $response;
	}
	
	private function setBasicCurlOptions() {
		//http://bugs.php.net/bug.php?id=47030
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_USERAGENT, 'Wufoo API Wrapper');
		curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	}
	
	private function setResultCodes() {
		$this->ResultStatus = curl_getinfo($this->curl);		
	}
	
	private function checkForCurlErrors() {
		if(curl_errno($this->curl)) {
			if ($closeConnection) curl_close($this->curl);
			throw new Exception(curl_error($this->curl), curl_errno($this->curl));
		}
	}
	
	private function checkForGetErrors($response) {
		switch ($this->ResultStatus['http_code']) {
			case 200:
				//ignore, this is good.
				break;
			case 401:
				throw new Exception('(401) Forbidden.  Check your API key.', 401);
				break;
			default:
				$this->throwResponseError($response);
				break;
		}
	}
	
	private function checkForPostErrors($response) {
		switch ($this->ResultStatus['http_code']) {
			case 200:
			case 201:
				//ignore, this is good.
				break;
			case 401:
				throw new Exception('(401) Forbidden. Check your API key.', 401);
				break;
			default:
				$this->throwResponseError($response);
				break;
		}
	}
	
	private function throwResponseError($response) {
		if ($response) {
			$obj = json_decode($response);
			throw new Exception('('.$obj->HTTPCode.') '.$obj->Text, $this->ResultStatus['HTTP_CODE']);
		} else {
			throw new Exception('('.$this->ResultStatus['HTTP_CODE'].') This is embarrassing... We did not anticipate this error type.  Please contact support here: support@wufoo.com', $this->ResultStatus['HTTP_CODE']);
		}
		return $response;
	}
	
}
?>