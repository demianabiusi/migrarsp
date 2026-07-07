<?php 
$pageTitle = "Configuración de Servidores";
include 'header.php'; 
require 'servidores.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Configuración de Servidores</h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Gestione las instancias de base de datos para comparación y migración.</p>
        </div>
        <a href="edit_server.php" class="btn">Agregar Nuevo Servidor</a>
    </div>

    <div class="server-cards">
        <?php foreach($srv->servidor as $index => $servidor): ?>
        <div class="server-card">
            <div class="server-card-header">
                <span class="server-card-name"><?=$servidor->nombre?></span>
                <span class="server-card-port">:<?=$servidor->puerto?></span>
            </div>
            <div class="server-card-body">
                <div class="server-card-field">
                    <span class="server-card-label">Host</span>
                    <span class="server-card-value"><?=$servidor->host?></span>
                </div>
                <div class="server-card-field">
                    <span class="server-card-label">Base</span>
                    <span class="server-card-value"><?=$servidor->base?></span>
                </div>
                <div class="server-card-field">
                    <span class="server-card-label">Usuario</span>
                    <span class="server-card-value"><?=$servidor->usuario?></span>
                </div>
            </div>
            <div class="server-card-actions">
                <a href="edit_server.php?id=<?=$index?>" class="btn btn-secondary btn-sm">Editar</a>
                <a href="save_server.php?action=delete&id=<?=$index?>" class="btn btn-secondary btn-sm btn-delete" onclick="return confirm('¿Está seguro de que desea eliminar este servidor?')">Eliminar</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
