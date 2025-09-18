<?php
// views/auth/login.php - Vista de login
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - <?= APP_NAME ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Work Sans', sans-serif; 
            background-color: #f0f2f5; 
        }
        .sena-bg-green { background-color: #39A900; }
        .sena-bg-orange { background-color: #FF6C00; }
        .login-container {
            background: linear-gradient(135deg, #39A900 0%, #2d7a00 100%);
            min-height: 100vh;
        }
        .login-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="login-container flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-8 login-form rounded-lg shadow-xl">
            <!-- Logo y título -->
            <div class="text-center">
                <div class="mx-auto w-20 h-20 sena-bg-green rounded-full flex items-center justify-center mb-6">
                    <span class="text-3xl font-bold text-white">H</span>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">Bienvenido a Huggin</h2>
                <p class="mt-2 text-sm text-gray-600">Sistema de Programación Académica</p>
                <p class="text-xs text-gray-500">Centro Agropecuario Buga - CAB</p>
            </div>

            <!-- Mensajes flash -->
            <?php if (isset($error_message) && $error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($error_message) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message) && $success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($success_message) ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulario de login -->
            <form method="POST" action="<?= BASE_URL ?>/auth/login" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo Electrónico
                        </label>
                        <div class="mt-1 relative">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                placeholder="usuario@sena.edu.co"
                                value="admin@sena.edu.co"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <div class="mt-1 relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="appearance-none relative block w-full px-3 py-3 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                placeholder="Ingrese su contraseña"
                                value="admin123"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <i class="bi bi-eye text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember-me" 
                            name="remember-me" 
                            type="checkbox" 
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Recordarme
                        </label>
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white sena-bg-green hover:sena-bg-orange focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bi bi-box-arrow-in-right text-green-300 group-hover:text-orange-300"></i>
                        </span>
                        Ingresar al Sistema
                    </button>
                </div>
            </form>

            <!-- Información adicional -->
            <div class="mt-6 text-center">
                <div class="text-xs text-gray-500">
                    <p>¿Problemas para acceder?</p>
                    <p>Contacte al administrador del sistema</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-xs text-gray-400 mt-8">
                <p>&copy; <?= date('Y') ?> SENA - Centro Agropecuario Buga</p>
                <p>Huggin v<?= APP_VERSION ?></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash text-gray-400 hover:text-gray-600';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye text-gray-400 hover:text-gray-600';
            }
        }

        // Auto-focus en el primer campo vacío
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            if (!emailInput.value) {
                emailInput.focus();
            } else if (!passwordInput.value) {
                passwordInput.focus();
            }
        });

        // Animación de carga en el botón de submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i>Iniciando sesión...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>