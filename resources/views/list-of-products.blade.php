<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <?php foreach ($products as $prod): ?>
    <div>
      <p>{{ $prod->name }}</p>
    </div>
  <?php endforeach; ?>
</body>
</html>
<h1>Главная страница<h1>
