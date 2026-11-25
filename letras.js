// Modo práctica: oculta los significados y se revelan al tocar cada tarjeta
const btnPractica = document.getElementById('btnPractica');
if (btnPractica) {
    btnPractica.addEventListener('click', () => {
        const activo = document.body.classList.toggle('practica');
        btnPractica.classList.toggle('activo', activo);
        btnPractica.textContent = activo ? '👀 Mostrar todo' : '🙈 Modo práctica';
        document.querySelectorAll('.letra-card').forEach(c => c.classList.remove('revelado'));
    });
}
document.querySelectorAll('.letra-card').forEach(card => {
    card.addEventListener('click', () => {
        if (document.body.classList.contains('practica')) card.classList.toggle('revelado');
    });
});