<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        input {
            width: 300px;
            padding: 5px;
        }
    </style>
    <title>Navegador</title>
</head>
<body>
    <header>
        <h1>Navegador de BD</h1>
    </header>

    <hr>

    <section>
        <form action="insert" method="post"> 
            
            <table class="rtable">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                            <th> <label for="nombre-carta">Nombre de la carta</label>
                                <input type="text" name="data[datos][nombre-carta]" value="<?=$nombre?>" required>
                            </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Grupo</td>
                        <td>Plato</td>
                        <td>Precio 1</td>
                        <td>Precio 2</td>
                        <td>Descripción</td>
                    </tr>
                        <?php 
                            for ($i=0; $i < count($group); $i++) { 
                                echo '<tr>
                                        <td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][nombre]" value="'.$group[$i]['gru_frase'].'" required>
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][desc_prec1]" placeholder="Descripción del Precio 1">
                                        </td>
                                        <td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][desc_prec2]" placeholder="Descripción del Precio 2">
                                        </td>'
                        ?>
                        <td></td>
                    </tr>
                    
                        <?php 
                            for ($j=0; $j < count($info[$i]); $j++) { 
                                echo '<tr>
                                        <td></td>
                                        <td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][plato'.$j.'][plato]" value="'.htmlspecialchars($info[$i][$j]['pla_plato'], ENT_QUOTES).'">
                                        </td>
                                        <td>
                                            <input type="number" name="data[grupos][grupo'.$i.'][plato'.$j.'][precio1]" value="'.number_format($info[$i][$j]['pla_precio1'], 2, '.', ' ').'" placeholder="Precio 1">
                                        </td>
                                        <td>
                                            <input type="number" name="data[grupos][grupo'.$i.'][plato'.$j.'][precio2]" value="'.number_format($info[$i][$j]['pla_precio2'], 2, '.', ' ').'" placeholder="Precio 2">
                                        </td>
                                        <td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][plato'.$j.'][descripcion]" value="'.$info[$i][$j]['pla_descripcion'].'" placeholder="Descripción del plato">
                                        </td>';
                                    }
                                        '<td>
                                            <input type="text" name="data[grupos][grupo'.$i.'][comentario'.$i.']" value="'.$group[$i]['gru_comentario'].'" placeholder="Aqui va un comentario">
                                        </td>
                                    </tr>';
                            }
                            ?>
                </tbody>
            </table>

            <div class="buttonDiv">
                <label for="submit">Si estan todos los valores correctos OK!</label>
                <button type="submit" name="submit">OK!</button>
            </div>
        </form>
    </section>
    <footer>
        <!-- Aqui podem posar algun footer polit -->
    </footer>

</body>
</html>