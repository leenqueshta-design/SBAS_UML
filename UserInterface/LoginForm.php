<?php
require_once __DIR__ . '/../AppLogic/AuthManager.php';

class LoginForm {
    private $usernameField = "";
    private $passwordField = "";
    private $loginButton = "Login";
    private $errorLabel = "";

    public function getUsernameField() {
        return $this->usernameField;
    }

    public function setUsernameField($username) {
        $this->usernameField = htmlspecialchars($username);
    }

    public function getPasswordField() {
        return $this->passwordField;
    }

    public function setPasswordField($password) {
        $this->passwordField = $password;
    }

    public function getLoginButton() {
        return $this->loginButton;
    }

    public function setLoginButton($buttonText) {
        $this->loginButton = $buttonText;
    }

    public function getErrorLabel() {
        return $this->errorLabel;
    }

    public function setErrorLabel($error) {
        $this->errorLabel = $error;
    }

    public function submit($authManager) {
        list($success, $message) = $authManager->authenticate(
            $this->usernameField,
            $this->passwordField
        );

        if ($success) {
            $this->errorLabel = "";
            $_SESSION['user'] = $authManager->getCurrentSession();
            return [true, $message];
        } else {
            $this->errorLabel = $message;
            return [false, $message];
        }
    }

    public function render() {
        $html = '<div class="login-form">';
        $html .= '<h2>SBAS Login</h2>';
        
        if ($this->errorLabel) {
            $html .= '<div class="error">' . $this->errorLabel . '</div>';
        }
        
        $html .= '<form method="POST">';
        $html .= '<input type="text" name="username" placeholder="Username" value="' . $this->usernameField . '" required>';
        $html .= '<input type="password" name="password" placeholder="Password" required>';
        $html .= '<button type="submit">' . $this->loginButton . '</button>';
        $html .= '</form>';
        $html .= '</div>';
        
        return $html;
    }
}
?>
