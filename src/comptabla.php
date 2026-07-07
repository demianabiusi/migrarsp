<?php 
$pageTitle = "Comparación de Tabla: " . $_GET['tabla'];
include 'header.php'; 
require 'servidores.php';
require 'basecomp.php';

$tabla = $_GET['tabla'];
$idx = $_GET['idx'];
$dst = $_GET['dst'];

$servidor = $arraysrv[$idx];
$destino = $arraysrv[$dst];    

$l = Conectar_Con($destino);
$st = mysqli_query($l, "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, NUMERIC_PRECISION, NUMERIC_SCALE, CHARACTER_SET_NAME, COLLATION_NAME, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='" . $destino->base . "' AND TABLE_NAME='$tabla'");
    
$rp = array();
$cp = array();
while($r = mysqli_fetch_assoc($st)) {
    $rp[$r['COLUMN_NAME']] = $r;
    $cp[] = $r['COLUMN_NAME'];
}    

$l = Conectar_Con($servidor);
$st = mysqli_query($l, "SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, NUMERIC_PRECISION, NUMERIC_SCALE, CHARACTER_SET_NAME, COLLATION_NAME, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='" . $servidor->base . "' AND TABLE_NAME='$tabla'");

$rd = array();
$cd = array();
while($r = mysqli_fetch_assoc($st)) {
    $rd[$r['COLUMN_NAME']] = $r;
    $cd[] = $r['COLUMN_NAME'];
}    

$dif1 = array_diff($cd, $cp);
$dif2 = array_diff($cp, $cd);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Comparación de Tabla: <span style="color: var(--accent-color);"><?=$tabla?></span></h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                <strong><?=$servidor->nombre?></strong> vs <strong><?=$destino->nombre?></strong>
            </p>
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
    </div>

    <?php if (!empty($dif1)): ?>
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--warning); font-size: 1.1rem;">Campos para agregar a <?=$destino->nombre?></h3>
        <div class="table-responsive">
            <table style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Tipo</th>
                        <th>Default</th>
                        <th>Nullable</th>
                        <th>Query (ALTER TABLE)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dif1 as $d): $r = $rd[$d]; ?>
                    <tr>
                        <td style="font-family: monospace; font-weight: 600;"><?=$d?></td>
                        <td><?=$r['COLUMN_TYPE']?></td>
                        <td><?=$r['COLUMN_DEFAULT'] ?? 'NULL'?></td>
                        <td><?=$r['IS_NULLABLE']?></td>
                        <td>
                            <code style="background-color: var(--bg-color); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                ALTER TABLE `<?=$tabla?>` ADD `<?=$d?>` <?=$r['COLUMN_TYPE']?> 
                                <?=($r['CHARACTER_SET_NAME']!='' ? "CHARACTER SET ".$r['CHARACTER_SET_NAME'] : "")?> 
                                <?=($r['IS_NULLABLE']!='YES' ? "NOT NULL" : "")?> 
                                <?=($r['COLUMN_DEFAULT']!='' ? "DEFAULT ".$r['COLUMN_DEFAULT'] : "")?>;
                            </code>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dif2)): ?>
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--danger); font-size: 1.1rem;">Campos para borrar de <?=$destino->nombre?></h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Query (DROP COLUMN)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dif2 as $d): ?>
                    <tr>
                        <td style="font-family: monospace; font-weight: 600;"><?=$d?></td>
                        <td>
                            <code style="background-color: var(--bg-color); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                ALTER TABLE `<?=$tabla?>` DROP COLUMN `<?=$d?>`;
                            </code>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php
    $dx = array();
    $dif_all = array_merge($dif1, $dif2);
    foreach ($rp as $r) {
        if (!in_array($r, $rd) && !in_array($r['COLUMN_NAME'], $dif_all)) {
            $dx[] = ['source' => 'destino', 'data' => $r];
            $dx[] = ['source' => 'origen', 'data' => $rd[$r['COLUMN_NAME']]];
        }
    }
    ?>

    <?php if (!empty($dx)): ?>
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--accent-color); font-size: 1.1rem;">Campos con diferencias de definición</h3>
        <div class="table-responsive">
            <table style="font-size: 0.8rem;">
                <thead>
                    <tr>
                        <th>Instancia</th>
                        <th>Campo</th>
                        <th>Tipo</th>
                        <th>Default</th>
                        <th>Nullable</th>
                        <th>Extra</th>
                        <th>Query</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $ant = -1;
                    foreach ($dx as $item): 
                        $r = $item['data'];
                        $isOrigen = ($item['source'] == 'origen');
                        if ($ant != $r['COLUMN_NAME'] && $ant != -1) {
                            echo '<tr style="background-color: var(--border-color); height: 2px;"><td colspan="7"></td></tr>';
                        }
                    ?>
                    <tr style="<?=$isOrigen ? 'background-color: rgba(99, 102, 241, 0.05);' : ''?>">
                        <td style="font-weight: 600; font-size: 0.7rem; color: var(--text-secondary);">
                            <?=$isOrigen ? $servidor->nombre : $destino->nombre?>
                        </td>
                        <td style="font-family: monospace; font-weight: 600;"><?=$r['COLUMN_NAME']?></td>
                        <td><?=$r['COLUMN_TYPE']?></td>
                        <td><?=$r['COLUMN_DEFAULT'] ?? 'NULL'?></td>
                        <td><?=$r['IS_NULLABLE']?></td>
                        <td><?=$r['EXTRA']?></td>
                        <td>
                            <?php if ($isOrigen): ?>
                            <code style="background-color: var(--bg-color); padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-size: 0.7rem;">
                                ALTER TABLE `<?=$tabla?>` MODIFY `<?=$r['COLUMN_NAME']?>` <?=$r['COLUMN_TYPE']?> 
                                <?=($r['CHARACTER_SET_NAME']!='' ? "CHARACTER SET ".$r['CHARACTER_SET_NAME'] : "")?> 
                                <?=($r['IS_NULLABLE']!='YES' ? "NOT NULL" : "")?> 
                                <?=($r['COLUMN_DEFAULT']!='' ? "DEFAULT ".$r['COLUMN_DEFAULT'] : "")?>;
                            </code>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        $ant = $r['COLUMN_NAME'];
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div style="margin-top: 3rem;">
        <h3 style="font-size: 1rem; margin-bottom: 1rem;">Estructura Completa en <?=$servidor->nombre?></h3>
        <div class="table-responsive">
            <table style="font-size: 0.75rem;">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Tipo</th>
                        <th>Key</th>
                        <th>Extra</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rd as $r): 
                        $isEqual = in_array($r, $rp);
                    ?>
                    <tr class="status-row <?=$isEqual?'status-equal':'status-diff'?>">
                        <td style="font-family: monospace;"><?=$r['COLUMN_NAME']?></td>
                        <td><?=$r['COLUMN_TYPE']?></td>
                        <td><?=$r['COLUMN_KEY']?></td>
                        <td><?=$r['EXTRA']?></td>
                        <td><span class="badge <?=$isEqual?'badge-success':'badge-danger'?>"><?=$isEqual?'Idéntico':'Diferente'?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
