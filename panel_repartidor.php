<?php
include("API/conexion.php");
include("roles.php");

// 🔹 Solo repartidores pueden acceder
permitirSolo(['repartidor']);

// 🔹 Obtener pedidos completados asignados a este repartidor
$repartidor_id = $_SESSION['usuario_id'];
$pedidosCompletados = $conn->query("
    SELECT c.*, p.imagen 
    FROM compras c 
    LEFT JOIN productos p ON c.producto = p.nombre 
    WHERE c.estado='entregado' AND c.repartidor_id = '$repartidor_id'
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Repartidor - 8VA ReBaNaDa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- VARIABLES Y RESET --- */
        :root {
            --primary: #b22222;
            --primary-dark: #8b1a1a;
            --bg-color: #f4f7f6;
            --text-color: #333;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        /* --- HEADER --- */
        header {
            background: var(--primary);
            color: var(--white);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* --- MAIN CONTAINER --- */
        main {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        /* --- BOTÓN DE ESCANEO --- */
        .action-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .panel-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(178, 34, 34, 0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .panel-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(178, 34, 34, 0.6);
        }

        /* --- ESCÁNER --- */
        #scanner {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 3px solid var(--primary);
        }

        /* --- TABLA / LISTA DE PEDIDOS --- */
        .table-container {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 20px;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f9f9f9;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        td img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        /* --- BOTÓN VER TICKET --- */
        .ver-ticket {
            background-color: #2ecc71;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background 0.3s;
            display: inline-block;
        }

        .ver-ticket:hover {
            background-color: #27ae60;
        }

        /* --- BOTÓN REGRESAR --- */
        #regresar {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #777;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        #regresar:hover {
            color: var(--primary);
        }

        .btn-login {
  border: 2px solid #d14b00;
  background: transparent;
  color: #d14b00;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-right: 30px;
  
}

.btn-login:hover {
  background: #d10000;
  color: white;
}

        /* --- RESPONSIVE TABLE (TRANSFORMACIÓN A TARJETAS) --- */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 10px;
                margin-bottom: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                padding: 15px;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
                padding-top: 5px;
                padding-bottom: 5px;
                text-align: right;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: #555;
            }

            td img {
                float: right;
            }
            
            .ver-ticket {
                display: block;
                text-align: center;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-motorcycle"></i> Panel Repartidor</h1>
    <div class="user-info">
        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
    </div>
    <a href="logout.php" class="btn-login" data-i18n="cerrar-sesion">Cerrar sesión</a>
    

</header>

<main>
    <div class="action-area">
        <button class="panel-btn" id="btnEscanear">
            <i class="fas fa-qrcode"></i> Escanear Pedido
        </button>
    </div>

    <div id="scanner" style="display:none;"></div>

    <div class="table-container">
        <h2><i class="fas fa-check-circle"></i> Historial de Entregas</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Total</th>
                    <th>Tipo</th>
                    <th>Dirección</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($f = $pedidosCompletados->fetch_assoc()): ?>
                <tr>
                    <td data-label="Imagen">
                        <img src="<?php echo !empty($f['imagen']) ? $f['imagen'] : 'img/default.png'; ?>" alt="Prod">
                    </td>
                    <td data-label="Producto"><strong><?php echo htmlspecialchars($f['producto']); ?></strong></td>
                    <td data-label="Cantidad"><?php echo $f['cantidad']; ?></td>
                    <td data-label="Total" style="color:var(--primary); font-weight:bold;">$<?php echo $f['total']; ?></td>
                    <td data-label="Tipo">
                        <span style="background:#eee; padding:3px 8px; border-radius:4px; font-size:0.8rem;">
                            <?php echo ucfirst($f['tipo_entrega']); ?>
                        </span>
                    </td>
                    <td data-label="Dirección" style="font-size:0.9rem; color:#555;">
                        <?php echo htmlspecialchars($f['direccion_entrega']); ?>
                    </td>
                    <td data-label="Acción">
                        <a href="ver_pedido.php?pedido_id=<?php echo $f['pedido_id']; ?>" class="ver-ticket" target="_blank">
                            <i class="fas fa-receipt"></i> Ticket
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="panel_repartidor.php" id="regresar"><i class="fas fa-sync-alt"></i> Actualizar Panel</a>
</main>

<script src="js/html5-qrcode.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnEscanear = document.getElementById('btnEscanear');
    const scannerDiv = document.getElementById('scanner');
    let html5QrcodeScanner;

    btnEscanear.addEventListener('click', () => {
        // Efecto visual: Mostrar scanner y ocultar botón
        scannerDiv.style.display = 'block';
        btnEscanear.style.display = 'none'; // Opcional: ocultar botón mientras escanea
        
        html5QrcodeScanner = new Html5Qrcode("scanner");

        Html5Qrcode.getCameras().then(cameras => {
            if(cameras && cameras.length){
                // Buscar la cámara trasera
                let backCamera = cameras.find(cam => cam.label.toLowerCase().includes("back") || cam.label.toLowerCase().includes("environment"));
                let cameraId = backCamera ? backCamera.id : cameras[0].id;

                html5QrcodeScanner.start(
                    cameraId,
                    { fps: 10, qrbox: 250 },
                    qrCodeMessage => {
                        console.log("QR escaneado:", qrCodeMessage);
                        // Detener escáner antes de redirigir
                        html5QrcodeScanner.stop().then(() => {
                            window.location.href = qrCodeMessage;
                        });
                    },
                    errorMessage => {
                        // console.warn("Error QR:", errorMessage);
                    }
                );
            }
        }).catch(err => console.error("No se pudo acceder a las cámaras:", err));
    })
});
</script>

</body>
</html>