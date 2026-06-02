<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manuales del Sistema - McDonald's</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #222;
        }
        /* Contenedor flexible para poner los PDF lado a lado */
        .contenedor-manuales {
            display: flex;
            width: 100%;
            height: 100%;
        }
        /* Cada bloque del manual ocupará el 50% del ancho */
        .seccion-manual {
            flex: 1;
            display: flex;
            flex-direction: column;
            border: 2px solid #444;
        }
        /* Título interno para identificar cada manual */
        .titulo-manual {
            background-color: #ffc72c; /* Amarillo McDonald's */
            color: #da291c; /* Rojo McDonald's */
            margin: 0;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        embed {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>

    <div class="contenedor-manuales">
        <div class="seccion-manual">
            <h2 class="titulo-manual">MANUAL DE ADMINISTRADOR</h2>
            <embed src="MANUAL_Administrador.pdf" type="application/pdf">
        </div>

        <div class="seccion-manual">
            <h2 class="titulo-manual">MANUAL DE USUARIO</h2>
            <embed src="manual_usuario.pdf" type="application/pdf">
        </div>
    </div>

</body>
</html>