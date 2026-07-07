<?php 
require 'servidores.php';

$sp = explode("\n", $_POST['listado']);
$idx = $_POST['srv'];
$_SESSION['last_srv'] = $idx;
$servidor = $arraysrv[$idx];

$scriptOutput = "-- SCRIPT EXTRAIDO DEL SERVIDOR " . $servidor->nombre . "\n\n";
$scriptOutput .= "DELIMITER $$\n";

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
    
    if ($row) {
        $proc = $row['Create Procedure'];
        if (trim($proc) != '') {
            $scriptOutput .= "\n\nDROP PROCEDURE IF EXISTS `".$spx."` $$\n\n\n";
            $scriptOutput .= $proc . "$$\n";
        }
    } else {
        $scriptOutput .= "\n-- ********************************************\n";
        $scriptOutput .= "-- No se encuentra el SP " . $spx . "\n";
        $scriptOutput .= "-- ********************************************\n";
    }
}

$scriptOutput .= "\nDELIMITER ;\n";

$pageTitle = "Script Generado";
include 'header.php'; 
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2>Script SQL Generado</h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                Extrayendo de <strong><?=$servidor->nombre?></strong>
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="copyToClipboard()" class="btn btn-secondary">📋 Copiar al Portapapeles</button>
            <button onclick="downloadScript()" class="btn">💾 Descargar .sql</button>
        </div>
    </div>

    <div style="position: relative; background-color: #000; border-radius: 0.5rem; border: 1px solid var(--border-color); overflow: hidden;">
        <textarea id="scriptBox" readonly style="width: 100%; height: 500px; background-color: transparent; color: #10b981; font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; padding: 1.5rem; border: none; outline: none; resize: vertical; line-height: 1.5;"><?=htmlspecialchars($scriptOutput)?></textarea>
    </div>
</div>

<script>
function copyToClipboard() {
    const copyText = document.getElementById("scriptBox");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand("copy");
    
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = "✅ ¡Copiado!";
    btn.classList.add('badge-success');
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.remove('badge-success');
    }, 2000);
}

function downloadScript() {
    const text = document.getElementById("scriptBox").value;
    const blob = new Blob([text], { type: "text/sql" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "script_migracion_<?=date('Ymd_His')?>.sql";
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>

<?php include 'footer.php'; ?>
