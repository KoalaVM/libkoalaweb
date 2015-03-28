<?php
  require_once(dirname(__FILE__)."/koalasignd.php");
  class koalad {
    private $host = null;
    private $socket = null;

    public function __construct($host) {
      $this->host = "tls://".$host;
    }

    private function connect() {
      $sock = fsockopen($this->host, "3654");
      if (is_resource($sock)) {
        $this->socket = $sock;
        return true;
      }
      return false;
    }

    public function sendCommand($c, $d) {
      if (is_string($c) && $c != null) {
        $payload = json_encode(array(
          "command" => $c,
          "data"    => $d
        ));
        $request = koalasignd::getRequest($payload);
        if (json_decode($request) != false && $this->connect() == true) {
          fputs($this->socket, $request."\n");
          $time = time();
          $data = null;
          while (!feof($this->socket) && (time() - $time) < 5)
            $data .= fgets($this->socket);
          fclose($this->socket);
          $data = json_decode($data, true);
          if (is_array($data))
            return $data;
        }
      }
      return false;
    }
  }
?>
