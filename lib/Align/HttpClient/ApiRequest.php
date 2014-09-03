<?php
namespace aligncommerce\lib\Align\HttpClient;
use aligncommerce\lib\Align\HttpClient\Curl as Curl;
use aligncommerce\lib\Align\Session\Session as Session;
use aligncommerce\lib\Align\Config\Apiconfig as Config;
use aligncommerce\lib\Align\HttpClient\AccessToken as Oauth;

class ApiRequest
{
   public $curl;
   public $session;
   protected static $accessToken;
   public $apiUrl; 

  public function __construct()
  { 
    $this->curl         = new Curl;
    $this->session      = new Session;
    session_write_close();
    session_set_save_handler($this->session , true);
    session_start();

    $accessToken =  $this->session->read('access_token');
    if(!empty($accessToken))
    {
      self::$accessToken = $accessToken;
    }
    else
    {
      session_write_close();
      $oauth = new Oauth;
      $oauth->getAuthorizationCode();
      self::$accessToken = $this->session->read('access_token');
    }  
  }

  public function all($url)
  {
    $data = array('access_token' => self::$accessToken);
    $apiUrl   = Config::$apiUrl . $url;
    $response = $this->curl->get($apiUrl, $data);
    return $response;
  }

  public function get($url)
  {
    $data = array('access_token' => self::$accessToken);
    $apiUrl   = Config::$apiUrl . $url;
    $response = $this->curl->get($apiUrl, $data);
    return $response;
  }

  public function post($url,$data=array())
  {
    $data['access_token'] = self::$accessToken;
    $apiUrl   = Config::$apiUrl . $url;
    $response = $this->curl->post($apiUrl, $data);
    return $response;
  }

  public function put($url, $data=array())
  {
    $data['access_token'] = self::$accessToken;
    $apiUrl   = Config::$apiUrl . $url;
    $response = $this->curl->put($apiUrl, $data);
    return $response;
  }

}
