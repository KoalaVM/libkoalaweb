<?php
  class koalasignd {
    private static $socket = null;

    private static function connect() {
      self::$socket = fsockopen("tls://127.0.0.1", "4765");
      if (is_resource(self::$socket)) {
        return true;
      }
      return false;
    }

    public static function getRequest($payload) {
      if (self::connect() && $payload != null) {
        fputs(self::$socket, $payload."\n");
        $time = time();
        $data = null;
        while (!feof(self::$socket) && (time() - $time) < 5)
          $data .= fgets(self::$socket);
        $data = json_decode($data, true);
        if (is_array($data) && isset($data["status"]) && isset($data["data"]) &&
            $data["status"] == "200") {
          return $data["data"];
        }
      }
      return json_encode(false);
    }
  }
?>
