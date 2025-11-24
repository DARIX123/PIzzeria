const canvas = document.getElementById("pizzas-canvas");
const ctx = canvas.getContext("2d");

canvas.style.position = "fixed";
canvas.style.top = "0";
canvas.style.left = "0";
canvas.style.width = "100%";
canvas.style.height = "100%";
canvas.style.pointerEvents = "none";
canvas.style.zIndex = "1";

function resizeCanvas(){
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

// Imagen mini-pizza
const pizzaImage = new Image();
pizzaImage.src = "img/pizza.png";

let particles = [];

function createParticle() {
    const size = Math.random() * 25 + 15;

    particles.push({
        x: Math.random() * canvas.width,
        y: -20,
        speedY: Math.random() * 2 + 1,
        speedX: Math.random() * 1 - 0.5,
        size: size,
        rotation: Math.random() * 360,
        rotationSpeed: Math.random() * 2 - 1
    });
}

function updateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particles.forEach((p, index) => {
        p.y += p.speedY;
        p.x += p.speedX;
        p.rotation += p.rotationSpeed;

        // eliminar si sale de pantalla
        if (p.y > canvas.height + 50) {
            particles.splice(index, 1);
        }

        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.rotation * Math.PI / 180);
        ctx.drawImage(pizzaImage, -p.size / 2, -p.size / 2, p.size, p.size);
        ctx.restore();
    });
}

setInterval(createParticle, 300); // nueva pizza cada 300ms

function loop() {
    updateParticles();
    requestAnimationFrame(loop);
}
loop();
