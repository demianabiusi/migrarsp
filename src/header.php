<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Utilidades de Migración'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <span>⚡</span> Migrar<span>SP</span>
            </div>
            <nav>
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Inicio</a>
                <a href="index_compsp.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index_compsp.php' ? 'active' : ''; ?>">Comparar SP</a>
                <a href="index_extrae.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index_extrae.php' ? 'active' : ''; ?>">Extraer Tablas</a>
                <a href="index_migrasp.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index_migrasp.php' ? 'active' : ''; ?>">Generar Scripts</a>
                <a href="config_servers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'config_servers.php' ? 'active' : ''; ?>">⚙️ Configuración</a>
            </nav>

        </header>
        <main>
