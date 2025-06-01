const fetch = require('node-fetch');

exports.handler = async (event) => {
  try {
    // Проверяем метод запроса
    if (event.httpMethod !== 'POST') {
      return { statusCode: 405, body: 'Method Not Allowed' };
    }

    // Парсим данные формы
    const { name, email, phone, consent } = JSON.parse(event.body);
    
    // Проверяем согласие
    if (!consent) {
      return { statusCode: 400, body: JSON.stringify({ error: 'Не получено согласие' }) };
    }

    // Формируем сообщение для Telegram
    const text = `📌 Новая заявка:\nИмя: ${name}\nEmail: ${email}\nТелефон: ${phone}`;

    // Отправляем в Telegram (используем ваш токен и chat_id)
    const telegramResponse = await fetch('https://api.telegram.org/bot7637419490:AAFYn0YWeJKbcig6Yp3V7AGz0PaPKeuhpdI/sendMessage', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        chat_id: '7114452953',
        text: text,
        parse_mode: 'Markdown'
      })
    });

    if (!telegramResponse.ok) {
      throw new Error('Ошибка Telegram API: ' + await telegramResponse.text());
    }

    return {
      statusCode: 200,
      body: JSON.stringify({ status: 'success' })
    };

  } catch (error) {
    return {
      statusCode: 500,
      body: JSON.stringify({ 
        status: 'error',
        message: error.message 
      })
    };
  }
};
