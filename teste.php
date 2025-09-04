<?php
// Configuração das chaves da Marvel
$publicKey = "6206dc26c0f4a750fad2d3500b1ef664";
$privateKey = "fa8424d82a09585cb55a5434e5b9a595a4be2eba";

$heroData = null;
$error = null;

// Se o usuário pesquisou
if (isset($_GET["hero"]) && !empty($_GET["hero"])) {
    $heroName = urlencode($_GET["hero"]);
    $ts = time();
    $hash = md5($ts . $privateKey . $publicKey);

    $url = "https://gateway.marvel.com/v1/public/characters?nameStartsWith={$heroName}&ts={$ts}&apikey={$publicKey}&hash={$hash}";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        $error = "Erro ao acessar a API da Marvel.";
    } else {
        $data = json_decode($response, true);
        if (isset($data["data"]["results"][0])) {
            $heroData = $data["data"]["results"][0];
        } else {
            $error = "Herói não encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enciclopédia de Heróis da Marvel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a1a 0%, #2a0e0e 100%);
      color: #fff;
      padding: 20px;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      text-align: center;
    }
    h1 { color: #e62429; margin-bottom: 20px; }
    form { margin: 20px 0; }
    input {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #e62429;
      width: 250px;
    }
    button {
      padding: 10px 20px;
      border: none;
      background: #e62429;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }
    .hero-card {
      margin: 20px auto;
      background: #202020;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      text-align: left;
    }
    .hero-card img {
      max-width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .error {
      color: #ff6b6b;
      margin: 20px 0;
    }
  </style>
</head>
<body>
  <h1>Enciclopédia de Heróis da Marvel</h1>
  <p>Digite o nome do herói para pesquisar:</p>
  <form method="GET">
    <input type="text" name="hero" placeholder="Ex: Spider-Man, Hulk" value="<?= isset($_GET["hero"]) ? htmlspecialchars($_GET["hero"]) : "" ?>">
    <button type="submit">Pesquisar</button>
  </form>

  <?php if ($error): ?>
    <div class="error"><?= $error ?></div>
  <?php elseif ($heroData): ?>
    <div class="hero-card">
      <img src="<?= $heroData["thumbnail"]["path"] . "." . $heroData["thumbnail"]["extension"] ?>" alt="<?= $heroData["name"] ?>">
      <h2><?= $heroData["name"] ?></h2>
      <p><?= !empty($heroData["description"]) ? $heroData["description"] : "Nenhuma descrição disponível." ?></p>
      <p><strong>Quadrinhos:</strong> <?= $heroData["comics"]["available"] ?>+</p>
      <p><strong>Séries:</strong> <?= $heroData["series"]["available"] ?>+</p>
      <p><strong>Eventos:</strong> <?= $heroData["events"]["available"] ?>+</p>
      <p><strong>Histórias:</strong> <?= $heroData["stories"]["available"] ?>+</p>
    </div>
  <?php endif; ?>

  <footer style="margin-top:40px; color:#777; font-size:0.9rem;">
    Dados fornecidos pela Marvel. © 2024 MARVEL <br>
    Este site é apenas para fins educacionais.
  </footer>
</body>
</html>