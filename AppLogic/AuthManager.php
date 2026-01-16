<?php
require_once __DIR__ . '/../Database/UserDB.php';

class AuthManager {
    private $userDB;
    private $currentSession = null;
    private $maxAttempts = 3;

    public function __construct($userDB, $maxAttempts = 3) {
        $this->userDB = $userDB;
        $this->maxAttempts = $maxAttempts;
    }

    public function getUserDB() {
        return $this->userDB;
    }

    public function setUserDB($userDB) {
        $this->userDB = $userDB;
    }

    public function getCurrentSession() {
        return $this->currentSession;
    }

    public function setCurrentSession($session) {
        $this->currentSession = $session;
    }

    public function getMaxAttempts() {
        return $this->maxAttempts;
    }

    public function setMaxAttempts($maxAttempts) {
        $this->maxAttempts = $maxAttempts;
    }

    public function authenticate($username, $password) {
        list($isValid, $role) = $this->userDB->validateUser($username, $password);
        
        if ($isValid) {
            $this->currentSession = [
                "username" => $username,
                "role" => $role,
                "login_time" => date("Y-m-d H:i:s")
            ];
            return [true, "Login successful"];
        }
        return [false, "Invalid credentials"];
    }

    public function logout() {
        $this->currentSession = null;
        return "Logged out";
    }

    public function isLoggedIn() {
        return $this->currentSession !== null;
    }
}
?>
