<?php

if ($authManager->isLoggedIn()) {
    header("Location: invoice.php");
} else {
    header("Location: login.php");
}
exit();
?>