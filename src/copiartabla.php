<?php
$pageTitle = "Script de Copia — Tabla";
include 'header.php';
require 'servidores.php';

$idx = $_GET['srv'];
$tabla = $_GET['tabla'];
$servidor = $arraysrv[$idx];

$mylink = Conectar_Con($servidor);
$sql = 'SHOW CREATE TABLE ' . $tabla;
$qry = mysqli_query($mylink, $sql);
$error = mysqli_error($mylink);
$row = @mysqli_fetch_assoc($qry);
$createTable = $row['Create Table'] ?? '';

$mylink2 = Conectar_Con($servidor);
$qryCount = mysqli_query($mylink2, 'SELECT COUNT(*) AS cant FROM ' . $tabla);
$rowCount = @mysqli_fetch_assoc($qryCount);
$cant = $rowCount['cant'] ?? 0;

$inserts = '';
if ($cant > 0 && $cant < 5000) {
    $mylink3 = Conectar_Con($servidor);
    $qryData = mysqli_query($mylink3, 'SELECT * FROM ' . $tabla);
    while ($row = @mysqli_fetch_assoc($qryData)) {
        $cols = '`' . implode('`,`', array_keys($row)) . '`';
        $vals = "'" . implode("','", array_map(function($v) { return str_replace("'", "\'", $v); }, array_values($row))) . "'";
        $inserts .= "INSERT INTO `$tabla` ($cols) VALUES ($vals);\n";
    }
}
?>

<div class="card">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <a href="javascript:history.back()" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">&larr; Volver</a>
        <div>
            <h2 style="margin-bottom: 0;"><?=$tabla?></h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">
                Exportado desde <strong style="color: var(--accent-color);"><?=$servidor->nombre?></strong>
                (<?=$servidor->host?>:<?=$servidor->puerto?>) — 
                <?=$cant?> registro<?=$cant == 1 ? '' : 's'?>
            </p>
        </div>
    </div>

    <?php if ($error): ?>
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: var(--danger);">
        <strong>Error:</strong> <?=$error?>
    </div>
    <?php endif; ?>

    <h3>CREATE TABLE</h3>
    <pre class="sql-block"><code><?=htmlspecialchars($createTable)?>;</code></pre>

    <?php if ($inserts): ?>
    <h3 style="margin-top: 2rem;">INSERT — <?=$cant?> registro<?=$cant == 1 ? '' : 's'?></h3>
    <p style="color: var(--text-secondary); font-size: 0.8125rem; margin-bottom: 0.75rem;">La tabla tiene menos de 5.000 registros, se incluyen los datos para insertar.</p>
    <pre class="sql-block"><code><?=htmlspecialchars($inserts)?></code></pre>
    <?php elseif ($cant >= 5000): ?>
    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; padding: 1rem; margin-top: 1.5rem; color: var(--warning); font-size: 0.875rem;">
        La tabla tiene <strong><?=$cant?></strong> registros (&ge; 5.000). Los datos no se incluyen en el script.
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
