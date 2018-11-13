<?=



class Redis extends TelegramBot {

///INITIALIZE
public $redis = false;

///CONNECTION TO REDIS
private function connect() {
  if (getenv('REDIS_URL')) {
    if (!$this->redis) {
      $this->$redis = new Predis\Client(getenv('REDIS_URL'));
    }
  }
  else {
    throw new Exception("No REDIS_URL found");
  }
}

///CHECK SERVER AND CONNECT TO REDIS
private function init() {
  parent::init();
  $this->redisInit();
}

private function write($key,$value) {
  //store key and value
}

private function remove($key){
  //remove a key/value
}

private function read($key){
  //read a value, given the key
}

?>
