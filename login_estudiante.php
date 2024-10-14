<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar a la Cartera de Proyectos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f0f0f0;
        }
        h1 {
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        .boton {
            background-color: green;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            width: 200px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            font-size: 1em;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .boton:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }
        input[type="text"] {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .mensaje-error {
            color: red;
        }
    </style>
    
</head>
<body>
    <h1>Ingresar a la Cartera de Proyectos</h1>
    <p>Para ingresar, use su codigo estudiantil otorgado por la Universidad.</p>
    <form method="post" action="verificar_estudiante.php">
        <input type="hidden" name="ordentipo" value="autenticar">
        <input type="text" name="id" placeholder="Ingrese su Código de estudiante" required>
        <input type="submit" value="Autenticar" class="boton">
    </form>
</body>
</html>



