<?php

//CLASSI

class DB {

  var $credentials;
  var $connection;

  /**
  * Costruttore
  * @param array $db_credentials Parametri di accesso al database
  */
  public function __construct($db_credentials) {
    $this->credentials = "host=$db_credentials[host] "
                        ."port=$db_credentials[port] "
                        ."dbname=$db_credentials[database] "
                        ."user=$db_credentials[user] "
                        ."password=$db_credentials[password]"
                        ;
  }

  /**
  * Stabilisce la connessione al database
  * @return resource $connection Connessione aperta con il database
  */
  public function connect() {
    $this->connection = pg_connect($this->credentials);//or die("unableto open db")
    return $this->connection;
  }


  /**
  * Invia una generica query al database
  * @param string $query Stringa SQL da eseguire
  * @return resource $result Il risultato della query (FALSE in caso di insuccesso)
  */
  public function query($query) {
    $result = pg_query($this->connection,$query);
    return $result;
  }


  /**
  * Inserisce i dati in una tabella del database
  * @param string $table Tabella in cui inserire i dati
  * @param array $data Dati da inserire
  * @return bool $result Esito dell'operazione
  */
  public function insert($table, array $data) {
    $result = pg_insert($this->connection, $table, $data);
    return $result;
  }

}


//ISTANZE

$db = new DB ($db_credentials);
$db->connect();
echo (is_resource($db->connection)?"Connesso al database\n":"Errore di connessione al database\n");


$utente = [
  'id'         =>  "174497151",
  'first_name' =>  "Roberto",
  'last_name'  =>  "Frattini",
  'username'   =>  "@donkeykosh"
];

$db->insert('users',$utente);

$res = $db->query("SELECT * FROM users");
echo "$res\n";
$name = pg_fetch_result($res,0,'first_name');
echo "$name\n";
