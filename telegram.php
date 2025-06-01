<?php
// Проверяем, что форма отправлена
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 403 Forbidden');
    exit('Доступ запрещен');
}

// Получаем и очищаем данные
$name = htmlspecialchars($_POST['name'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Проверяем обязательные поля
if (empty($name) || empty($email) || empty($phone)) {
    header('HTTP/1.1 400 Bad Request');
    exit('Заполните все обязательные поля');
}

// Формируем сообщение
$text = "📌 Новая заявка с лендинга:\n\n";
$text .= "👤 Имя: $name\n";
$text .= "📧 Email: $email\n";
$text .= "📞 Телефон: $phone\n";
$text .= "✉️ Сообщение: " . (!empty($message) ? $message : 'не указано');

// Настройки бота
$botToken = '7637419490:AAFYn0YWeJKbcig6Yp3V7AGz0PaPKeuhpdI';
$chatId = '7114452953';

try {
    // Отправка в Telegram
    $url = "https://api.telegram.org/bot{7637419490:AAFYn0YWeJKbcig6Yp3V7AGz0PaPKeuhpdI}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'Markdown'
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        throw new Exception('Ошибка отправки сообщения');
    }

    // Успешный ответ
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено!']);
    
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>