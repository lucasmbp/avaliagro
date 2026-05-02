<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Area.class.php';

// Proteção de acesso
if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$areaObj = new Area($db);

// Variáveis de controle
$id = $_GET['id'] ?? null;
$modo_edicao = !empty($id);
$area_dados = ['area' => '', 'cliente' => ''];
$mensagem = "";

// 1. Se for edição, busca os dados atuais
if ($modo_edicao) {
    $area_dados = $areaObj->buscarPorId($id);
}

// 2. Busca lista de clientes para o select (usando PDO diretamente aqui para simplificar)
$clientes = $db->query("SELECT id, nome FROM cliente ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// 3. Processamento do Formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_area = trim($_POST['area']);
    $id_cliente = $_POST['cliente'];

    if ($modo_edicao) {
        $resultado = $areaObj->atualizar($id, $nome_area, $id_cliente);
        $mensagem = $resultado ? "Área atualizada com sucesso!" : "Erro ao atualizar.";
    } else {
        $mensagem = $areaObj->inserir($nome_area, $id_cliente);
    }
    
    // Redireciona após salvar (opcional: com delay ou mensagem via URL)
    header("Refresh: 2; url=index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $modo_edicao ? 'Editar' : 'Nova'; ?> Área - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-stone-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-stone-100">
        <!-- Header do Card -->
        <div class="bg-green-800 p-6 text-white flex justify-between items-center">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i data-lucide="<?php echo $modo_edicao ? 'edit' : 'plus-circle'; ?>"></i>
                <?php echo $modo_edicao ? 'Editar Área' : 'Cadastrar Nova Área'; ?>
            </h2>
            <a href="index.php" class="text-green-200 hover:text-white transition">
                <i data-lucide="x"></i>
            </a>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <?php if ($mensagem): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded text-sm mb-4">
                    <?php echo $mensagem; ?> (Redirecionando...)
                </div>
            <?php endif; ?>

            <!-- Campo: Descrição da Área -->
            <div>
                <label class="block text-stone-700 font-semibold mb-2 text-sm">Nome da Área/Setor</label>
                <input type="text" name="area" value="<?php echo htmlspecialchars($area_dados['area']); ?>" required
                    placeholder="Ex: Tratores, Financeiro, Lavoura..."
                    class="w-full px-4 py-3 rounded-xl border border-stone-200 focus:ring-2 focus:ring-green-500 outline-none transition">
            </div>

            <!-- Campo: Cliente -->
            <div>
                <label class="block text-stone-700 font-semibold mb-2 text-sm">Vincular ao Cliente</label>
                <select name="cliente" required
                    class="w-full px-4 py-3 rounded-xl border border-stone-200 focus:ring-2 focus:ring-green-500 outline-none bg-white transition">
                    <option value="">Selecione o proprietário</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $area_dados['cliente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex flex-col gap-3 pt-4">
                <button type="submit" 
                    class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-xl shadow-lg transition transform active:scale-95">
                    <?php echo $modo_edicao ? 'Salvar Alterações' : 'Confirmar Cadastro'; ?>
                </button>
                <a href="index.php" class="text-center text-stone-400 hover:text-stone-600 text-sm font-medium transition">
                    Cancelar e Voltar
                </a>
            </div>
        </form>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>