<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка</title>
</head>
<body>
<h1>Здравствуйте, {{ $request->name }}!</h1>
<p>Ваша заявка была успешно создана.</p>
<p><strong>Сообщение:</strong> {{ $request->message }}</p>
<p>Вы получите уведомление, когда заявка будет обработана.</p>
<p>С уважением, <br> Ваша компания</p>
</body>
</html>
