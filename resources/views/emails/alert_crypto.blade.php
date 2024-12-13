<!DOCTYPE html>
<html>
<head>
    <title>Alerta configurado com sucesso!</title>
</head>
<body>
    <h1>Olá, {{ $userName }}!</h1>
    <p>O alerta para a moeda <strong>{{ $coinName }}</strong> foi configurado, com o valor em: <strong>{{ $alertValue }}</strong>.</p>
    <p>Assim que percebermos que essa moeda atingiu o valor previamente configurado, vamos lhe enviar outro e-mail o mais rápido possível!</p>
    <p>Obrigado por usar nosso serviço!</p>
</body>
</html>
