<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion a BBDD</h1>
    <form action="connect" method="post">
        <label for="server-ip">Ip del Servidor</label>
        <input type="text" name="connexion_data[server-ip]" value="51.178.144.4">

        <label for="dbname">Nombre BBDD</label>
        <input type="text" name="connexion_data[dbname]" value="adriaon_wp314">

        <label for="user">Nombre Usuario</label>
        <input type="text" name="connexion_data[user]" value="adriaon_wp314">

        <label for="password">Contraseña</label>
        <input type="password" name="connexion_data[password]" value="6CHjnLIQmrNz">

        <div class="buttonDiv">
            <label for="submit">Si estan todos los valores correctos OK!</label>
            <button type="submit" name="submit">OK!</button>
        </div>

    </form>
</body>
</html>
