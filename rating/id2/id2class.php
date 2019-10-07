<?php
class Id2action {

	private $appid;
	private $pass;
	private $base_url = 'https://id2.action-media.ru';
	
	
	public function __construct($appid = 0, $pass = '') {
		$this->appid = $appid;
		$this->pass = $pass;
	}

	// Проверка токена
	public function validateToken($token = '') {
		if ($token == '') {
			return false;
		}

		$url = $this->base_url . '/api/rest/Invoke';
		$params =  array(
			'method' => 'validate',
			'token' => $token,
			'format' => 'json',
			'appid' => $this->appid,
		);
		$params['sig'] = $this->get_sign($params);
		$url = $url . '?' . http_build_query($params);
		$res = $this->send_request($params, $url, 'GET');
		return $res;
	}

	// Получение списка доступов
	public function GetAccess($token = '') {
		if ($token == '') {
			return false;
		}

		$url = $this->base_url . '/api/rest/Invoke';
		$params =  array(
			'method' => 'getaccess',
			'token' => $token,
			'format' => 'json',
			'appid' => $this->appid,
		);
		$params['sig'] = $this->get_sign($params);
		$url = $url . '?' . http_build_query($params);
		$res = $this->send_request($params, $url, 'GET');
		return $res;
	}

	// Получение профиля пользователя
	public function GetProfile($token = '') {
		if ($token == '') {
			return false;
		}

		$url = $this->base_url . '/api/rest/Invoke';
		$params =  array(
			'method' => 'getprofile',
			'token' => $token,
			'format' => 'json',
			'appid' => $this->appid,
		);
		$params['sig'] = $this->get_sign($params);
		$url = $url . '?' . http_build_query($params);
		$res = $this->send_request($params, $url, 'GET');
		return $res;
	}

	// Получение расширенного профиля пользователя
	public function GetExtendedUserProfile($token = '') {
		if ($token == '') {
			return false;
		}

		$url = $this->base_url . '/api/rest/Invoke';
		$params =  array(
			'appid'  => $this->appid,
			'fields' => 'region',
			'token'  => $token,
			'format' => 'json',
			'method' => 'getextendeduserprofile',
		);
		$params['sig'] = $this->get_sign($params);
		$url = $url . '?' . http_build_query($params);
		$res = $this->send_request($params, $url, 'GET');
		return $res;
	}

	// Получение расширенного профиля пользователя
	public function GetProfile2($token = '') {
		if ($token == '') {
			return false;
		}

		$url = $this->base_url . '/api/rest/Invoke';
		$params =  array(
			'appid'  => $this->appid,
			'fields' => 'Fillprofile',
			'token'  => $token,
			'format' => 'json',
			'method' => 'getprofile2',
			
		);
		$params['sig'] = $this->get_sign(array(
			'appid'  => $this->appid,
			'format' => 'json',
			'method' => 'getprofile2',
			'token'  => $token,
			'fields' => "Fillprofile",
		));
		$url = $url . '?' . http_build_query($params);
		$res = $this->send_request($params, $url, 'GET');
		return $res;
	}

	// Данные для формы авторизации
	public function getLoginData($callbackurl) {
    	$params = array(
			'appid' 		=> $this->appid,
			'callbackurl' 	=> $callbackurl,
			'rand' 			=> $this->mt_rand(),
		);
		$sig = $this->get_sign($params);
        $params['sig'] = $sig;
		$params['url'] = $this->base_url . '/Account/Login';
        return $params;
    }

    public function getLoginLink($callbackurl) {
    	return $this->getLoginData($callbackurl);
    }

    // Ссылка для регистрации
	public function getRegistrationLink($callbackurl, $return_params = false) {
        $url = $this->base_url . '/Account/Registration';
        $params = array(
    		'appid' 		=> $this->appid,
			'callbackurl' 	=> $callbackurl,
			'rand' 			=> $this->mt_rand(),
		);
        $sig = $this->get_sign($params);
		$params['sig'] = $sig;

		if ($return_params) {
			return $params;
		}
        return $url.'?'.http_build_query($params);
    }

    // Данных для ShortLogin
	public function getShortLoginLinks($callbackurl, $mode) {
    	$params = array(
			'appid' 		=> $this->appid,
			'callbackurl' 	=> $callbackurl,
			'mode' 			=> $mode,
			'rand' 			=> $this->mt_rand()
		);
		$sig = $this->get_sign($params);
        $params['sig'] = $sig;
		$params['url'] = $this->base_url . '.Account/ShortLogin';
        return $params;
    }
    
    public function getLogOutLink($callbackurl) {
        return '';
    }

    // Отправка запроса
	private function send_request($params, $url, $method = 'POST') {
		$query_str = http_build_query($params);
		if ($method == 'POST') {
			$context = stream_context_create(array(
	            'http' => array(
	                'method' => $method,
	                'header' => 'Content-Type: application/x-www-form-urlencoded' . "\r\n"
	                    . "Content-Length: " . strlen($query_str) . "\r\n",
	                'content' => $query_str,
	                'timeout' => 3,
	            ),
	        ));
		} else {
			$context = stream_context_create(array(
	            'http' => array(
	                'method' => $method,
	                'timeout' => 3,
	            ),
	        ));
		}

        try {
            $res = file_get_contents($url, false, $context);
            if (!empty($res)) {
                return json_decode($res, true);
            }
        } catch(\Exception $e) {
        	return false;
        }
	}

	// Случайное число
	private function mt_rand() {
		mt_srand(time());
		return mt_rand(1,999999999999);
	}

	// Подпись
	private function get_sign($params) {
		ksort($params);
		foreach ($params as $param => $value) {
			$arr[] = mb_strtolower($param.$value, 'utf-8');
		}
		$params_str = implode('',$arr);

		return md5(md5($params_str.$this->appid).$this->pass);
		//return $params_str;
	}
}