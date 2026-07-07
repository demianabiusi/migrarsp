<?php
require 'servidores.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'save';
$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;

// Load XML again to make sure we have the DOM object for modification
$xml = simplexml_load_file('servidores.xml');

if ($action == 'delete' && $id !== null) {
    // SimpleXML doesn't have a direct "remove" for elements easily, 
    // we use unset on the internal array
    unset($xml->servidor[$id]);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $host = $_POST['host'];
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    $puerto = $_POST['puerto'];
    $base = $_POST['base'];

    if ($id !== null && isset($xml->servidor[$id])) {
        // Edit existing
        $xml->servidor[$id]->nombre = $nombre;
        $xml->servidor[$id]->host = $host;
        $xml->servidor[$id]->usuario = $usuario;
        $xml->servidor[$id]->clave = $clave;
        $xml->servidor[$id]->puerto = $puerto;
        $xml->servidor[$id]->base = $base;
    } else {
        // Add new
        $new = $xml->addChild('servidor');
        $new->addChild('nombre', $nombre);
        $new->addChild('host', $host);
        $new->addChild('usuario', $usuario);
        $new->addChild('clave', $clave);
        $new->addChild('puerto', $puerto);
        $new->addChild('base', $base);
    }
}

// Format XML for readability
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save('servidores.xml');

header('Location: config_servers.php');
exit;
