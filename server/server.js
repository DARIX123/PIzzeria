const express = require('express');
const http = require('http');
const WebSocket = require('ws');
const mysql = require('mysql2');

const app = express();
const server = http.createServer(app);
const wss = new WebSocket.Server({ server });

// Middleware para saltar el aviso de ngrok
app.use((req, res, next) => {
  res.setHeader("ngrok-skip-browser-warning", "true");
  next();
});

// Servir la carpeta "public"
app.use(express.static('public'));

// Conexión MySQL
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'pizzeria'
});

db.connect(err => {
  if (err) {
    console.error('Error conectando a MySQL:', err);
    return;
  }
  console.log('✅ Conectado a la base de datos MySQL');
});

// ---  Ruta para verificación QR ---
app.get('/verificar', (req, res) => {
  res.sendFile(__dirname + '/public/verificacion_qr.html');
});

// ---  WebSocket Conexion bidireccional) ---
wss.on('connection', (ws) => {
  console.log('🛰️ Cliente conectado al WebSocket');

  ws.on('message', (message) => {
    console.log('📩 Mensaje recibido:', message);

    if (message.toString().startsWith('usuario_abierto:')) {
      const userId = message.toString().split(':')[1];
      console.log(`Usuario ${userId} abrió la página de verificación`);

      // Actualizamos la base de datos
      const updateQuery = 'UPDATE usuarios SET verificado = 1 WHERE id = ?';
      db.query(updateQuery, [userId], (err, results) => {
        if (err) {
          console.error('❌ Error actualizando verificación:', err);
          ws.send(JSON.stringify({ status: 'error', message: 'DB update failed' }));
          return;
        }

        console.log(`✅ Usuario ${userId} marcado como verificado en la base de datos`);
        wss.clients.forEach((client) => {
            if (client.readyState === WebSocket.OPEN) {
                client.send("verificado:" + userId);
            }
        })
      });
    }
  });

  ws.on('close', () => {
    console.log('❎ Cliente desconectado del WebSocket');
  });
});

// Iniciar el servidor
server.listen(3000, () => {
  console.log('🚀 Servidor corriendo en http://localhost:3000');
});
