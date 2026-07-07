<?php 
$pageTitle = "Utilidades de Migración";
include 'header.php'; 
?>

<div class="card">
    <h2>Herramientas Disponibles</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">Seleccione una de las siguientes utilidades para comenzar la migración o comparación de bases de datos.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <a href="index_compsp.php" class="card" style="margin-bottom: 0; padding: 1.5rem; text-align: center; border-color: var(--accent-color);">
            <div style="font-size: 2rem; margin-bottom: 1rem;">🔍</div>
            <h3 style="margin-bottom: 0.5rem;">Comparar SP</h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary);">Compara procedimientos almacenados entre diferentes servidores.</p>
        </a>
        
        <a href="index_extrae.php" class="card" style="margin-bottom: 0; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">📋</div>
            <h3 style="margin-bottom: 0.5rem;">Extraer Tablas</h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary);">Busca y lista las tablas utilizadas en los Stored Procedures.</p>
        </a>
        
        <a href="index_migrasp.php" class="card" style="margin-bottom: 0; padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 1rem;">📜</div>
            <h3 style="margin-bottom: 0.5rem;">Generar Scripts</h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary);">Genera scripts SQL a partir de una lista de procedimientos.</p>
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>
