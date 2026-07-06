<?php
session_start();
include "config/conecta.php"; // $conexao

// Se já escolheu, mantém
if (!isset($_SESSION['plano_id'])) {
    $_SESSION['plano_id'] = null;
}

$planos = [];
$sql = "SELECT id, nome, valor FROM planos ORDER BY id";
$res = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $planos[] = $row;
}

$erro = isset($_GET['erro']) ? $_GET['erro'] : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Planos - Cia Dalan Maxádo</title>
    <style>
        body { font-family: Arial, sans-serif; background:#8b2525; margin:0; }
        .container { max-width: 980px; margin:0 auto; padding: 30px 15px; color:white; }
        .grid { display:flex; gap:20px; justify-content:center; flex-wrap:wrap; margin-top:20px; }
        .card { background:white; color:black; width: 260px; padding: 20px; border-radius: 16px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,.25); }
        .btn { display:inline-block; margin-top:12px; background:#8b2525; color:white; padding:10px 15px; border-radius:10px; text-decoration:none; font-weight:bold; border:0; cursor:pointer; }
        .btn-secondary { background:#333; }
        .topbar { text-align:center; }
        .msg { background: rgba(0,0,0,.2); padding:10px 15px; border-radius:10px; margin-top:15px; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1>Escolha seu Plano</h1>
        <p>Agora selecione um plano para continuar.</p>
        <?php if ($erro): ?>
            <div class="msg">⚠️ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
    </div>

    <div class="grid">
        <?php foreach ($planos as $p): ?>
            <div class="card">
                <h2 style="margin-top:0;"><?= htmlspecialchars($p['nome']) ?></h2>
                <p style="font-size:22px; font-weight:bold;">R$ <?= number_format((float)$p['valor'], 2, ',', '.') ?></p>

                <form method="POST" action="modalidades.php" style="margin-top:10px;">
                    <input type="hidden" name="plano_id" value="<?= (int)$p['id'] ?>">
                    <button class="btn" type="submit">Selecionar Plano</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:25px;">
        <a class="btn btn-secondary" href="index.php">← Voltar</a>
    </div>
</div>

<?php
// Se vier POST direto daqui, salva e redireciona (opcional)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plano_id'])) {
    $_SESSION['plano_id'] = (int)$_POST['plano_id'];
    // A submissão normalmente já vai pra modalidades.php, mas isso garante consistência:
    header("Location:modalidades.php");
    exit;
}
?>
</body>
</html>