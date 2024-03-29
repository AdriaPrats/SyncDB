<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/0-connect.css">
    <title>Conexion</title>
</head>
<body>
    <div class="container">
        <h1>Conexión a BBDD</h1>
        <form action="connect" method="post">
        
            <div class="group">
                <input type="text" name="connexion_data[server-ip]" value="51.178.144.4">
                <span class="bar"></span>
                <label for="server-ip">Ip del Servidor</label>
            </div>

            <div class="group">
                <input type="text" name="connexion_data[dbname]" value="adriaon_wp314">
                <span class="bar"></span>
                <label for="dbname">Nombre BBDD</label>
            </div>

            <div class="group">
                <input type="text" name="connexion_data[user]" value="adriaon_wp314">
                <span class="bar"></span>
                <label for="user">Nombre Usuario</label>
            </div>

            <div class="group">
                <input type="password" name="connexion_data[password]" value="6CHjnLIQmrNz">
                <span class="bar"></span>
                <label for="password">Contraseña</label>
            </div>

            <div class="buttonDiv">
                <button class="button" type="submit" name="submit">Enviar Datos</button>
            </div>

        </form>
    </div>
</body>
</html>
