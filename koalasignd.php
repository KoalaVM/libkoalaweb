<?php
  class koalasignd {
    private static $socket = null;

    private static function connect() {
      $sock = fsockopen("tls://127.0.0.1", "4765");
      if (is_resource($sock)) {
        self::$socket = $sock;
        return true;
      }
      return false;
    }

    public static function getRequest($payload) {
      if ($payload != null && self::connect() == true) {
        fputs(self::$socket, $payload."\n");
        $time = time();
        $data = null;
        while (!feof(self::$socket) && (time() - $time) < 5)
          $data .= fgets(self::$socket);
        fclose(self::$socket);
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
