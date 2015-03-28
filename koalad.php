<?php
  require_once(__PROJECTROOT__."/libkoalaweb/koalasignd.php");
  class koalad {
    private $configured = false;
    private $socket = null;

    public function __construct($host) {
      $this->socket = fsockopen("tls://".$host, "3654");
      if (is_resource($this->socket))
        $this->configured = true;
      return $this->configured;
    }

    public function sendCommand($c, $d) {
      if ($this->configured == true) {
        if (is_string($c) && $c != null) {
          $payload = json_encode(array(
            "command" => $c,
            "data"    => $d
          ));
          $request = koalasignd::getRequest($payload);
          if (json_decode($request) != false) {
            fputs($this->socket, $request."\n");
            $data = null;
            while (!feof($this->socket))
              $data .= fgets($this->socket);
            $data = json_decode($data, true);
            if (is_array($data))
              return $data;
          }
        }
      }
      return false;
    }
  }
?>
