@echo off
REM Crear carpetas
mkdir huggin
mkdir huggin\config
mkdir huggin\controllers
mkdir huggin\models
mkdir huggin\views
mkdir huggin\views\layouts
mkdir huggin\views\auth
mkdir huggin\views\dashboard
mkdir huggin\assets
mkdir huggin\assets\css
mkdir huggin\assets\js
mkdir huggin\assets\images
mkdir huggin\utils
mkdir huggin\sql

REM Crear archivos en config
type nul > huggin\config\database.php
type nul > huggin\config\config.php
type nul > huggin\config\routes.php

REM Crear archivos en controllers
type nul > huggin\controllers\AuthController.php
type nul > huggin\controllers\DashboardController.php
type nul > huggin\controllers\InstructorController.php
type nul > huggin\controllers\ProgramaController.php
type nul > huggin\controllers\AmbienteController.php
type nul > huggin\controllers\FichaController.php
type nul > huggin\controllers\ProgramacionController.php
type nul > huggin\controllers\ReporteController.php

REM Crear archivos en models
type nul > huggin\models\Database.php
type nul > huggin\models\User.php
type nul > huggin\models\Instructor.php
type nul > huggin\models\Programa.php
type nul > huggin\models\Ambiente.php
type nul > huggin\models\Ficha.php
type nul > huggin\models\Programacion.php

REM Crear archivos en views
type nul > huggin\views\layouts\header.php
type nul > huggin\views\layouts\footer.php
type nul > huggin\views\layouts\sidebar.php
type nul > huggin\views\auth\login.php
type nul > huggin\views\dashboard\index.php

REM Crear archivos en utils
type nul > huggin\utils\Validator.php
type nul > huggin\utils\Session.php
type nul > huggin\utils\Response.php

REM Crear archivo en sql
type nul > huggin\sql\database.sql

REM Crear raíz
type nul > huggin\.htaccess
type nul > huggin\index.php

REM Crear .gitkeep en carpetas vacías
type nul > huggin\assets\css\.gitkeep
type nul > huggin\assets\js\.gitkeep
type nul > huggin\assets\images\.gitkeep

echo.
echo 🚀 Estructura con .gitkeep lista para GitHub.
pause
