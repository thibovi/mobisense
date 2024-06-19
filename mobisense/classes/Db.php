<?php
class Db
{
    private static $db;

    public static function getInstance()
    {
        if (self::$db !== null) {
            return self::$db;
        } else {
            $dsn = 'mysql:host=localhost;dbname=mobisense';
            $username = 'root';
            $password = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$db = new PDO($dsn, $username, $password, $options);
                return self::$db;
            } catch (PDOException $e) {
                // Handle any connection errors here
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
    }
}
?>