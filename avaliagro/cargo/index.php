<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Cargo.class.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$cargoObj = new Cargo($db);

// Lógica de Exclusão
if (isset($_GET['excluir'])) {
    $cargoObj->excluir($_GET['excluir']);
    header("Location: index.php?msg=excluido");
}

// Paginação
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;
$total_linhas = $cargoObj->contarTodos();
$total_paginas = ceil($total_linhas / $limite);

$cargos = $cargoObj->listar($limite, $offset);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargos - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-stone-100 min-h-screen p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-green-900">Cargos</h1>
            <a href="manter_cargo.php" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-xl flex items-center gap-2 shadow transition">
                <i data-lucide="plus"></i> Novo Cargo
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-stone-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-stone-600">Descrição do Cargo</th>
                        <th class="px-6 py-4 text-sm font-semibold text-stone-600 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    <?php foreach($cargos as $c): ?>
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-6 py-4 text-stone-800"><?php echo htmlspecialchars($c['cargo']); ?></td>
                        <td class="px-6 py-4 flex justify-center gap-4">
                            <a href="manter_cargo.php?id=<?php echo $c['id']; ?>" class="text-blue-600 hover:text-blue-800"><i data-lucide="edit-2" class="w-5 h-5"></i></a>
                            <a href="javascript:if(confirm('Excluir este cargo?')) window.location.href='index.php?excluir=<?php echo $c['id']; ?>'" class="text-red-500 hover:text-red-700"><i data-lucide="trash-2" class="w-5 h-5"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>