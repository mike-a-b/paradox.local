<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoinmarketcapApi;

class TestController extends Controller
{
  public function index()
  {    

    //Add
    $a = 0.1; $b = 0.7; $c = intval(($a + $b) * 10);  echo $c."<br>"; // Output: 7
    //Minus
    $a = 100; $b = 99.98; $c = $a - $b;  echo $c."<br>"; // Output: 0.01999999996
    //Ride
    $a = 0.58; $b = 100; $c = intval($a * $b);  echo $c."<br>"; // Output: 57
    //Except
    $a = 0.7; $b = 0.1; $c = intval($a / $b);  echo $c."<br><br><br>"; // Output: 6

    $a = 0.1; $b = 0.7; $c = intval(bcadd($a, $b, 1) * 10);  echo $c."<br>"; // Output: 8
     //Minus
    $a = 100; $b = 99.98; $c = bcsub($a, $b, 2);  echo $c."<br>"; // Output: 0.02
        //Ride
    $a = 0.58; $b = 100; $c = (bcmul($a, $b));  echo $c."<br>"; // Output: 58
        //Except
    $a = 0.7; $b = 0.1; $c = (bcdiv($a, $b));  echo $c."<br>"; // Output: 7

    exit;
    // $cmc = new CoinmarketcapApi();
    // $list = $cmc->getCryptoCurrencyInfo(['symbol' => 'btc,eth']);
    // dd($list);
    //return view('test');
  }
  // public function index()
  // {
  //     $telegramBot = new TelegramBot();
  //     $telegramBot->init('5005757922:AAHL5-Mnq1v8YDLSA65Mu8TtaZAPqGnMBEg');
  //     $resp = $telegramBot->sendMessage('Hello Димас!!!', 459165436);
  //     dd($resp);
  //     //return view('test');
  // }
}

function processMessage($message)
{
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true
      )));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}


//$content = file_get_contents("php://input");
//$update = json_decode($content, true);
