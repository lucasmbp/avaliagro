<?php
// Inicia a sessão e verifica se o usuário está logado
session_start();

if (!isset($_SESSION['usuario_id'])) {
    // Se não houver sessão, redireciona para o login por segurança
    header("Location: index.php");
    exit();
}

// Pegamos o nome do usuário para dar as boas-vindas
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvaliAgro - Hub do Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Biblioteca de ícones Lucide (leve e moderna) -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-stone-50 min-h-screen">

    <!-- Cabeçalho de Boas-Vindas -->
    <header class="bg-green-800 text-white shadow-md p-6">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div>
                <p class="text-green-200 text-sm">Bem-vindo ao AvaliAgro,</p>
                <h1 class="text-2xl font-bold"><?php echo explode(' ', $nomeUsuario)[0]; ?>!</h1>
            </div>
            <a href="logout.php" class="bg-green-700 hover:bg-red-600 px-4 py-2 rounded-lg transition text-sm font-semibold">
                Sair
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto p-6">
        <h2 class="text-stone-600 font-semibold mb-6 uppercase tracking-wider text-sm text-center md:text-left">
            Módulos de Gestão
        </h2>

        <!-- Grid de Módulos (Mobile First) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Módulo: Áreas -->
            <a href="area/index.php" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-200 hover:shadow-xl hover:border-green-500 transition duration-300">
                <div class="bg-green-100 text-green-700 w-12 h-12 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i data-lucide="map"></i>
                </div>
                <h3 class="text-lg font-bold text-stone-800">Áreas</h3>
                <p class="text-stone-500 text-sm">Gerencie os setores e glebas da fazenda.</p>
            </a>

            <!-- Módulo: Avaliados (Funcionários) -->
            <a href="avaliados/index.php" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-200 hover:shadow-xl hover:border-green-500 transition duration-300">
                <div class="bg-green-100 text-green-700 w-12 h-12 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i data-lucide="users"></i>
                </div>
                <h3 class="text-lg font-bold text-stone-800">Avaliados</h3>
                <p class="text-stone-500 text-sm">Lista de colaboradores aptos para avaliação.</p>
            </a>

            <!-- Módulo: Avaliações -->
            <a href="avaliacao/index.php" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-200 hover:shadow-xl hover:border-green-500 transition duration-300">
                <div class="bg-green-100 text-green-700 w-12 h-12 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i data-lucide="clipboard-check"></i>
                </div>
                <h3 class="text-lg font-bold text-stone-800">Avaliações</h3>
                <p class="text-stone-500 text-sm">Crie e acompanhe o desempenho da equipe.</p>
            </a>

            <!-- Módulo: Cargos -->
            <a href="cargo/index.php" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-200 hover:shadow-xl hover:border-green-500 transition duration-300">
                <div class="bg-green-100 text-green-700 w-12 h-12 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i data-lucide="briefcase"></i>
                </div>
                <h3 class="text-lg font-bold text-stone-800">Cargos</h3>
                <p class="text-stone-500 text-sm">Defina as funções e responsabilidades.</p>
            </a>

            <!-- Módulo: Usuários -->
            <a href="usuario/index.php" class="group bg-white p-6 rounded-2xl shadow-sm border border-stone-200 hover:shadow-xl hover:border-green-500 transition duration-300">
                <div class="bg-green-100 text-green-700 w-12 h-12 rounded-lg flex items-center justify-center mb-4 group-hover:bg-green-600 group-hover:text-white transition">
                    <i data-lucide="user-cog"></i>
                </div>
                <h3 class="text-lg font-bold text-stone-800">Usuários</h3>
                <p class="text-stone-500 text-sm">Controle de acesso e administradores.</p>
            </a>

        </div>
    </main>

    <script>
        // Inicializa os ícones da biblioteca Lucide
        lucide.createIcons();
    </script>
</body>
</html>