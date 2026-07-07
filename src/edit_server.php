<?php 
require 'servidores.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$server = null;

if ($id !== null && isset($srv->servidor[(int)$id])) {
    $server = $srv->servidor[(int)$id];
}

$pageTitle = ($server ? "Editar Servidor" : "Agregar Servidor");
include 'header.php'; 
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2><?=$pageTitle?></h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">Ingrese los detalles de conexión de la instancia de MySQL.</p>

    <form action="save_server.php" method="post">
        <?php if ($id !== null): ?>
            <input type="hidden" name="id" value="<?=$id?>">
        <?php endif; ?>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Nombre Descriptivo</label>
            <input type="text" name="nombre" value="<?=$server ? $server->nombre : ''?>" placeholder="Ej: Producción, Desarrollo..." required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 120px; gap: 1rem;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Host / IP</label>
                <input type="text" name="host" value="<?=$server ? $server->host : ''?>" placeholder="localhost" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Puerto</label>
                <input type="text" name="puerto" value="<?=$server ? $server->puerto : '3306'?>" placeholder="3306" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Usuario</label>
                <input type="text" name="usuario" value="<?=$server ? $server->usuario : ''?>" placeholder="root" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Contraseña</label>
                <input type="password" name="clave" value="<?=$server ? $server->clave : ''?>" placeholder="••••••••">
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">Base de Datos</label>
            <input type="text" name="base" value="<?=$server ? $server->base : ''?>" placeholder="my_database" required>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <a href="config_servers.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn">Guardar Cambios</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
