<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Area.class.php';

// Segurança: Verifica se está logado
if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$areaObj = new Area($db);

// Lógica de Exclusão
if (isset($_GET['excluir'])) {
    $areaObj->excluir($_GET['excluir']);
    header("Location: index.php?msg=sucesso");
}

// Paginação
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;
$total_linhas = $areaObj->contarTodos();
$total_paginas = ceil($total_linhas / $limite);

$areas = $areaObj->listar($limite, $offset);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Áreas - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-stone-100 min-h-screen p-4 md:p-8">

    <div class="max-w-5xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-green-900">Gerenciamento de Áreas</h1>
                <p class="text-stone-500">Setores e glebas da fazenda registrados no sistema.</p>
            </div>
            <a href="manter_area.php" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl flex items-center gap-2 shadow-lg transition">
                <i data-lucide="plus-circle"></i> Nova Área
            </a>
        </div>

        <!-- Tabela Responsiva -->
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600">Descrição da Área</th>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600">Cliente Responsável</th>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach($areas as $area): ?>
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-6 py-4 text-stone-800 font-medium"><?php echo htmlspecialchars($area['area']); ?></td>
                            <td class="px-6 py-4 text-stone-600"><?php echo htmlspecialchars($area['cliente_nome'] ?? 'Não definido'); ?></td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <a href="editar_area.php?id=<?php echo $area['id']; ?>" class="text-blue-600 hover:text-blue-800"><i data-lucide="edit-3"></i></a>
                                <a href="javascript:if(confirm('Deseja excluir?')) window.location.href='index.php?excluir=<?php echo $area['id']; ?>'" class="text-red-500 hover:text-red-700"><i data-lucide="trash-2"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="mt-6 flex justify-center gap-2">
            <?php for($i=1; $i<=$total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>" class="px-4 py-2 rounded-lg <?php echo $i==$pagina ? 'bg-green-700 text-white' : 'bg-white text-stone-600 border border-stone-200'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>