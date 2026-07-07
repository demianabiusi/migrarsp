<?php 
$pageTitle = "Resultados de Comparación";
include 'header.php'; 
require 'servidores.php';
require 'basecomp.php';

$sp = explode("\n", $_POST['listado']);
$idx = $_POST['srv'];
$dst = $_POST['contra'];

$_SESSION['last_srv'] = $idx;
$_SESSION['last_dst'] = $dst;

$servidor = $arraysrv[$idx];

$destino = $arraysrv[$dst];
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Resultados de Comparación</h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                Comparando <strong><?=$servidor->nombre?></strong> vs <strong><?=$destino->nombre?></strong>
            </p>
        </div>
        <a href="index_compsp.php" class="btn btn-secondary">Nueva Consulta</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Procedimiento</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($sp as $spx) {
                    $spx = trim($spx);
                    if ($spx == '') continue;

                    $spxe = explode('_', $spx);
                    $i = 0;
                    foreach($spxe as $spxu) {
                        $spxe[$i] = ucfirst($spxu);
                        $i++;
                    }
                    $spx = implode("_", $spxe);

                    // Conectar y obtener de origen
                    $mylink = Conectar_Con($servidor);
                    $sql = 'show create procedure ' . $spx;
                    $qry = mysqli_query($mylink, $sql);
                    $row = $qry ? mysqli_fetch_assoc($qry) : null;
                    $proc1 = isset($row['Create Procedure']) ? trim($row['Create Procedure']) : '';

                    // Conectar y obtener de destino
                    $mylink = Conectar_Con($destino);
                    $sql = 'show create procedure ' . $spx;
                    $qry = mysqli_query($mylink, $sql);
                    $row = $qry ? mysqli_fetch_assoc($qry) : null;
                    $proc2 = isset($row['Create Procedure']) ? trim($row['Create Procedure']) : '';

                    $statusClass = '';
                    $statusText = '';
                    $badgeClass = '';
                    $showDiff = false;

                    if ($proc1 == '' || $proc2 == '') {
                        $statusClass = 'status-missing';
                        $badgeClass = 'badge-warning';
                        if ($proc1 == '') $statusText = "No existe en " . $servidor->nombre;
                        else $statusText = "No existe en " . $destino->nombre;
                    } elseif (strtoupper($proc1) != strtoupper($proc2)) {
                        $statusClass = 'status-diff';
                        $badgeClass = 'badge-danger';
                        $statusText = "Tiene Diferencias";
                        $showDiff = true;
                    } else {
                        $statusClass = 'status-equal';
                        $badgeClass = 'badge-success';
                        $statusText = "Son Iguales";
                    }
                    ?>
                    <tr class="status-row <?=$statusClass?>">
                        <td style="font-family: monospace; font-weight: 500;"><?=$spx?></td>
                        <td><span class="badge <?=$badgeClass?>"><?=$statusText?></span></td>
                        <td style="text-align: right;">
                            <?php if ($showDiff): ?>
                                <a href="compsp.php?srv=<?=$_POST['srv']?>&contra=<?=$_POST['contra']?>&sp=<?=urlencode($spx)?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Ver Diferencias</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
