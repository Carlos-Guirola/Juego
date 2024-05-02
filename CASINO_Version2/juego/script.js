
const depurando = document.getElementById('depurador');
const ancho = 79;
const altura = 79;
const cantidadFiguras = 8;
const tiempoPorFigura = 100;
const posiciones = [0, 0, 0, 0];
const girar = (carrete, offset = 0) => {
  const delta = (offset + 2) * cantidadFiguras + Math.round(Math.random() * cantidadFiguras);
  return new Promise((resolve, reject) => {
    const estilo = getComputedStyle(carrete);
    const posicionFondoY = parseFloat(estilo["background-position-y"]);
    const objetivoPosicionFondoY = posicionFondoY + delta * altura;
    const normObjetivoPosicionFondoY = objetivoPosicionFondoY % (cantidadFiguras * altura);
    setTimeout(() => {
      carrete.style.transition = `background-position-y ${(8 + 1 * delta) * tiempoPorFigura}ms cubic-bezier(.41,-0.01,.63,1.09)`;
      carrete.style.backgroundPositionY = `${posicionFondoY + delta * altura}px`;
    }, offset * 150);
    setTimeout(() => {
      carrete.style.transition = `none`;
      carrete.style.backgroundPositionY = `${normObjetivoPosicionFondoY}px`;
      resolve(delta % cantidadFiguras);
    }, (8 + 1 * delta) * tiempoPorFigura + offset * 150);
  });
};

function girarTodos() {
  const listaCarretes = document.querySelectorAll('.tragamonedas > .carretes');
  Promise.all([...listaCarretes].map((carrete, i) => girar(carrete, i)))
    .then(deltas => {
      deltas.forEach((delta, i) => posiciones[i] = (posiciones[i] + delta) % cantidadFiguras);
    
      if (posiciones[0] == posiciones[1] && posiciones[1] == posiciones[2] && posiciones[2] == posiciones[3]) {
        document.querySelector(".tragamonedas").classList.add("ganar3");
      } else if ((posiciones[0] == posiciones[1] && posiciones[1] == posiciones[2]) || (posiciones[1] == posiciones[2] && posiciones[2] == posiciones[3])) {
        document.querySelector(".tragamonedas").classList.add("ganar2");
      } else if (posiciones[0] == posiciones[1] || posiciones[1] == posiciones[2] || posiciones[2] == posiciones[3]) {
        document.querySelector(".tragamonedas").classList.add("ganar1");
      }
      setTimeout(() => document.querySelector(".tragamonedas").classList.remove("ganar3", "ganar2", "ganar1"), 2000);
    });
}
const spinButton = document.getElementById('spinButton');
botonJugar.addEventListener('click', function() {
  girarTodos();
});
