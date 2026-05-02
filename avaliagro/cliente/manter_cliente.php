<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Cliente.class.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$clienteObj = new Cliente($db);

$id = $_GET['id'] ?? null;
$modo_edicao = !empty($id);
$dados = $modo_edicao ? $clienteObj->buscarPorId($id) : ['nome' => '', 'cnpj' => '', 'responsavel' => ''];
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cnpj = trim($_POST['cnpj']);
    $resp = trim($_POST['responsavel']);

    if (!$clienteObj->validarCNPJ($cnpj, $id)) {
        $mensagem = "Erro: Este CNPJ já está cadastrado.";
    } else {
        $res = $modo_edicao ? $clienteObj->atualizar($id, $nome, $cnpj, $resp) : $clienteObj->inserir($nome, $cnpj, $resp);
        $mensagem = $res ? "Dados salvos com sucesso!" : "Erro ao salvar.";
        header("Refresh: 2; url=index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manter Cliente - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-green-800 p-6 text-white font-bold text-xl">
            <?php echo $modo_edicao ? 'Editar Cliente' : 'Novo Cliente'; ?>
        </div>
        <form method="POST" class="p-8 space-y-5">
            <?php if ($mensagem): ?>
                <div class="p-3 rounded bg-blue-50 text-blue-700 text-sm border-l-4 border-blue-500"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <div>
                <label class="block text-stone-700 text-sm font-semibold mb-1">Nome da Fazenda/Empresa</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block text-stone-700 text-sm font-semibold mb-1">CNPJ</label>
                <input type="text" name="cnpj" value="<?php echo htmlspecialchars($dados['cnpj']); ?>" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div>
                <label class="block text-stone-700 text-sm font-semibold mb-1">Nome do Responsável</label>
                <input type="text" name="responsavel" value="<?php echo htmlspecialchars($dados['responsavel']); ?>" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 rounded-xl transition shadow-lg">Salvar Cliente</button>
            <a href="index.php" class="block text-center text-stone-500 text-sm mt-4">Cancelar</a>
        </form>
    </div>
</body>
</html>