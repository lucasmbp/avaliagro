<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Cargo.class.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$cargoObj = new Cargo($db);

$id = $_GET['id'] ?? null;
$modo_edicao = !empty($id);
$dados = ['cargo' => ''];
$mensagem = "";

if ($modo_edicao) {
    $dados = $cargoObj->buscarPorId($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cargo = trim($_POST['cargo']);

    // Valida duplicidade
    if (!$cargoObj->validar($nome_cargo, $id)) {
        $mensagem = "Erro: Este cargo já está cadastrado.";
    } else {
        if ($modo_edicao) {
            $cargoObj->atualizar($id, $nome_cargo);
            $mensagem = "Cargo atualizado com sucesso!";
        } else {
            $cargoObj->inserir($nome_cargo);
            $mensagem = "Cargo cadastrado com sucesso!";
        }
        header("Refresh: 2; url=index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manter Cargo - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-green-800 p-6 text-white">
            <h2 class="text-xl font-bold"><?php echo $modo_edicao ? 'Editar Cargo' : 'Novo Cargo'; ?></h2>
        </div>
        <form method="POST" class="p-8 space-y-6">
            <?php if ($mensagem): ?>
                <div class="p-3 rounded text-sm <?php echo strpos($mensagem, 'Erro') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-stone-700 font-semibold mb-2">Descrição do Cargo</label>
                <input type="text" name="cargo" value="<?php echo htmlspecialchars($dados['cargo']); ?>" required
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 rounded-xl transition">
                Salvar Cargo
            </button>
            <a href="index.php" class="block text-center text-stone-500 text-sm">Voltar</a>
        </form>
    </div>
</body>
</html>