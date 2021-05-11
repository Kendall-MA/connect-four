<?php
// 0 = ficha del usuario
// 1 = ficha de la máquina
// 3 = campo vacío
$tablero = [
    [3, 3, 3, 3, 3, 3, 3],
    [3, 3, 3, 3, 3, 3, 3],
    [3, 3, 3, 3, 3, 3, 3],
    [3, 3, 3, 3, 3, 3, 3],
    [3, 3, 3, 3, 3, 3, 3],
    [3, 3, 3, 3, 3, 3, 3]
];

$hayGanador = "false";

function rowData($ficha, $id) {

    $nombre = "fila_" . $id;
    $row_data = "";

    if ($ficha == 3) {
        $row_data .= "<button class='hide' class='ficha' name='". $nombre ."'>";
    } else {
        $row_data .= "<button class='ficha' name='". $nombre ."'>";
    }

    if ($ficha == 0) {
        $row_data .= "🟡";
    } 
    else if ($ficha == 1) {
        $row_data .= "🔴";
    }
    $row_data .= "</button>";
    return $row_data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['col1']) || isset($_POST['col2']) || isset($_POST['col3']) || isset($_POST['col4']) || isset($_POST['col5']) || isset($_POST['col6']) || isset($_POST['col7'])) {
    $tablero = isset($_POST['tablero']) ? json_decode($_POST['tablero']) : [];
    $i = 5;
    $hayGanador = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Obtener el nombre de cual columna fue la que acciono el post
    $keys = array_keys($_POST);
    $nombre_col = $keys[2];

    // Obtener el numero de la columna
    $num_col = intval(substr($nombre_col, -1));
    $num_col--;

    if ($hayGanador == "false") {
        if (isset($_POST[$nombre_col])) {
            jugar($tablero, $i, $num_col, 0);
        }
    
        // Revisar si ganó el jugador
        revisarGanador($tablero, 0, $hayGanador);

        if ($hayGanador == "false") {
            // Maquina
            movimientoMaquina($tablero, $i);
        
            // Revisar si ganó la máquina
            revisarGanador($tablero, 1, $hayGanador);
        }
    }
}

function jugar(&$tablero, $fila, $columna, $ficha) {
    while ($tablero[$fila][$columna] != 3 && $fila >= 0) {
        $fila--;
    }
    if ($tablero[$fila][$columna] == 3 && $fila > -1) {
        $tablero[$fila][$columna] = $ficha;
    }
}

function movimientoMaquina(&$tablero, $fila) {
    $i = 5;
    $movimiento = false;
    
    while (!$movimiento) {
        $columna = rand(0, 6);

        if ($tablero[0][$columna] == 3) {
            jugar($tablero, $fila, $columna, 1);
            break;
        }
    }
}

function revisarGanador($tablero, $ficha, &$hayGanador) {
    $jugadores = array("El jugador", "La máquina");
    $ganador = false;
    // Se va a revisar el tablero desde la esquina inferior izquierda hasta
    // la esquina superior derecha
    for ($fila = 5; $fila >= 0; $fila--) {
        for ($col = 0; $col < 7; $col++) {
            if ($tablero[$fila][$col] == $ficha) { // Si es la ficha a revisar, se revisa
                // Las lineas verticales y diagonales se revisan solo si la fila es mayor a 2
                if ($fila > 2) { 
                    // Se revisan diagonales
                    if ($col <= 3) { // Se revisan diagonales hacia la derecha
                        if ($tablero[$fila - 1][$col + 1] == $ficha && $tablero[$fila - 2][$col + 2] == $ficha && $tablero[$fila - 3][$col + 3] == $ficha) {
                            $ganador = true;
                            $hayGanador = "true";
                            break;
                        }
                    }    
                    if ($col >= 4) { // Se revisan diagonales a la izquierda
                        if ($tablero[$fila - 1][$col - 1] == $ficha && $tablero[$fila - 2][$col - 2] == $ficha && $tablero[$fila - 3][$col - 3] == $ficha) {
                            $ganador = true;
                            $hayGanador = "true";
                            break;
                        }
                    }

                    // Se revisan las verticales
                    if ($tablero[$fila - 1][$col] == $ficha && $tablero[$fila - 2][$col] == $ficha && $tablero[$fila - 3][$col] == $ficha) {
                        $ganador = true;
                        $hayGanador = "true";
                        break;
                    }
                }
                // Las lineas horizontales siempre se revisan
                if ($col <= 3) { // Se revisan horizontales a la derecha
                    if ($tablero[$fila][$col + 1] == $ficha &&  $tablero[$fila][$col + 2] == $ficha && $tablero[$fila][$col + 3] == $ficha) {
                        $ganador = true;
                        $hayGanador = "true";
                        break;
                    }
                }
    
                if ($col >= 3) { // Se revisan horizontales a la izquierda
                    if ($tablero[$fila][$col - 1] == $ficha &&  $tablero[$fila][$col - 2] == $ficha && $tablero[$fila][$col - 3] == $ficha) {
                        $ganador = true;
                        $hayGanador = "true";
                        break;
                    }
                }
            }
        }
    }

    if ($ganador) {
        $nombre = $jugadores[$ficha];
        echo "<script>alert('".$nombre." ganó la partida!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cuatro en línea</title>

        <!-- CSS Stylesheet -->
        <link rel="stylesheet" type="text/css" href="css/juego.css" />

        <!-- Google Fonts -->

        <!-- Bootstrap scripts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />

        <!-- Favicon -->
        <link rel="icon" href="img/connect.png">

        <!-- Javascript -->
    </head>
    <body>
    <b>CONNECT 4</b>
        <section>
            <!-- 6 filas, 7 columnas -->
            <form method="POST" action="juego.php">
                <input name="tablero" type="hidden" value="<?php echo json_encode($tablero)?>">
                <input name="estado" type="hidden" value="<?php echo $hayGanador?>">
                <table class="table table-bordered">
                    <tr class="headers">
                        <th><button class="button-arrow" name="col1">↓</button></th>
                        <th><button class="button-arrow" name="col2">↓</button></th>
                        <th><button class="button-arrow" name="col3">↓</button></th>
                        <th><button class="button-arrow" name="col4">↓</button></th>
                        <th><button class="button-arrow" name="col5">↓</button></th>
                        <th><button class="button-arrow" name="col6">↓</button></th>
                        <th><button class="button-arrow" name="col7">↓</button></th>
                    </tr>
                    <?php $count = 1; foreach($tablero as $fila):?>
                    <tr>
                        <?php foreach($fila as $dato):?>
                            <td><?php echo rowData($dato, $count);?></td>
                        <?php $count++; endforeach;?>
                    </tr>
                    <?php endforeach;?>
                </table>
            </form>
        </section>
    </body>
</html>