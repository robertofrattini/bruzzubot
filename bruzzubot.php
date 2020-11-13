<?php

define('BOT_TOKEN', '226626117:AAF_LAq1uNAEpQs_tDU92QysXgP65kGjJcY');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('BOT_NAME', 'bruzzubot');
define('BRUZZU_ID', '-1001089994455');

include('API.php');
include('./src/gif.php');
include('./src/pic.php');
include('./src/audio.php');
include('./src/fanpic.php');

function search($needle,$haystack) {
  foreach ($haystack as $title => $fileId) {
    if (stripos($title,$needle)!==false) {
      $searchResult[] = $fileId;
    }
  }
  return $searchResult;
}
function mention($user) {
  $mention = isset($user['first_name'])?$user['first_name']:'';
  $mention = isset($user['username'])?$user['username']:'';
  $mention = isset($user['last_name'])?$user['last_name']:'';
  return $mention;
}

$update = json_decode(file_get_contents('php://input'), true);
  $message = isset($update['message'])?$update['message']:'';
  $inlineQuery = isset($update['inline_query'])?$update['inline_query']:'';
  $callbackQuery = isset($update['callback_query'])?$update['callback_query']:'';


//INLINE MODE
if ($inlineQuery) {

  $userId = isset($inlineQuery['from']['id'])?$inlineQuery['from']['id']:'';
  $queryId = isset($inlineQuery['id'])?$inlineQuery['id']:'';
  $queryText = isset($inlineQuery['query'])?$inlineQuery['query']:'';

  $method = 'answerInlineQuery';

  switch ($queryText) {
    case 'gif':
      $fileId = array_values($gif);
      for ($i=0; $i < count($fileId); $i++) {
        $queryResult[] = [
          'type' => "mpeg4_gif",
          'id' => "$i",
          'mpeg4_file_id' => "$fileId[$i]",
          ];
      }
      break;
    case 'pic':
      $fileId = array_values($pic);
      for ($i=0; $i < 50; $i++) {
        $queryResult[] = [
          'type' => "photo",
          'id' => "$i",
          'photo_file_id' => "$fileId[$i]",
          ];
      }
      break;
    case 'pic2':
      $fileId = array_values($pic);
      for ($i=50; $i < count($fileId); $i++) {
        $queryResult[] = [
          'type' => "photo",
          'id' => "$i",
          'photo_file_id' => "$fileId[$i]",
          ];
      }
      break;
    case 'audio':
      $fileId = array_values($audio);
      for ($i=0; $i < count($audio); $i++) {
        $queryResult[] = [
          'type' => "audio",
          'id' => "$i",
          'audio_file_id' => "$fileId[$i]",
          ];
      }
      break;
    default:
      $fileId = array_values(search($queryText,$pic));
      for ($i=0; $i < count($fileId); $i++) {
        $queryResult[] = [
          'type' => "photo",
          'id' => "$i",
          'photo_file_id' => "$fileId[$i]",
          ];
      }
      break;
  }

  $parameters = [
    'inline_query_id' => $queryId,
    'results'=> $queryResult,
    'cache_time' => 60,
    ];

}


//CALLBACK MODE
elseif ($callbackQuery){

  $userId = isset($callbackQuery['from']['id'])?$callbackQuery['from']['id']:'';
    $queryId = isset($callbackQuery['id'])?$callbackQuery['id']:'';
    $queryMessage = isset($callbackQuery['message'])?$callbackQuery['message']:'';
    $queryInlineId = isset($callbackQuery['inline_message_id'])?$callbackQuery['inline_message_id']:'';
    $queryData = isset($callbackQuery['data'])?$callbackQuery['data']:'';

  if ($queryMessage) {

    $chatId = isset($queryMessage['chat']['id'])?$queryMessage['chat']['id']:'';
    $messageId = isset($queryMessage['message_id'])?$queryMessage['message_id']:'';

    $method = 'answerCallbackQuery';

    switch ($queryData) {
      case 'guida':
        $parameters = [
          'callback_query_id' => $queryId,
          'text' => "In qualsiasi chat, digitare @bruzzubot seguito da uno dei comandi disponibili:\n\ngif\npic\npic2\naudio\n<altro> (ricerca immagini)\n\nThat's all folks!",
          'show_alert' => true,
          ];
        break;
    }
  }

}


