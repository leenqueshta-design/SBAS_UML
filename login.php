<?php
require_once 'config.php';
$loginForm = new LoginForm();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginForm->setUsernameField($_POST['username'] ?? '');
    $loginForm->setPasswordField($_POST['password'] ?? '');
    list($success, $msg) = $loginForm->submit($authManager);
    $message = $msg;
    if ($success) {
        header("Location: invoice.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>SBAS - تسجيل الدخول</title>
    <style>
        :root { --primary: #2c3e50; --accent: #3498db; --bg: #f5f6fa; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: var(--bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: var(--primary); margin-bottom: 25px; font-weight: 600; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1rem; }
        button { width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: 0.3s; margin-top: 10px; }
        button:hover { background: #2980b9; transform: translateY(-2px); }
        .error { color: #e74c3c; background: #fdf2f2; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="font-size: 3.5rem; margin-bottom: 15px;">🔒</div>
        <h2>تسجيل الدخول</h2>
        <?php if ($message) echo "<div class='error'>$message</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">دخول النظام</button>
        </form>
        <p style="color: #95a5a6; font-size: 0.8rem; margin-top: 20px;">نظام SBAS للمحاسبة الذكية</p>
    </div>
</body>
</html>