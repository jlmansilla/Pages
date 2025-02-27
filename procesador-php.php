<?php
// Configuración de correo
$destinatario = "contacto@manastic.cl";
$asunto = "Nuevo contacto desde la web manastic.cl";

// Validación de campos
if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Obtener valores del formulario
$nombre = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$empresa = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
$mensaje = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// Validar email
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email no válido']);
    exit;
}

// Construir el mensaje
$contenido = "Nombre: " . $nombre . "\n";
$contenido .= "Email: " . $email . "\n";
$contenido .= "Empresa: " . $empresa . "\n\n";
$contenido .= "Mensaje:\n" . $mensaje . "\n";

// Cabeceras del correo
$cabeceras = 'From: ' . $email . "\r\n" .
             'Reply-To: ' . $email . "\r\n" .
             'X-Mailer: PHP/' . phpversion();

// Intentar enviar el correo
$envio_exitoso = mail($destinatario, $asunto, $contenido, $cabeceras);

// Enviar respuesta al cliente
if($envio_exitoso) {
    // Opcional: Registrar el contacto en una base de datos
    // insertarContacto($nombre, $email, $empresa, $mensaje);
    
    echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
}

/* Función opcional para guardar contactos en base de datos
function insertarContacto($nombre, $email, $empresa, $mensaje) {
    // Configuración de la base de datos
    $servidor = "localhost";
    $usuario_db = "usuario_db";
    $password_db = "password_db";
    $base_datos = "nombre_base_datos";
    
    // Conectar a la base de datos
    $conn = new mysqli($servidor, $usuario_db, $password_db, $base_datos);
    
    // Verificar conexión
    if ($conn->connect_error) {
        return false;
    }
    
    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, empresa, mensaje, fecha) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $nombre, $email, $empresa, $mensaje);
    
    // Ejecutar la consulta
    $resultado = $stmt->execute();
    
    // Cerrar conexión
    $stmt->close();
    $conn->close();
    
    return $resultado;
}*/
?>
