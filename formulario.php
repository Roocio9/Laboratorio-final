<?php

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_de_datos";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}



// Función para validar el formato de email
function validarEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

// Función para validar la longitud de la contraseña
function validarPassword($password) {
    $longitud = strlen($password);
    if ($longitud < 4 || $longitud > 8) {
        return false;
    }
    return true;
}

// Procesar los datos enviados desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido1 = $_POST["apellido1"];
    $apellido2 = $_POST["apellido2"];
    $email = $_POST["email"];
    $login = $_POST["login"];
    $password = $_POST["password"];

    // Validar los datos
    $errors = array();

    if (empty($nombre)) {
        $errors[] = "El nombre es obligatorio.";
    }

    if (empty($apellido1)) {
        $errors[] = "El primer apellido es obligatorio.";
    }

    if (empty($apellido2)) {
        $errors[] = "El segundo apellido es obligatorio.";
    }

    if (empty($email)) {
        $errors[] = "El email es obligatorio.";
    } elseif (!validarEmail($email)) {
        $errors[] = "El email no tiene un formato válido.";
    }

    if (empty($login)) {
        $errors[] = "El login es obligatorio.";
    }

    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria.";
    } elseif (!validarPassword($password)) {
        $errors[] = "La contraseña debe tener entre 4 y 8 caracteres.";
    }

    // Si hay errores, mostrarlos y volver al formulario
    if (!empty($errors)) {
        echo "<h3>Error:</h3>";
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "<a href='javascript:history.back()'>Volver</a>";
    } else {
        // Verificar si el email ya existe en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<body style='background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
    background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;'>";    
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
    echo "<div style='background-color: #f9e8f2; padding: 50px; border-radius: 12px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
    echo "<h3 style='text-align: center; font-family: \"Poppins\", sans-serif; margin-bottom: 20px;'>El correo electrónico ya está registrado. Por favor, inténtalo nuevamente.</h3>";
    echo "<br><a href='javascript:history.back()'>Volver</a>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
            
        } else {
            // Insertar los datos en la base de datos
            $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, email, login, password) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$login', '$password')";
            if ($conn->query($sql) === true) {
                echo "<body style='background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
                background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
                background-attachment: fixed;
                background-repeat: no-repeat;'>";    
                echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
                echo "<div style='background-color: #f9e8f2; padding: 50px; border-radius: 12px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
                echo "<h3 style='text-align: center; font-family: \"Poppins\", sans-serif; margin-bottom: 20px;'>Registro completado con éxito.</h3>";
                echo "</div>";
                echo "</div>";
                echo "</body>";
            } else {
                echo "Error al registrar los datos: " . $conn->error;
            }
        }
    }
    $conn->close();
}


?>


<?php

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_de_datos";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Función para obtener la lista de usuarios registrados
function obtenerUsuariosRegistrados() {
    global $conn;
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);
    $usuarios = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }
    return $usuarios;
}

// Verificar si se ha realizado una consulta
if (isset($_GET['consulta'])) {
    // Obtener la lista de usuarios registrados y mostrarla
    $listaUsuarios = obtenerUsuariosRegistrados();
    if (!empty($listaUsuarios)) {
        echo "<body style='background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
    background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;'>";
    
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
    echo "<div style='background-color: #f9e8f2; padding: 50px; border-radius: 12px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
    echo "<h3 style='text-align: center; font-family: \"Poppins\", sans-serif; margin-bottom: 20px;'>Lista de usuarios registrados:</h3>";
    echo "<ul style='list-style: none; padding: 0;'>";
    foreach ($listaUsuarios as $usuario) {
        echo "<li style='font-family: \"Poppins\", sans-serif; font-size: 14px; margin-bottom: 10px;'>" . $usuario["nombre"] . " " . $usuario["apellido1"] . " " . $usuario["apellido2"] . "</li>";
    }
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    
    echo "</body>";
} else {
    echo "<body style='background-image: linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%);
    background-image: linear-gradient(to top, #a8edea 0%, #fed6e3 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;'>";    
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
    echo "<div style='background-color: #f9e8f2; padding: 50px; border-radius: 12px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
    echo "<h3 style='text-align: center; font-family: \"Poppins\", sans-serif; margin-bottom: 20px;'>No se encontraron usuarios registrados.</h3>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
}
}



// Cerrar la conexión a la base de datos
$conn->close();

?>






