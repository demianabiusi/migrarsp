<?php 
$pageTitle = "Diferencias en Stored Procedure";
include 'header.php'; 
require 'servidores.php';
require 'basecomp.php';

$spx = $_GET['sp'];
$idx = $_GET['srv'];
$dst = $_GET['contra'];

$servidor = $arraysrv[$idx];
$destino = $arraysrv[$dst];

// Conectar y obtener
$mylink = Conectar_Con($servidor);
$sql = 'show create procedure ' . $spx;
$qry = mysqli_query($mylink, $sql);
$row = $qry ? mysqli_fetch_assoc($qry) : null;
$proc1 = isset($row['Create Procedure']) ? trim($row['Create Procedure']) : '';

$mylink = Conectar_Con($destino);
$sql = 'show create procedure ' . $spx;
$qry = mysqli_query($mylink, $sql);
$row = $qry ? mysqli_fetch_assoc($qry) : null;
$proc2 = isset($row['Create Procedure']) ? trim($row['Create Procedure']) : '';

// Diff logic
$n1 = "/tmp/" . rand() . rand() . ".txt";
$n2 = "/tmp/" . rand() . rand() . ".txt";

$f1 = fopen($n1, "w");
$f2 = fopen($n2, "w");
fwrite($f1, $proc1);
fwrite($f2, $proc2);
fclose($f1);
fclose($f2);

exec("/usr/bin/diff -i $n1 $n2", $sdiff);

unlink($n1);
unlink($n2);

$a1 = array();
$a2 = array();

foreach ($sdiff as $x) {
    if (is_numeric(substr($x, 0, 1))) {
        if (strpos($x, "a") !== false) {
            $px = explode("a", $x);
            $a1 = array_merge($a1, explode(",", $px[0]));
            $a2 = array_merge($a2, explode(",", $px[1]));
        }
        if (strpos($x, "c") !== false) {
            $px = explode("c", $x);
            $a1 = array_merge($a1, explode(",", $px[0]));
            $a2 = array_merge($a2, explode(",", $px[1]));
        }
    }
}

$p1x = explode("\n", $proc1);
$p2x = explode("\n", $proc2);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Diferencias: <span style="color: var(--accent-color);"><?=$spx?></span></h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                <strong><?=$servidor->nombre?></strong> (Izquierda) vs <strong><?=$destino->nombre?></strong> (Derecha)
            </p>
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background-color: var(--border-color); border: 1px solid var(--border-color); border-radius: 0.5rem; overflow: hidden;">
        <div style="background-color: var(--bg-color); overflow-x: auto;">
            <div style="padding: 0.5rem; background-color: var(--card-bg); font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-align: center; border-bottom: 1px solid var(--border-color);">
                <?=$servidor->nombre?>
            </div>
            <pre style="margin: 0; padding: 1rem; font-family: 'Courier New', Courier, monospace; font-size: 0.875rem;">
<?php foreach ($p1x as $n => $l): 
    $hl = in_array($n + 1, $a1) ? 'background-color: rgba(239, 68, 68, 0.2);' : '';
?>
<div style="<?=$hl?> display: block; min-height: 1.2em;"><?=htmlspecialchars($l)?></div>
<?php endforeach; ?>
            </pre>
        </div>

        <div style="background-color: var(--bg-color); overflow-x: auto;">
            <div style="padding: 0.5rem; background-color: var(--card-bg); font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; text-align: center; border-bottom: 1px solid var(--border-color);">
                <?=$destino->nombre?>
            </div>
            <pre style="margin: 0; padding: 1rem; font-family: 'Courier New', Courier, monospace; font-size: 0.875rem;">
<?php foreach ($p2x as $n => $l): 
    $hl = in_array($n + 1, $a2) ? 'background-color: rgba(16, 185, 129, 0.2);' : '';
?>
<div style="<?=$hl?> display: block; min-height: 1.2em;"><?=htmlspecialchars($l)?></div>
<?php endforeach; ?>
            </pre>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
