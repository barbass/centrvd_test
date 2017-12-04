<?php

use GuzzleHttp\Client;

/**
 * Класс работы с сайтом https://www.kinopoisk.ru/
 *
 */
class KinoPoiskFilm {
	/**
	 * @var array Конфиг для авторизации
	 */
	protected $config_auth = [
		'login' => '',
		'password' => '',
	];

	/**
	 * @var array Данные фильма
	 */
	protected $page = [
		'name' => null,
	];

	/**
	 * Конструктор
	 * @param array $config_auth данные для авторизации
	 * @param string $url Ссылка на фильм
	 */
	public function __construct($config_auth = null, $url = null) {
		if ($config_auth) {
			$this->auth($config_auth);
		}

		if ($url) {
			$this->getPage($url);
		}
	}

	/**
	 * Авторизация на сайте
	 * @param array
	 */
	public function auth($config_auth) {
		return;

		// Не работает
		$jar = new \GuzzleHttp\Cookie\CookieJar();

		$client = new GuzzleHttp\Client();
		$response = $client->request('POST', 'https://plus.kinopoisk.ru/user/resolve-by-password', [
			'form_params' => $config_auth,
			'headers' => [
				'Referer'         	=> 'https://www.kinopoisk.ru/',
				'User-Agent'      	=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
				'Content-Type' 		=> 'application/x-www-form-urlencoded',
				'Accept'          	=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				'charset' 			=> 'utf-8',
				'Accept-Language' 	=> 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
			],
			'cookies' => $jar,
			'debug' => $f,
		]);

		return true;
	}

	/**
	 * Получение страницы сайта
	 */
	public function getPage($url) {
		$client = new GuzzleHttp\Client();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Referer'         	=> 'https://www.kinopoisk.ru/',
				'User-Agent'     	=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
				'Accept'          	=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				'Accept-Language' 	=> 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
				'charset' 			=> 'utf-8',
			]
		]);

		$this->parsePage($response->getBody());

		return true;
	}

	/**
	 * Получение названия фильма
	 * @return string
	 */
	public function getFilmName() {
		return (!empty($this->page['name'])) ? $this->page['name'] : null;
	}

	/**
	 * Парсинг страницы
	 * @return array
	 */
	public function parsePage($html) {
		$html = mb_convert_encoding($html, 'utf-8', 'windows-1251');

		// Поиск названия
		$title_arr = explode('<title>', $html);
		$title_arr = explode('</title>', $title_arr[1]);
		$title_arr = explode('—', $title_arr[0]);
		$title = trim($title_arr[0]);

		$this->page['name'] = trim($title);

		return $this->page;
	}

}
