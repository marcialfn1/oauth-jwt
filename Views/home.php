<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina principal</title>
</head>

<body>
    <h3>
        Pagina principal Home
    </h3>

    <p>Nombre pagina: <?php echo $data['page_title'] ?></p>

    <p>
        <?php

        dep($data);

        ?>
    </p>
</body>

</html>