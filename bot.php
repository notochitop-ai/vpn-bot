<?php
// تنظیمات
define('BOT_TOKEN', '8412613086:AAHPN2U1SJ0EqhN_6OCEUKD_Dz6X_Z8RN2I');
define('ADMIN_ID', '435719319');
define('DB_HOST', 'localhost');
define('DB_USER', 'achostnet_v2pro');
define('DB_PASS', 'qq6Gy2J2hzm8cmBpxYs8');
define('DB_NAME', 'achostnet_v2pro');
define('PANEL_HOST', '213.165.69.221');
define('PANEL_PORT', '98');
define('PANEL_USER', 'hosseinali');
define('PANEL_PASS', 'HosseinAli@687Oli@#$?');

require_once 'functions.php';
require_once 'database.php';

$input = file_get_contents('php://input');
$update = json_decode($input, true);

file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $input . "\n", FILE_APPEND);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $userId = $update['message']['from']['id'];
    $text = $update['message']['text'] ?? '';
    $firstName = $update['message']['from']['first_name'] ?? 'کاربر';
    
    if ($text == '/start') {
        if ($userId == ADMIN_ID) {
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '🌐 سرورها', 'callback_data' => 'servers'],
                        ['text' => '📦 سرویس‌ها', 'callback_data' => 'services']
                    ],
                    [
                        ['text' => '👥 کاربران', 'callback_data' => 'users'],
                        ['text' => '📊 آمار', 'callback_data' => 'stats']
                    ],
                    [
                        ['text' => '🔧 تست پنل', 'callback_data' => 'test_panel']
                    ]
                ]
            ];
            $message = "🤖 <b>پنل مدیریت</b>\n\nسلام $firstName!\n\nاز منوی زیر انتخاب کنید:";
        } else {
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => '🛍 خرید سرویس', 'callback_data' => 'buy']],
                    [
                        ['text' => '📱 سرویس‌های من', 'callback_data' => 'my'],
                        ['text' => '💰 کیف پول', 'callback_data' => 'wallet']
                    ],
                    [
                        ['text' => '📞 پشتیبانی', 'callback_data' => 'support'],
                        ['text' => '📚 آموزش', 'callback_data' => 'help']
                    ]
                ]
            ];
            $message = "🚀 <b>ربات VPN</b>\n\nسلام $firstName!\n\nاز منوی زیر انتخاب کنید:";
        }
        sendMessage($chatId, $message, $keyboard);
    }
} elseif (isset($update['callback_query'])) {
    $callbackId = $update['callback_query']['id'];
    $data = $update['callback_query']['data'];
    $chatId = $update['callback_query']['message']['chat']['id'];
    $messageId = $update['callback_query']['message']['message_id'];
    $userId = $update['callback_query']['from']['id'];
    
    answerCallback($callbackId);
    
    if ($data == 'test_panel' && $userId == ADMIN_ID) {
        require_once 'x_ui_api.php';
        $api = new XUIApi(PANEL_HOST, PANEL_PORT, PANEL_USER, PANEL_PASS);
        if ($api->testConnection()) {
            $text = "✅ اتصال به پنل برقرار شد!";
        } else {
            $text = "❌ خطا در اتصال به پنل!";
        }
        $keyboard = [
            'inline_keyboard' => [
                [['text' => '🔙 بازگشت', 'callback_data' => 'back']]
            ]
        ];
        editMessage($chatId, $messageId, $text, $keyboard);
    }
}
echo "OK";
?>
