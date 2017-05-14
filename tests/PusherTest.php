<?php

use acurrieclark\IonicPhpPusher\Pusher;

class PusherTest extends PHPUnit_Framework_TestCase {

  /**
  * @expectedException acurrieclark\IonicPhpPusher\Exception\TokenException
  */
  public function testInvalidToken() {
    $token = 'token';
    $pusher = new Pusher($token);
  }

  public function testTokenSet() {
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.TJVA95OrM7E2cBab30RMHrHDcEfxjoYZgeFONFh7HgQ';
    $pusher = new Pusher($token);
    $this->assertEquals($pusher->getAuthToken(), $token);
  }

  public function testTokenNotNull() {
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.TJVA95OrM7E2cBab30RMHrHDcEfxjoYZgeFONFh7HgQ';
    $pusher = new Pusher($token);
    $this->assertNotNull($pusher->getAuthToken());
    return $pusher;
  }


  /**
  * @expectedException acurrieclark\IonicPhpPusher\Exception\AuthException
  * @depends testTokenNotNull
  */
  public function testAuth(Pusher $pusher) {
    $pusher->sendToTokens(['device_token'], 'test', new stdClass);
  }

  /**
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @depends testTokenNotNull
  * @expectedExceptionCode 1
  */
  public function testDeviceTokensPresent(Pusher $pusher) {
    $pusher->sendToTokens([], 'test', new stdClass);
  }

  /**
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @depends testTokenNotNull
  * @expectedExceptionCode 2
  */
  public function testDeviceTokensIsArray(Pusher $pusher) {
    $pusher->sendToTokens('device_token', 'test', new stdClass);
  }

  /**
  * @depends testTokenNotNull
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @expectedExceptionCode 3
  */
  public function testProfilePresent(Pusher $pusher) {
    $pusher->sendToTokens(['device_token'], null, new stdClass);
  }

  /**
  * @depends testTokenNotNull
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @expectedExceptionCode 4
  */
  public function testProfileIsString(Pusher $pusher) {
    $pusher->sendToTokens(['device_token'], ['profile_name'], new stdClass);
  }

  /**
  * @depends testTokenNotNull
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @expectedExceptionCode 5
  */
  public function testNotificationDataPresent(Pusher $pusher) {
    $pusher->sendToTokens(['device_token'], 'profile_name', null);
  }

  /**
  * @depends testTokenNotNull
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @expectedExceptionCode 6
  */
  public function testNotificationDataIsObject(Pusher $pusher) {
    $pusher->sendToTokens(['device_token'], 'profile_name', ['key' => 'data']);
  }

  /**
  * @depends testTokenNotNull
  * @expectedException acurrieclark\IonicPhpPusher\Exception\PusherException
  * @expectedExceptionCode 401
  */
  public function testApiAccess(Pusher $pusher) {
    $pusher->testApiAccess();
  }

}

?>
