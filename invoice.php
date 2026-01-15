<?php
require_once 'config.php';

if (isset($_SESSION['user'])) {
    $authManager->setCurrentSession($_SESSION['user']);
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!$authManager->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['recent_invoices'])) {
    $_SESSION['recent_invoices'] = [];
}

$invoiceForm = new InvoiceForm();
$invoiceData = null;
$view = $_GET['view'] ?? 'dashboard'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_invoice_action'])) {
    $customer = ($_POST['customer_id'] === 'NEW') ? $_POST['new_customer_name'] : $_POST['customer_id'];
    $invoiceForm->setCustomerComboBox($customer);
    $invoiceForm->addItem($_POST['item_name'], (float)$_POST['item_price'], (int)$_POST['item_qty']);
    
    list($success, $msg, $invoiceId, $data) = $invoiceForm->createInvoice($invoiceManager);
    
    if ($success) {
        $currentCount = count($_SESSION['recent_invoices']) + 1;
        $formattedId = str_pad($currentCount, 4, '0', STR_PAD_LEFT);
        
        $newEntry = [
            'seq_id' => $formattedId,
            'customer' => $customer,
            'item_name' => $_POST['item_name'],
            'item_price' => $_POST['item_price'],
            'item_qty' => $_POST['item_qty'],
            'subtotal' => $data['subtotal'],
            'tax' => $data['tax'],
            'total' => $data['total'],
            'date' => date('Y-m-d'),
            'time' => date('H:i A')
        ];
        
        array_unshift($_SESSION['recent_invoices'], $newEntry);
        $invoiceData = $newEntry; 
        $view = 'result';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>SBAS - لوحة التحكم</title>
    <style>
        :root { --primary: #2c3e50; --accent: #3498db; --bg: #f5f6fa; --white: #ffffff; --success: #27ae60; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: var(--bg); margin: 0; color: #333; }
        .navbar { background: var(--primary); color: white; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 900px; margin: 2rem auto; padding: 0 20px; }
        
        .card { background: var(--white); border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); width: 100%; box-sizing: border-box; margin-bottom: 25px; }
        .flex-center { display: flex; flex-direction: column; align-items: center; }

        .btn { padding: 12px 25px; border-radius: 8px; cursor: pointer; text-decoration: none; font-weight: bold; border:none; transition: 0.3s; display: inline-block; }
        .btn-main { background: var(--accent); color: white; }
        .btn-main:hover { background: #2980b9; transform: translateY(-2px); }
        .btn-outline { background: transparent; border: 1px solid #ccc; color: #7f8c8d; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #fdfdfd; padding: 15px; text-align: right; color: var(--primary); border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #f1f1f1; }
        .id-badge { background: #e3f2fd; color: #1976d2; padding: 4px 10px; border-radius: 5px; font-weight: bold; font-family: monospace; }
        
        select, input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1rem; }
        .section-title { border-right: 5px solid var(--accent); padding-right: 15px; margin-bottom: 25px; text-align: right; font-weight: bold; }
        
        .invoice-box { border: 1px solid #eee; padding: 40px; background: #fff; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2 style="margin:0;">SBAS Accounting</h2>
        <a href="invoice.php?action=logout" style="color: #ffcccc; text-decoration:none; font-weight:bold;">خروج</a>
    </div>

    <div class="container">
        
        <?php if ($view === 'dashboard'): ?>
            <div class="flex-center">
                <div class="card" style="text-align: center;">
                    <h2 style="margin:0 0 10px 0;">لوحة التحكم</h2>
                    <a href="invoice.php?view=create" class="btn btn-main">➕ إنشاء فاتورة جديدة</a>
                </div>
                
                <div class="card">
                    <h3 class="section-title">الفواتير السابقة</h3>
                    <?php if (empty($_SESSION['recent_invoices'])): ?>
                        <p style="text-align:center; color:#ccc;">لا توجد سجلات.</p>
                    <?php else: ?>
                        <table>
                            <thead><tr><th>الرقم</th><th>العميل</th><th>التاريخ</th><th>الإجمالي</th></tr></thead>
                            <tbody>
                                <?php foreach ($_SESSION['recent_invoices'] as $inv): ?>
                                    <tr>
                                        <td><span class="id-badge">#<?php echo $inv['seq_id']; ?></span></td>
                                        <td><strong><?php echo htmlspecialchars($inv['customer']); ?></strong></td>
                                        <td><small><?php echo $inv['date']; ?></small></td>
                                        <td style="color:var(--success); font-weight:bold;">$<?php echo number_format($inv['total'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($view === 'create'): ?>
            <div class="flex-center">
                <div class="card" style="max-width: 600px;">
                    <h3 class="section-title">إصدار فاتورة جديدة</h3>
                    <form method="POST">
                        <input type="hidden" name="create_invoice_action" value="1">
                        <select name="customer_id" required onchange="this.value=='NEW'?document.getElementById('n').style.display='block':document.getElementById('n').style.display='none'">
                            <option value="">-- اختر العميل --</option>
                            <option value="شركة الخليج">شركة الخليج</option>
                            <option value="NEW">+ إضافة عميل جديد</option>
                        </select>
                        <div id="n" style="display:none;"><input type="text" name="new_customer_name" placeholder="اسم العميل الجديد"></div>
                        <input type="text" name="item_name" placeholder="اسم المنتج" required>
                        <div style="display:flex; gap:10px;">
                            <input type="number" step="0.01" name="item_price" placeholder="السعر" required>
                            <input type="number" name="item_qty" placeholder="الكمية" required>
                        </div>
                        <div style="text-align:center; margin-top:30px;">
                            <button type="submit" class="btn btn-main">إصدار وحفظ</button>
                            <a href="invoice.php" class="btn btn-outline" style="margin-right:10px;">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>

        <?php elseif ($view === 'result'): ?>
            <div class="flex-center">
                <div class="card invoice-box">
                    <div style="display:flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px;">
                        <h2 style="margin:0; color:var(--accent);">تفاصيل الفاتورة</h2>
                        <span class="id-badge">#<?php echo $invoiceData['seq_id']; ?></span>
                    </div>
                    <p><strong>العميل:</strong> <?php echo htmlspecialchars($invoiceData['customer']); ?></p>
                    <p><strong>التاريخ:</strong> <?php echo $invoiceData['date']; ?> | <?php echo $invoiceData['time']; ?></p>
                    <hr>
                    <table style="margin-bottom: 20px;">
                        <thead><tr><th>الوصف</th><th>السعر</th><th>الكمية</th><th>المجموع</th></tr></thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($invoiceData['item_name']); ?></td>
                                <td>$<?php echo number_format($invoiceData['item_price'], 2); ?></td>
                                <td><?php echo $invoiceData['item_qty']; ?></td>
                                <td>$<?php echo number_format($invoiceData['subtotal'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="text-align: left;">
                        <p>الضريبة (15%): $<?php echo number_format($invoiceData['tax'], 2); ?></p>
                        <h3 style="color:var(--success);">الإجمالي النهائي: $<?php echo number_format($invoiceData['total'], 2); ?></h3>
                    </div>
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="invoice.php" class="btn btn-main">العودة للشاشة الرئيسية</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>