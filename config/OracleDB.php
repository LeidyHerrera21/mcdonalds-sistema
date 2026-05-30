<?php

$conn = oci_connect(
    "system",
    "oracle",
    "localhost/FREE"
);

if (!$conn) {

    $e = oci_error();

    die($e['message']);
}

?>