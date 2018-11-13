<?=

define(TOKEN,'xxxxxxxxxx');

abstract class API {

  //TELEGRAM SERVER
  protected $apiUrl;
  //API URL SEGMENTS
  protected $host;
  //GETME SETTINGS
  protected $botToken;
  public $botId;
  public $botUsername;
  //GETUPDATES SETTINGS
  protected $updatesOffset = false;
  protected $updatesLimit = 30;
  protected $updatesTimeout = 10;
  protected $updatesAllowedUpdates

  public function __construct($token, $options=array()) {
    $this->botToken = $token;
    $this->apiUrl = "https://api.telegram.org/bot".$token;
  }

}

abstract class BotCore {
  


}

abstract class Connection {

  //CURL REQUEST VARIABLES
  protected $handle;
  protected $inited = false;
  //POLLING DELAY VALUES
  protected $lpDelay = 1;
  protected $netDelay = 1;
  //CONNECTION TIMEOUT VALUES
  protected $netTimeout = 10;
  protected $netConnectionTimeout = 5;

  public function apiRequest($method,$params=array(),$options=array()){
    $options += array(
      'http_method' => 'GET'
    );
    $getParams = urlencode();
  }

}

?>
