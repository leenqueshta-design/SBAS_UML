<?php
class UserDB {
    private $connection;
    private $userCache = [];
    private $tableName = "users";

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function setConnection($connection) {
        $this->connection = $connection;
    }

    public function getUserCache() {
        return $this->userCache;
    }

    public function setUserCache($userCache) {
        $this->userCache = $userCache;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getUser($username) {
        $users = [
            "admin" => ["password" => password_hash("admin123", PASSWORD_DEFAULT), "role" => "admin"],
            "user1" => ["password" => password_hash("pass123", PASSWORD_DEFAULT), "role" => "user"]
        ];
        
        return isset($users[$username]) ? $users[$username] : null;
    }

    public function validateUser($username, $password) {
        $user = $this->getUser($username);
        if ($user && password_verify($password, $user["password"])) {
            return [true, $user["role"]];
        }
        return [false, null];
    }
}

?>
