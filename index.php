<!DOCTYPE html>
<html>
<head>
    
    <title>Cartera de Trabajos de Grado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
            background: url('https://i.ibb.co/wWXSGTL/Dise-o-sin-t-tulo-1-min.png') no-repeat center center fixed; 
            background-size: cover;
            color: white;
        }

        .boton {
            background-color: green;
            color: white;
            padding: 20px 30px;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            width: 60%;
            max-width: 400px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            font-size: 1.2em;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .boton:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }

        h1 {
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Cartera de Proyectos - Ingenierías Eléctrica y Electrónica</h1>

<form action="login_estudiante.php" method="get">
    <input type="submit" value="Estudiante" class="boton">
</form>

<form action="proponedor_de_proyectos.php" method="post">
    <input type="submit" value="Proponente de Proyectos" class="boton">
</form>

<form action="login_administrador.php" method="get">
    <input type="submit" value="Administrador" class="boton">
</form>

</body>
</html>
