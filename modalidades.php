<?php
session_start();
include "config/conecta.php";

// Recebe plano vindo de planos.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plano_id'])) {
    $_SESSION['plano_id'] = (int)$_POST['plano_id'];
}

if (!isset($_SESSION['plano_id']) || empty($_SESSION['plano_id'])) {
    header("Location:planos.php?erro=Selecione+um+plano+para+continuar.");
    exit;
}

// Carrega modalidades
$modalidades = [];
$sql = "SELECT id, nome FROM modalidades ORDER BY id";
$res = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $modalidades[] = $row;
}

$erro = isset($_GET['erro']) ? $_GET['erro'] : null;

// Processa seleção e vai para cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modalidades'])) {
    $ids = $_POST['modalidades'];

    // Validação: precisa pelo menos 1 modalidade
    if (!is_array($ids) || count($ids) < 1) {
        header("Location:modalidades.php?erro=Selecione+ao+menos+1+modalidade.");
        exit;
    }

    // Sanitiza
    $ids = array_map('intval', $ids);
    $_SESSION['modalidade_ids'] = $ids;

    header("Location:cadastro.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Modalidades - Cia Dalan Maxádo</title>
    <style>
        body { font-family: Arial, sans-serif; background:#8b2525; margin:0; }
        .container { max-width: 980px; margin:0 auto; padding: 30px 15px; color:white; }
        .grid { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; margin-top:20px; }
        .modal-card { background:white; color:black; width: 190px; padding: 15px; border-radius: 16px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,.25); }
        .modal-card label { cursor:pointer; display:block; }
        .modal-card input { margin-right:8px; }
        .btn { display:inline-block; background:#8b2525; color:white; padding:12px 18px; border-radius:10px; text-decoration:none; font-weight:bold; border:0; cursor:pointer; margin-top:18px; }
        .btn-secondary { background:#333; }
        .row-actions { text-align:center; margin-top:25px; }
        .msg { background: rgba(0,0,0,.2); padding:10px 15px; border-radius:10px; margin-top:15px; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <div style="text-align:center;">
        <h1>Escolha as Modalidades</h1>
        <p>Você pode escolher <strong>mais de uma</strong>.</p>
        <?php if ($erro): ?>
            <div class="msg">⚠️ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
    </div>

    <form method="POST" action="modalidades.php" style="margin-top:10px;">
        <input type="hidden" name="plano_id" value="<?= (int)$_SESSION['plano_id'] ?>">

        <div class="grid">
            <?php foreach ($modalidades as $m): ?>
                <div class="modal-card">
                    <label>
                        <input type="checkbox" name="modalidades[]" value="<?= (int)$m['id'] ?>">
                        <strong><?= htmlspecialchars($m['nome']) ?></strong>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row-actions">
            <button type="submit" class="btn">Continuar para Cadastro ✅</button>
            <div style="margin-top:10px;">
                <a class="btn btn-secondary" href="planos.php">← Voltar para Planos</a>
            </div>
        </div>
    </form>

</div>
</body>
</html>