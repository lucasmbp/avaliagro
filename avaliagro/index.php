<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AvaliAgro - Login</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border-t-8 border-green-700">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-800 tracking-tight">AvaliAgro</h1>
            <p class="text-stone-500 text-sm italic">Gestão de Desempenho no Campo</p>
        </div>

        <form action="processa_login.php" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1">Usuário</label>
                <input type="text" name="login" required 
                    class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-green-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1">Senha</label>
                <input type="password" name="senha" required 
                    class="w-full px-4 py-3 rounded-lg border border-stone-300 focus:ring-2 focus:ring-green-500 focus:outline-none transition">
            </div>

            <button type="submit" 
                class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 rounded-lg shadow-lg transition duration-300 transform active:scale-95">
                Entrar no Sistema
            </button>
        </form>

        <p class="text-center mt-8 text-xs text-stone-400">
            &copy; 2026 AvaliAgro - Tecnologia para o Agronegócio
        </p>
    </div>

</body>
</html>