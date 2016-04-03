# Ionic Push Beta PHP SDK

This package is a basic PHP SDK to assist with the sending of push notifications using the Ionic Push Beta API.

## Usage

``` php
// include autoloader if you haven't already
include_once('vendor/autoload.php');

use acurrieclark\IonicPhpPusher\Pusher;
use acurrieclark\IonicPhpPusher\PusherData;

// create an instance
$pusher = new Pusher(API_JWT_TOKEN);

// set an array of device tokens to push to. Can be a mix of Android and iOS devices
$device_tokens = ["android_token", "ios_token"];

// set which Ionic push profile you are using
$profile = IONIC_PUSH_PROFILE_NAME;

// the PusherData helper class provides a blank object to populate with notification data
// it is provided for convenience. Feel free to create your own object here
$notification = new PusherData();


$notification->title = "Message Title";
$notification->message = "Message Body";
$notification->ios->sound = "Default.caf";
$notification->ios->badge = 2;
$notification->android->title = "Android Title";
$notification->android->message = "Android Message";
$notification->android->data->style = 'inbox';
$notification->android->data->summaryText = "There are %n% updates";
$notification->android->sound = "default_alert";

try {

  // API endpoint to check you are using a valid token. This is not necessary before pushing
  $pusher->testApiAccess();

  // send your notification and output its response
  $response = $pusher->sendToTokens($tokens, $profile, $notification);
  print_r($response);

} catch (PusherException $e) {
  // any exceptions can be caught and handled here
  echo $e->getType().' - '.$e->getCode() .": ".$e->getMessage() . "\n";

  if ($e->hasResponse()) {
    print_r($e->getResponse());
  }
  else echo "No response\n";
}
```

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

## Thanks

Builds on the work for the Ionic Alpha API done by [Vladimir Dmitrovskiy](https://github.com/dmitrovskiy "Vladimir Dmitrovskiy")
