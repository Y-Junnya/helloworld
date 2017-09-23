<?php
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

foreach ($events as $event) {
  
  //$bot->replyText($event->getReplyToken(), 'TextMessage');
  
  //テキスト返信
  //replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');
  
  //画像返信
  //replyImageMessage($bot, $event->getReplyToken(), 'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg', 'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');
  
  //位置情報を返信
  //replyLocationMessage($bot,$event->getReplyToken(),'LINE','東京都渋谷区渋谷2-21-1 ヒカリエ27階','35.659025','139.703473');
  
  //ユーザーのプロフィールを取得しメッセージを作成後返信
  $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
  
  $bot->replyMessage($event->getReplyToken(),
  	(new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder())
  		->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('現在のプロフィールです。'))
  		->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('表示名：'.$profile['displayName']))
  		->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('画像URL：'.$profile['pictureUrl']))
  		->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('ステータスメッセージ：'.$profile['statusMessage']))
  		->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('userID：'.$profile['userId']))
  );
  error_log($profile['displayName']);
  error_log($profile['pictureUrl']);
  
  }

function replyTextMessage($bot, $replyToken, $text) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));
  if (!$response->isSucceeded()) {
    error_log('Failed! '. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    error_log($title.$address.$lat.$lon);
  }
}


$inputString = file_get_contents('php://input');
error_log($inputString);

?>