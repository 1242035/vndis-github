<?php
/**
 * Created by PhpStorm.
 * User: honganh
 * Date: 10/31/2014
 * Time: 1:58 PM
 */

class Github {
	private $base_path;
	private $github;
	public function __construct()
	{
		$this->base_path = dirname(__FILE__);
		require_once $this->base_path. '/Github/Autoloader.php';
		Github_Autoloader::register();

		$this->github = new Github_Client();

	}
	public function search($keyword, $language, $page)
	{
		if(empty($keyword) )
		{
			return null;
		}
		if(isset($page) && $page > 0 && isset($language ) && !empty($language) )
		{
			return $this->github->getRepoApi()->search($keyword , $language, $page);
		}
		elseif(isset($language) && !empty($language) )
		{
			return $this->github->getRepoApi()->search($keyword , $language);
		}
		else
		{
			return $this->github->getRepoApi()->search($keyword);
		}
	}
	public function authenticate($username, $secret, $method=Github_Client::AUTH_URL_TOKEN)
	{
		$this->github->authenticate($username, $secret, $method);
	}



} 