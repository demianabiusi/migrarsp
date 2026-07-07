<?php 
$pageTitle = "Tablas Extraídas de SP";
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

$tablas = array();

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

    $mylink = Conectar_Con($servidor);
    $sql = 'show create procedure ' . $spx;
    $qry = mysqli_query($mylink, $sql);
    $row = $qry ? mysqli_fetch_assoc($qry) : null;
    $proc = isset($row['Create Procedure']) ? eliminar_comentarios($row['Create Procedure']) : '';

    
    if (trim($proc) != '') {
        $from = extraer_palabra_despues($proc, "from ");                
        $join = extraer_palabra_despues($proc, "join ");    
        $into = extraer_palabra_despues($proc, "insert into ");
        $updt = extraer_palabra_despues($proc, "update ");                
        $temp = extraer_palabra_despues($proc, "create temporary table ");
        
        $todo = array_merge($from, $join, $into, $updt);
        $todo = array_unique($todo);
        $todo = array_diff($todo, $temp);
        
        $tablas = array_unique(array_merge($tablas, $todo));
    }
}
sort($tablas);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Tablas Encontradas</h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                Analizado en <strong><?=$servidor->nombre?></strong> para comparar con <strong><?=$destino->nombre?></strong>
            </p>
        </div>
        <a href="index_extrae.php" class="btn btn-secondary">Nueva Extracción</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nombre de Tabla</th>
                    <th>Estado en Destino</th>
                    <th style="text-align: right;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($tablas as $t) {
                    $msg = '';
                    $lnk = '';
                    $statusClass = '';
                    $badgeClass = '';
                    
                    if (comparador($destino, $servidor, $rp, $rd, $t)) {
                        $statusClass = 'status-equal';
                        $badgeClass = 'badge-success';
                        $msg = 'Son idénticas';
                    } else {
                        $statusClass = 'status-diff';
                        $badgeClass = 'badge-danger';
                        $msg = 'Tienen diferencias';
                        $lnk = "comptabla.php?tabla=" . $t . "&idx=" . $idx . "&dst=" . $dst;
                    }
                    
                    if (count($rp) == 0) {
                        $statusClass = 'status-missing';
                        $badgeClass = 'badge-warning';
                        $msg = 'No existe en ' . $destino->nombre;
                        $lnk = "copiartabla.php?srv=" . $idx . "&tabla=" . $t;
                    }
                    
                    if (count($rd) == 0) {
                        $statusClass = 'status-missing';
                        $badgeClass = 'badge-warning';
                        $msg = 'No existe en ' . $servidor->nombre;
                    }
                    
                    if (count($rp) > 0 || count($rd) > 0):
                    ?>
                    <tr class="status-row <?=$statusClass?>">
                        <td style="font-family: monospace; font-weight: 500;"><?=$t?></td>
                        <td><span class="badge <?=$badgeClass?>"><?=$msg?></span></td>
                        <td style="text-align: right;">
                            <?php if ($lnk != ''): ?>
                                <a href="<?=$lnk?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
                                    <?=strpos($lnk, 'copiartabla') !== false ? 'Copiar Tabla' : 'Ver Diferencias'?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                    endif;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
