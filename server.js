const express = require('express');
const mysql = require('mysql2');
const WebSocket = require('ws');
const app = express();

// --------------------------
// Conexión MySQL
// --------------------------
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'pizzeria'
});

db.connect(err => {
  if(err) console.error("❌ MySQL Error:", err);
  else console.log("✅ Conectado a MySQL");
});

// --------------------------
// Endpoint HTTP para notificar pedido actualizado (PHP lo llamará)
// --------------------------
app.get("/verificar_pedido.php", (req, res) => {
    const pedidoId = req.query.pedido_id;
    if(!pedidoId) return res.send("Falta pedido_id");

    console.log(`✅ Pedido ${pedidoId} ENTREGADO (notificado por PHP)`);

    // 🔴 Enviar mensaje a todos los clientes WebSocket conectados
    wss.clients.forEach(client => {
        if(client.readyState === WebSocket.OPEN){
            client.send(`pedido_actualizado:${pedidoId}`);
            console.log("Mensaje WebSocket enviado a cliente:", pedidoId);
        }
    });

    res.send("ok");
});

// --------------------------
// Iniciar servidor HTTP + WebSocket
// --------------------------
const server = app.listen(3000, () => console.log("🚀 Node.js corriendo en http://localhost:3000"));

const wss = new WebSocket.Server({ server });

wss.on('connection', ws => {
    console.log("🔵 Cliente WebSocket conectado");

    ws.on('message', message => {
        console.log("📩 Mensaje recibido del cliente:", message);
    });

    ws.on('close', () => {
        console.log("❌ Cliente WebSocket desconectado");
    });
});
