<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegador</title>
</head>
<body>
    <header>
        <h1>Navegador de BD</h1>
    </header>

    <hr>

    <section>
        <form action="query" method="post"> 
            <label for="carta">Selecciona una carta</label>
            <select name="carta" id="carta">

                <?php

                    foreach ($data as $row) {
                        echo "<option value='$row[car_id]'>$row[car_nombre]</option>";
                    }

                ?>

            </select>
            <div class="buttonDiv">
                <button type="submit" name="submit">Comprovar</button>
            </div>
        </form>
    </section>
    <footer>
        <!-- Aqui podem posar algun footer polit -->
    </footer>
</body>
</html>