//MESSAGE MODE
elseif ($message) {
  $updateId = isset($update['update_id'])?$update['update_id']:'';
    $date = date("d-m-y", $message['date']);
    $userId = isset($message['from']['id'])?$message['from']['id']:'';
    $chatId = isset($message['chat']['id'])?$message['chat']['id']:'';
    $chatTitle = isset($message['chat']['title'])?$message['chat']['title']:'';
    $text = isset($message['text'])?$message['text']:'';
    $string = str_replace(" ","",preg_replace("/[^A-Za-z0-9 ]/",' ', strtolower($text)));
    $newParticipant = isset($message['new_chat_participant'])?$message['new_chat_participant']:'';
    $replyToMessage = isset($message['reply_to_message'])?$message['reply_to_message']:'';

  $method = 'sendMessage';
  $parameters = [];

  if ($newParticipant&&$newParticipant!=BOT_NAME) {
    $parameters = [
      'chat_id' => $chatId,
      'text' => "Benvenuto nel Bruzzu ".mention($newParticipant)."!",
      'parse_mode' => "HTML",
      ];
  }
  if ($text=="/start"||$text=="/start@".BOT_NAME||$text=="/menu"||$text=="/menu@".BOT_NAME) {
    $keyboard=[[['text' => "Statuto del Bruzzu", 'url' => "https://drive.google.com/file/d/1ScxQ_2Pns96Q8t5R4xygGVU9wQUal26F/view?usp=sharing"]],[['text' => "Sacro Registro del Bruzzu", 'url' => "https://docs.google.com/spreadsheets/d/1UT_DBIlCCJbtE_3yDjaagYIJ_i2AeJCtEjVw0ennO_o/edit?usp=sharing"]],[['text' => "Guida comandi", 'callback_data' => "guida"]]];
    $markup = ['inline_keyboard' => $keyboard];
    $parameters = [
      'chat_id' => $chatId,
      'text' => "___________________________\n\n<b>\xF0\x9F\x8C\x80    BRUZZU MASTER    \xF0\x9F\x8C\x80</b>\n___________________________\n\n<i>- Il Bot ufficiale del Bruzzu -</i>\n\n\nConsulta il saggio Re Elrond:\n\n📜 /statuto\n📚 /registro\n👑 /redelbruzzu\n🗣 /mottodelmese\n👊 /fuoridalbruzzu\n\n/sondaggio\n/richiesta\n\n\nUn giorno il Bruzzu sarà un gruppo serio. Ma non è questo il giorno!",
      'parse_mode' => "HTML",
      'reply_markup' => $markup,
      ];
  }
  elseif ($text=="/sondaggio"||$text=="/sondaggio@".BOT_NAME) {
    $parameters = [
      'chat_id' => $chatId,
      'text' => "Questa funzione non è ancora disponibile",
      ];
  }
  elseif ($text=="/richiesta"||$text=="/richiesta@".BOT_NAME) {
    $forceReply = [
      'force_reply' => true,
      'selective' => true,
      ];
    $parameters = [
      'chat_id' => $chatId,
      'text' => "Inviami un messaggio da inoltrare al Consiglio del Bruzzu, oppure /annulla",
      'reply_markup' => $forceReply,
      ];
  }
  elseif (strpos($string,'fuoridalbruzzu')!==false) {
    $fuoridalbruzzu = array_values($fuoridalbruzzu);
    if (strpos($date,"25-12")!==false) {
      $parameters = [
        'chat_id' => $chatId,
        'document' => $gif['Fuori dal Bruzzu Christmas Edition'],
      ];
    }
  	else {
      $parameters = [
    		'chat_id' => $chatId,
    		'document' => $fuoridalbruzzu[rand(0,count($fuoridalbruzzu)-1)],
    		];
  	}
  	$method = 'sendDocument';
  }

  elseif ($replyToMessage) {
    if ($replyToMessage['text']=="Inviami un messaggio da inoltrare al Consiglio del Bruzzu, oppure /annulla"&&$chatId>0) {
      $parameters = [
        'chat_id' => BRUZZU_ID,
        'from_chat_id' => $chatId,
        'message_id' => $messageId,
        ];
      $method = 'forwardMessage';
    }
    //elseif ($replyToMessage['text']=="") {CHIEDE ULTERIORI INFORMAZIONI (TASTIERA X CLASSIFICA - SONDAGGIO NORMALE ecc.)}
  }

  $textCommand = [
    "Сквозь грозы сияло нам солнце свободы" => "mottodelmese",
    "Cavalieri di Rohan, salutate Re <b>Inno</b>!" => "redelbruzzu",
    "<a href=\"https://drive.google.com/file/d/1ScxQ_2Pns96Q8t5R4xygGVU9wQUal26F/view?usp=sharing\">Leggi lo statuto</a>" => "/statuto",
    "<a href=\"https://docs.google.com/spreadsheets/d/1UT_DBIlCCJbtE_3yDjaagYIJ_i2AeJCtEjVw0ennO_o/edit?usp=sharing\">Leggi lo statuto</a>" => "/registro",
    ];
    foreach ($textCommand as $reply => $trigger) {
      if (strpos($string,$trigger)!==false) {
        error_log("Found".$trigger."=>".$trigger);
        $parameters = [
          'chat_id' => $chatId,
          'text' => $reply,
          'parse_mode' => "HTML",
          ];
        $method = "sendMessage";
      }
    }
  $kapsisTrigger = [
    "madrina",
    "kapsis",
    ];
    foreach ($kapsisTrigger as $picTrigger) {
        if (strpos($string,$picTrigger)!==false) {
          $parameters = [
            'chat_id' => $chatId,
            'photo' => $kapsis[rand(0,count($kapsis)-1)],
            ];
          $method = 'sendPhoto';
        }
      }
    }
  $picCommand = [
    ];
    foreach ($picCommand as $picTitle => $picTriggerList) {
      foreach ($picTriggerList as $picTrigger) {
        if (strpos($string,$picTrigger)!==false) {
          $parameters = [
            'chat_id' => $chatId,
            'photo' => $pic[$gifTitle],
            ];
          $method = 'sendPhoto';
        }
      }
    }
  $gifCommand = [
    "Fuori dal Nonno!" => ["fuoridalnonno","fuori dal nonno"],
    "Fuori dal Bruzzu Christmas Edition" => ["fuori dal natale","fuoridalnatale"],
    "Ordine e metodo ragazzi!" => ["ordineemetodo","ordine e metodo"],
    "NiroSuspect" => ["sospetto"],
    "Rifaccia il corso v2" => ["ho sbagliato tutto"],
    "Dr. Galliani" => [" è perfetto "],
    "LugaXperiment" => [" lugarini "],
    "Questa è benzina" => ["questaèbenzina","questa è benzina"],
    "Autogol 1" => ["autogol"],
    "Nonsièsteso v1" => ["nonsièsteso","non si è steso"],
    "Non si è Seveso v1" => ["nonsièseveso","non si è seveso","perso il treno"],
    "Mii baasta" => ["non ce la faccio più"," mii ","non ne posso"],
    "Petalòso" => ["petaloso"],
    ];
    foreach ($gifCommand as $gifTitle => $gifTriggerList) {
      foreach ($gifTriggerList as $gifTrigger) {
        if (strpos($string,$gifTrigger)!==false) {
          $parameters = [
            'chat_id' => $chatId,
            'document' => $gif[$gifTitle],
            ];
          $method = 'sendDocument';
        }
      }
    }
  $audioCommand = [
    "Carpentiere Estone" => "carpentiereestone",
    "Zozzo" => "zozzo",
    ];
    foreach ($audioCommand as $audioTitle => $audioTrigger) {
      if (strpos($string,$audioTrigger)!==false) {
      $parameters = [
        'chat_id' => $chatId,
        'audio' => $audio[$audioTitle],
        ];
      $method = 'sendAudio';
      }

}


//Send request to Telegram API server
apiRequestJson($method, $parameters);
