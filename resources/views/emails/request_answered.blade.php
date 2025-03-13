<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ответ на вашу заявку</title>
</head>
<body>
<h1>Здравствуйте, {{ $request->name }}!</h1>
<p>Ваш запрос был обработан, и мы оставили комментарий:</p>
<p><strong>Комментарий:</strong> {{ $request->comment }}</p>
<p>Ваш запрос теперь в статусе: <strong>{{ $request->status }}</strong></p>
<p>С уважением, <br> Ваша компания</p>
</body>
</html>
