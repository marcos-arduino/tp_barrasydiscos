<?php
include "database.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Control de Stock de Barras</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS (Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<style>
    body {
        background-color: #1a1a1a;
        color: white;
    }
    .stock-normal {
        color: green;
    }
    .stock-bajo {
        color: red;
    }
    .card-custom {
        background-color: #333;
        border-radius: 10px;
    }
    .card-title {
        color: white;
    }
    .form-custom {
        background-color: #444;
        padding: 20px;
        border-radius: 10px;
        color: white;
    }
</style>

<body>
    <div class="container my-5">
        <h2 class="pb-2 border-bottom text-white">Control de Stock de Barras</h2>

        <div class="form-custom mb-4">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="producto" class="form-label">Tipo de Producto:</label>
                    <select class="form-select" id="producto" name="producto" required>
                        <option value="" selected disabled>Seleccione un producto</option>
                        <?php
                        // Cargar productos desde la base de datos
                        $query = "SELECT descripcion FROM productos";
                        $result = mysqli_query(conexion(), $query);
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['descripcion'] . '">' . $row['descripcion'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad a Producir:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese cantidad" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" name="calcular" class="btn btn-primary w-100 me-2">Calcular Hierro Necesario</button>
                    <button type="submit" name="guardar" class="btn btn-success w-100">Guardar Restock</button>
                </div>
            </form>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['calcular'])) {
                $producto = $_POST['producto'];
                $cantidad = $_POST['cantidad'];

                if ($producto == 'Barra de 1.20m') {
                    $hierro_necesario = $cantidad * 11;
                } elseif ($producto == 'Barra de 2.00m') {
                    $hierro_necesario = $cantidad * 22;
                } elseif ($producto == 'Disco de 5kg') {
                    $hierro_necesario = $cantidad * 5.5;
                } elseif ($producto == 'Disco de 10kg') {
                    $hierro_necesario = $cantidad * 11;
                } else {
                    $hierro_necesario = "Producto no válido";
                }

                echo "<div class='alert alert-info mt-3'>Hierro necesario: $hierro_necesario kg</div>";
            }

            if (isset($_POST['guardar'])) {
                $producto = $_POST['producto'];
                $cantidad = $_POST['cantidad'];

                $query = "UPDATE productos SET stock = stock + $cantidad WHERE descripcion = '$producto'";
                $result = mysqli_query(conexion(), $query);

                if ($result) {
                    echo "<div class='alert alert-success mt-3'>Stock actualizado correctamente.</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Error al actualizar el stock.</div>";
                }
            }
        }
        ?>

        <div class="row g-4 py-5">
            <?php
            $query = "SELECT descripcion, stock FROM productos";
            $result = mysqli_query(conexion(), $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $stock = $row['stock'];
                $class = $stock < 5 ? 'stock-bajo' : 'stock-normal';
            ?>
                <div class="col-lg-4">
                    <div class="card card-custom text-center p-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['descripcion']; ?></h5>
                            <p class="card-text <?php echo $class; ?>">
                                Stock: <?php echo $stock; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
