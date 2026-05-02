<?php
session_start();
require_once '../includes/BD/Config.php';
require_once '../classes/Cliente.class.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: ../index.php"); exit(); }

$database = new Database();
$db = $database->getConnection();
$clienteObj = new Cliente($db);

if (isset($_GET['excluir'])) {
    $clienteObj->excluir($_GET['excluir']);
    header("Location: index.php?msg=excluido");
}

$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;
$total_paginas = ceil($clienteObj->contarTodos() / $limite);
$clientes = $clienteObj->listar($limite, $offset);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - AvaliAgro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-stone-100 p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-green-900">Gestão de Clientes</h1>
            <a href="manter_cliente.php" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-xl flex items-center gap-2 shadow transition">
                <i data-lucide="user-plus"></i> Novo Cliente
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-stone-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600">Cliente</th>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600">CNPJ</th>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600">Responsável</th>
                            <th class="px-6 py-4 text-sm font-semibold text-stone-600 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php foreach($clientes as $c): ?>
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-6 py-4 font-medium text-stone-800"><?php echo htmlspecialchars($c['nome']); ?></td>
                            <td class="px-6 py-4 text-stone-600"><?php echo htmlspecialchars($c['cnpj']); ?></td>
                            <td class="px-6 py-4 text-stone-600"><?php echo htmlspecialchars($c['responsavel']); ?></td>
                            <td class="px-6 py-4 flex justify-center gap-4">
                                <a href="manter_cliente.php?id=<?php echo $c['id']; ?>" class="text-blue-600"><i data-lucide="edit"></i></a>
                                <a href="javascript:if(confirm('Excluir cliente?')) window.location.href='index.php?excluir=<?php echo $c['id']; ?>'" class="text-red-500"><i data-lucide="trash-2"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>