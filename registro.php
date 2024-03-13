<?php
	require 'conexion/conexion.php';
    $db = new Database();
    $conectar = $db->conectar();
    require 'vendor/autoload.php';

    use Picqer\Barcode\BarcodeGeneratorPNG;
    
    $usua = $conectar->prepare("SELECT * FROM articulos");
    $usua->execute();
    $asigna = $usua->fetchAll(PDO::FETCH_ASSOC);
    
    
    if ((isset($_POST["enviar"]) && ($_POST["enviar"]) == "formu")) {
        $id_articulo = $_POST['id_articulo'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        


        $cod_bar = uniqid() . rand(1000, 9999);
        $generator = new BarcodeGeneratorPNG();
        $codigo_barras_imagen = $generator->getBarcode($cod_bar, $generator::TYPE_CODE_128);
        file_put_contents(__DIR__.'/images/' . $cod_bar . '.png', $codigo_barras_imagen);
    
    
        $insertsql = $conectar->prepare("INSERT INTO articulos (id_articulo,cod_bar, nombre, precio ) VALUES (?, ?, ?, ?)");
        $insertsql->execute([$id_articulo, $cod_bar, $nombre, $precio]);
    }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tabla.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">
    <title>Formularios</title>
</head>
<body>
    <div class="container mt-5">
        <div class="formulario-container">
            <form action="registro.php" method="POST" class="formulario">
                <label for="id_articulo">ID del artículo:</label>
                <input type="text" id="id_articulo" name="id_articulo" readonly>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="precio">Precio:</label>
                <input type="text" id="precio" name="precio" required>

                <input type="submit" name="enviar" value="Enviar">
                <input type="hidden" name="enviar" value="formu">
            </form>
        </div>
    </div>

    <div class="container mt-3">
        <table>
            <thead>
                <tr>
                    <th>ID artículo</th>
                    <th>Cod. Bar</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asigna as $row) : ?>
                    <tr>
                        <td><?php echo $row['id_articulo']; ?></td>
                        <td>
                            <img src="images/<?php echo $row['cod_bar']; ?>.png" alt="Código de Barras">
                        </td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
