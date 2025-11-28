<?php
session_start();
include("API/conexion.php");

// 🔒 SEGURIDAD: Si no es admin, lo sacamos
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    header("Location: index.php");
    exit;
}

$mensaje = "";

// 🔄 PROCESAR CAMBIO DE ROL
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar_rol'])) {
    $id_usuario = $_POST['user_id'];
    $nuevo_rol = $_POST['nuevo_rol'];
    
    // Evitar que el admin se quite el permiso a sí mismo por error
    if ($id_usuario == $_SESSION['usuario_id'] && $nuevo_rol !== 'admin') {
        $mensaje = "⚠️ No puedes quitarte el rol de admin a ti mismo.";
    } else {
        $update = $conn->query("UPDATE usuarios SET rol = '$nuevo_rol' WHERE id = '$id_usuario'");
        if ($update) {
            $mensaje = "✅ Rol actualizado correctamente.";
        } else {
            $mensaje = "❌ Error al actualizar.";
        }
    }
}

// OBTENER TODOS LOS USUARIOS
$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador - 8VA ReBaNaDa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo_index.css"> 
    <style>
        /* Estilos específicos para el panel */
        body { padding-top: 100px; background-color: #f4f4f4; }
        .admin-container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #b22222; text-align: center; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #b22222; color: white; }
        tr:hover { background-color: #f9f9f9; }
        
        select { padding: 8px; border-radius: 5px; border: 1px solid #ccc; font-size: 1rem; }
        
        .btn-guardar { 
            background: #28a745; color: white; border: none; padding: 8px 15px; 
            border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s;
        }
        .btn-guardar:hover { background: #218838; }
        
        .alerta { text-align: center; background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .alerta.error { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }

        .header-admin { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .btn-salir { background: #333; color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; }
        .btn-salir:hover { background: #555; }

        /* Responsive */
        @media (max-width: 768px) {
            th, td { padding: 10px 5px; font-size: 0.9rem; }
            select { width: 100%; margin-bottom: 5px; }
            form { display: flex; flex-direction: column; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo"><span>🍕</span></div>
    <h1 class="titulo">Panel Admin</h1>
    <div class="acciones-header">
        <a href="logout.php" class="btn-login" style="border:none;">Cerrar Sesión</a>
    </div>
</header>

<main>
    <div class="admin-container">
        <div class="header-admin">
            <h2>⚙️ Gestión de Usuarios y Roles</h2>
            <span>Admin: <strong><?php echo $_SESSION['usuario']; ?></strong></span>
        </div>

        <?php if(!empty($mensaje)) echo "<div class='alerta'>$mensaje</div>"; ?>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol Actual</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $usuarios->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                        <td>
                            <?php 
                                $bg = '#eee'; $color = '#333';
                                if($row['rol']=='admin') { $bg='#ffeeba'; $color='#856404'; }
                                if($row['rol']=='repartidor') { $bg='#b8daff'; $color='#004085'; }
                                if($row['rol']=='cliente') { $bg='#c3e6cb'; $color='#155724'; }
                            ?>
                            <span style="background:<?php echo $bg; ?>; color:<?php echo $color; ?>; padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                                <?php echo ucfirst($row['rol']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display:flex; gap:5px; align-items:center;">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <select name="nuevo_rol">
                                    <option value="cliente" <?php echo ($row['rol']=='cliente')?'selected':''; ?>>Cliente</option>
                                    <option value="repartidor" <?php echo ($row['rol']=='repartidor')?'selected':''; ?>>Repartidor</option>
                                    <option value="admin" <?php echo ($row['rol']=='admin')?'selected':''; ?>>Admin</option>
                                </select>
                                <button type="submit" name="actualizar_rol" class="btn-guardar">💾</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>