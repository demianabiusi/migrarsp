<?php 
$pageTitle = "Generar Scripts de Stored Procedures";
include 'header.php'; 
require 'servidores.php';
?>

<div class="card">
    <h2>Generar Scripts</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">Genera un archivo SQL con el código de creación de los procedimientos seleccionados.</p>
    
    <form action="listar.php" method="post">
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Servidor de Origen</label>
            <select name="srv">
                <?php foreach($listasrv as $n=>$s): ?>
                    <option value="<?=$n?>" <?=(isset($_SESSION['last_srv']) && $_SESSION['last_srv'] == $n) ? 'selected' : ''?>><?=$s?></option>
                <?php endforeach; ?>
            </select>

        </div>

        <div style="margin-top: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Lista de Stored Procedures (uno por línea)</label>
            <textarea name="listado" placeholder="Ej: sp_obtener_usuarios" style="height: 300px; font-family: monospace;"></textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn">Generar Script</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
