<?php 
$pageTitle = "Extracción de Tablas";
include 'header.php'; 
require 'servidores.php';
?>

<div class="card">
    <h2>Extraer Tablas de SP</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">Esta herramienta busca todas las tablas mencionadas en una lista de Stored Procedures.</p>
    
    <form action="extraetablas.php" method="post">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Servidor de Origen</label>
                <select name="srv">
                    <?php foreach($listasrv as $n=>$s): ?>
                        <option value="<?=$n?>" <?=(isset($_SESSION['last_srv']) && $_SESSION['last_srv'] == $n) ? 'selected' : ''?>><?=$s?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Servidor de Destino</label>
                <select name="contra">
                    <?php foreach($listasrv as $n=>$s): ?>
                        <option value="<?=$n?>" <?=(isset($_SESSION['last_dst']) ? ($_SESSION['last_dst'] == $n ? 'selected' : '') : ($n==1 ? 'selected' : ''))?>><?=$s?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="margin-top: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Lista de Stored Procedures (uno por línea)</label>
            <textarea name="listado" placeholder="Ej: sp_obtener_usuarios" style="height: 300px; font-family: monospace;"></textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn">Extraer Tablas</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
