<?php
// views/layouts/app.php - Layout principal de la aplicación
$user = Session::getUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Identidad Corporativa SENA 2024 & Estilos Personalizados */
        body { 
            font-family: 'Work Sans', sans-serif; 
            background-color: #f0f2f5; 
        }
        .sena-bg-green { background-color: #39A900; }
        .sena-text-green { color: #39A900; }
        .sena-border-green { border-color: #39A900; }
        .sena-bg-orange { background-color: #FF6C00; }
        .sena-text-orange { color: #FF6C00; }
        
        .sidebar-link { 
            transition: background-color 0.3s, color 0.3s; 
            border-left: 4px solid transparent;
        }
        .sidebar-link:hover {
            background-color: #f0fdf4;
            color: #39A900;
            border-left-color: #FF6C00;
        }
        .sidebar-link.active { 
            background-color: #39A900; 
            color: white; 
            border-left-color: #FF6C00;
        }
        
        /* Scrollbar styles */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Responsive Sidebar Logic */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar Backdrop (for mobile) -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden hidden"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-center p-4 sena-bg-green">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <span class="text-xl font-bold sena-text-green">H</span>
            </div>
            <h1 class="text-2xl font-bold text-white ml-3">Huggin</h1>
        </div>
        
        <nav id="main-nav" class="mt-5">
            <a href="<?= BASE_URL ?>/dashboard" class="sidebar-link flex items-center py-3 px-4 text-gray-700 <?= ($page_title === 'Dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill mr-3"></i>Dashboard
            </a>
            <a href="<?= BASE_URL ?>/programacion" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-calendar-week mr-3"></i>Programación
            </a>
            <a href="<?= BASE_URL ?>/instructores" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-person-badge-fill mr-3"></i>Instructores
            </a>
            <a href="<?= BASE_URL ?>/programas" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-mortarboard-fill mr-3"></i>Programas
            </a>
            <a href="<?= BASE_URL ?>/ambientes" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-building-fill mr-3"></i>Ambientes
            </a>
            <a href="<?= BASE_URL ?>/reportes" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-file-earmark-bar-graph-fill mr-3"></i>Reportes
            </a>
            <?php if (Session::hasPermission('administrador')): ?>
            <a href="<?= BASE_URL ?>/configuracion" class="sidebar-link flex items-center py-3 px-4 text-gray-700">
                <i class="bi bi-gear-fill mr-3"></i>Configuración
            </a>
            <?php endif; ?>
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t">
            <div class="text-xs text-gray-500 mb-2">
                <div><?= htmlspecialchars($user['nombre_completo']) ?></div>
                <div><?= ucfirst($user['rol']) ?></div>
            </div>
            <a href="<?= BASE_URL ?>/auth/logout" class="flex items-center text-gray-600 hover:text-red-500 transition-colors">
                <i class="bi bi-box-arrow-left mr-3"></i>Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="md:ml-64 flex flex-col min-h-screen">
        <!-- Header -->
        <header class="flex justify-between items-center p-4 bg-white border-b">
            <div class="flex items-center">
                <button id="menu-button" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 ml-2"><?= $page_title ?? 'Dashboard' ?></h2>
            </div>
            
            <div class="flex items-center space-x-4 relative">
                <!-- Notificaciones -->
                <button id="notification-btn" class="text-gray-500 hover:text-gray-700 relative">
                    <i class="bi bi-bell-fill text-xl"></i>
                    <?php if (isset($notifications) && count($notifications) > 0): ?>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?= count($notifications) ?>
                    </span>
                    <?php endif; ?>
                </button>
                
                <!-- Panel de Notificaciones -->
                <div id="notification-panel" class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border z-40 hidden">
                    <div class="p-4 font-semibold border-b">Notificaciones</div>
                    <div class="p-2 space-y-2 max-h-96 overflow-y-auto">
                        <?php if (isset($notifications) && !empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="block p-3 rounded-md hover:bg-gray-100 border-l-4 <?= $notification['type'] === 'alert' ? 'border-red-400' : 'border-blue-400' ?>">
                                <p class="font-semibold text-sm"><?= htmlspecialchars($notification['title']) ?></p>
                                <p class="text-xs text-gray-600"><?= htmlspecialchars($notification['message']) ?></p>
                                <p class="text-xs text-gray-400 mt-1">Hace <?= $notification['time'] ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-3 text-center text-gray-500">
                                <i class="bi bi-bell text-2xl"></i>
                                <p class="text-sm mt-2">No hay notificaciones</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Menú de usuario -->
                <div class="relative">
                    <button id="user-menu-btn" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                        <div class="h-10 w-10 rounded-full sena-bg-green text-white flex items-center justify-center">
                            <?= strtoupper(substr($user['nombre_completo'], 0, 1)) ?>
                        </div>
                        <i class="bi bi-chevron-down text-sm"></i>
                    </button>
                    
                    <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-40 hidden">
                        <a href="<?= BASE_URL ?>/auth/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="bi bi-person-circle mr-2"></i>Mi Perfil
                        </a>
                        <a href="<?= BASE_URL ?>/auth/change-password" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="bi bi-key mr-2"></i>Cambiar Contraseña
                        </a>
                        <hr class="my-1">
                        <a href="<?= BASE_URL ?>/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="bi bi-box-arrow-left mr-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mensajes Flash -->
        <?php if (Session::hasFlash('success')): ?>
        <div class="mx-4 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars(Session::getFlash('success')) ?></span>
        </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
        <div class="mx-4 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars(Session::getFlash('error')) ?></span>
        </div>
        <?php endif; ?>

        <!-- Contenido Principal -->
        <main class="flex-1 p-6">
            <?php 
            // Incluir el contenido específico de cada página
            if (isset($content_view) && file_exists(__DIR__ . '/../' . $content_view)) {
                include __DIR__ . '/../' . $content_view;
            } else {
                // Vista por defecto del dashboard
                include __DIR__ . '/../dashboard/index.php';
            }
            ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t p-4 text-center text-sm text-gray-500">
            <p>&copy; <?= date('Y') ?> SENA - Centro Agropecuario Buga | Huggin v<?= APP_VERSION ?></p>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del sidebar móvil
            const menuButton = document.getElementById('menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('open');
                sidebarBackdrop.classList.toggle('hidden');
            }

            menuButton?.addEventListener('click', toggleSidebar);
            sidebarBackdrop?.addEventListener('click', toggleSidebar);

            // Manejo de notificaciones
            const notificationBtn = document.getElementById('notification-btn');
            const notificationPanel = document.getElementById('notification-panel');

            notificationBtn?.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('hidden');
                // Cerrar menú de usuario si está abierto
                document.getElementById('user-menu')?.classList.add('hidden');
            });

            // Manejo del menú de usuario
            const userMenuBtn = document.getElementById('user-menu-btn');
            const userMenu = document.getElementById('user-menu');

            userMenuBtn?.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
                // Cerrar panel de notificaciones si está abierto
                notificationPanel?.classList.add('hidden');
            });

            // Cerrar menús al hacer clic fuera
            document.addEventListener('click', function() {
                notificationPanel?.classList.add('hidden');
                userMenu?.classList.add('hidden');
            });

            // Activar el enlace del sidebar según la página actual
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath || 
                    (currentPath.includes(link.getAttribute('href')) && link.getAttribute('href') !== '<?= BASE_URL ?>/')) {
                    link.classList.add('active');
                }
            });

            // Auto-cerrar mensajes flash después de 5 segundos
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>