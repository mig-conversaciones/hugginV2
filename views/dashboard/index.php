<?php
// views/dashboard/index.php - Vista principal del Dashboard
?>

<div class="container mx-auto">
    <!-- Título de bienvenida -->
    <div class="mb-6">
        <h3 class="text-2xl font-semibold text-gray-700">Bienvenido a Huggin</h3>
        <p class="text-gray-600 mt-1">Sistema de Programación Académica - Centro Agropecuario Buga</p>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="<?= BASE_URL ?>/instructores" class="block bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Instructores Activos</p>
                    <p class="text-3xl font-bold sena-text-green"><?= $stats['instructores_activos'] ?? 0 ?></p>
                </div>
                <div class="p-3 rounded-full sena-bg-green text-white">
                    <i class="bi bi-person-badge-fill text-2xl"></i>
                </div>
            </div>
        </a>

        <a href="<?= BASE_URL ?>/programas" class="block bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Fichas en Formación</p>
                    <p class="text-3xl font-bold sena-text-green"><?= $stats['fichas_formacion'] ?? 0 ?></p>
                </div>
                <div class="p-3 rounded-full sena-bg-green text-white">
                    <i class="bi bi-collection-fill text-2xl"></i>
                </div>
            </div>
        </a>

        <a href="<?= BASE_URL ?>/ambientes" class="block bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ambientes Disponibles</p>
                    <p class="text-3xl font-bold sena-text-green"><?= $stats['ambientes_disponibles'] ?? 0 ?></p>
                </div>
                <div class="p-3 rounded-full sena-bg-green text-white">
                    <i class="bi bi-building-fill text-2xl"></i>
                </div>
            </div>
        </a>

        <div class="block bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Alertas TyT</p>
                    <p class="text-3xl font-bold sena-text-orange"><?= $stats['alertas_tyt'] ?? 0 ?></p>
                </div>
                <div class="p-3 rounded-full sena-bg-orange text-white">
                    <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel TyT -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Panel de Seguimiento TyT</h4>
        <div class="overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ficha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Avance Lectivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Evaluado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado TyT</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">228106</td>
                        <td class="px-6 py-4 whitespace-nowrap">ADSO</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">80%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="sena-bg-green h-2 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">75%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="sena-bg-green h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Habilitada</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">218107</td>
                        <td class="px-6 py-4 whitespace-nowrap">Gestión Contable</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">72%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="sena-bg-green h-2 rounded-full" style="width: 72%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">70%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Próxima a Habilitar</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">208108</td>
                        <td class="px-6 py-4 whitespace-nowrap">Mecatrónica</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">50%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-400 h-2 rounded-full" style="width: 50%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 mr-2">45%</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-400 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No Habilitada</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grid de dos columnas para actividades y resumen -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actividades Recientes -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Actividades Recientes</h4>
            <div class="space-y-3">
                <?php if (isset($recent_activities) && !empty($recent_activities)): ?>
                    <?php foreach (array_slice($recent_activities, 0, 5) as $activity): ?>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="bi bi-clock text-gray-500 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium"><?= htmlspecialchars($activity['nombre_completo'] ?? 'Sistema') ?></span>
                                <?= ucfirst(str_replace('_', ' ', $activity['action'])) ?>
                            </p>
                            <p class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-activity text-4xl mb-3"></i>
                        <p>No hay actividades recientes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumen Rápido -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Resumen del Sistema</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Usuario conectado:</span>
                    <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['nombre_completo']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rol:</span>
                    <span class="text-sm font-medium sena-text-green"><?= ucfirst($user['rol']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Última conexión:</span>
                    <span class="text-sm text-gray-500"><?= date('d/m/Y H:i') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Versión del sistema:</span>
                    <span class="text-sm text-gray-500">v<?= APP_VERSION ?></span>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="mt-6">
                <h5 class="text-sm font-medium text-gray-700 mb-3">Accesos Rápidos</h5>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= BASE_URL ?>/programacion" class="text-center p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                        <i class="bi bi-calendar-plus text-lg sena-text-green"></i>
                        <p class="text-xs mt-1">Nueva Programación</p>
                    </a>
                    <a href="<?= BASE_URL ?>/instructores" class="text-center p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                        <i class="bi bi-person-plus text-lg sena-text-green"></i>
                        <p class="text-xs mt-1">Agregar Instructor</p>
                    </a>
                    <a href="<?= BASE_URL ?>/reportes" class="text-center p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                        <i class="bi bi-file-earmark-text text-lg sena-text-green"></i>
                        <p class="text-xs mt-1">Generar Reporte</p>
                    </a>
                    <a href="<?= BASE_URL ?>/configuracion" class="text-center p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                        <i class="bi bi-gear text-lg sena-text-green"></i>
                        <p class="text-xs mt-1">Configuración</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar estadísticas cada 5 minutos
    setInterval(function() {
        updateDashboardStats();
    }, 300000);

    function updateDashboardStats() {
        fetch('<?= BASE_URL ?>/dashboard/stats', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success !== false) {
                // Actualizar las estadísticas en la UI
                console.log('Estadísticas actualizadas');
            }
        })
        .catch(error => {
            console.log('Error al actualizar estadísticas:', error);
        });
    }
});