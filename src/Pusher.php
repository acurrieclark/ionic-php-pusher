<?php

namespace acurrieclark\IonicPhpPusher;

use acurrieclark\IonicPhpPusher\Exception\PusherException;
use acurrieclark\IonicPhpPusher\Exception\TokenException;
use acurrieclark\IonicPhpPusher\Exception\AuthException;
use acurrieclark\IonicPhpPusher\Exception\DataException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
* Pusher
*/
class Pusher
{

  protected $authToken, $endPoints;

  function __construct($authToken)
  {
    $this->authToken = $authToken;
    $this->verifyAuthToken();
    $this->endPoints = [
      'pushSend' => 'https://api.ionic.io/push/notifications',
      'testApi' => 'https://api.ionic.io/auth/test'
    ];
  }

  public function getAuthToken() {
    return $this->authToken;
  }

  public function sendToTokens($tokens, $profile, $notification, $scheduled = null) {

    if (empty($tokens)) {
      throw new PusherException("Please include an array of tokens", 1);
    }
    else if (!is_array($tokens)) {
      throw new PusherException("Tokens must be an array", 2);
    }
    else if (!$profile) {
      throw new PusherException("Please provide a profile", 3);
    }
    else if (!is_string($profile)) {
      throw new PusherException("Profile must be a string", 4);
    }
    else if (!$notification) {
      throw new PusherException("Please provide a notification data object", 5);
    }
    else if (!is_object($notification)) {
      throw new PusherException("Notification data must be an object", 6);
    }


    $data = new \stdClass();
    $data->tokens = $tokens;
    $data->profile = $profile;
    $data->notification = $notification;

    $response = $this->sendRequest('POST', $this->endPoints['pushSend'], $this->getHeaders(), $data);
    return json_decode($response->getBody()->getContents());
  }

  public function testApiAccess() {
    $response = $this->sendRequest('GET', $this->endPoints['testApi'], $this->getHeaders());
    return ($response) ? true : false;
  }

  protected function verifyAuthToken() {
    if (!$this->authToken) {
      throw new TokenException('Auth token is not a valid JWT. Please check you are using the correct token');
    }
    $parts = explode('.', $this->authToken);
    if (is_object(json_decode(base64_decode($parts[0]))) && is_object(json_decode(base64_decode($parts[1]))))
      return true;
    else {
      throw new TokenException('Auth token is not a valid JWT. Please check you are using the correct token');
    }
  }

  protected function getHeaders() {
    $authorization = sprintf("Bearer %s", $this->authToken);
    return array(
      'Authorization' => $authorization,
      'Content-Type' => 'application/json'
    );
  }

  protected function sendRequest($type, $endPoint, $headers = [], $body = [])
  {
    if (!is_string($body)) {
      $body = json_encode($body);
    }
    $request = new Request($type, $endPoint, $headers, $body);
    $client = new Client();

    try {

      $response = $client->send($request);
      return $response;

    } catch (ClientException $e) {
      if ($e->hasResponse()) {
        $response = json_decode($e->getResponse()->getBody()->getContents());
        $message = $response->error->message;
      }
      else {
        $message = $e->getMessage();
        $reponse = false;
      }

      switch ($e->getCode()) {
        case 401:
          throw new AuthException($message, $e->getCode(),$e, $response);
          break;
        case 422:
          throw new DataException($message, $e->getCode(),$e, $response);
          break;
        default:
          throw new PusherException($message, $e->getCode(),$e, $response);
          break;
      }


    } catch (\Exception $e) {
      throw new PusherException("An error occurred when sending push request with message: {$e->getMessage()}", $e->getCode(), $e);
    }
    return null;
  }

}


?>
