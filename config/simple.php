<?php

echo "<h2>OCI8</h2>";

if (extension_loaded('oci8')) {
    echo "OCI8 CARGADO<br>";
} else {
    die("OCI8 NO CARGADO");
}

$conn = oci_connect(
    "system",
    "oracle",
    "//192.168.1.50:1521/ORCL"
);

if (!$conn) {

    $e = oci_error();

    echo "<pre>";
    print_r($e);
    echo "</pre>";

    die();
}

echo "CONECTADO OK";

?>