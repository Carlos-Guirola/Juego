// Obtener referencia al elemento de depuración 
const depurador = document.getElementById('depurador');

// Ancho y altura de los iconos
const ancho = 79;
const altura = 79;

// Número de iconos en los reels
const cantidadFiguras = 8;

// Velocidad máxima en ms para animar un icono hacia abajo
const tiempoPorFigura = 85;

// Contiene índices de iconos
const posiciones = [0, 0, 0, 0];

// Función para girar un carrete específico
const girar = (carrete, offset = 0) => {
  const delta = (offset + 2) * cantidadFiguras + Math.floor(Math.random() * (cantidadFiguras + 1));
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

// Función para girar todos los reels
function girarTodos() {
  const listaCarretes = document.querySelectorAll('.tragamonedas > .carretes');
  return Promise.all([...listaCarretes].map((carrete, i) => girar(carrete, i)))
    .then(deltas => {
      deltas.forEach((delta, i) => posiciones[i] = (posiciones[i] + delta) % cantidadFiguras);

      if (posiciones[0] === posiciones[1] && posiciones[1] === posiciones[2] && posiciones[2] === posiciones[3] && posiciones[0] !== 0) {
        document.querySelector(".tragamonedas").classList.add("ganar3");
        asignarPuntos(15);
      } else if (posiciones[0] === posiciones[1] && posiciones[2] === posiciones[3]) {
        document.querySelector(".tragamonedas").classList.add("ganar2");
        asignarPuntos(10);
      } else if ((posiciones[0] === posiciones[1] && posiciones[1] === posiciones[2]) || 
                 (posiciones[1] === posiciones[2] && posiciones[2] === posiciones[3]) || 
                 (posiciones[0] === posiciones[1] && posiciones[1] === posiciones[3]) || 
                 (posiciones[0] === posiciones[2] && posiciones[2] === posiciones[3])) {
        document.querySelector(".tragamonedas").classList.add("ganar3");
        asignarPuntos(8);
      } else if (posiciones[1] === posiciones[2]) {
        document.querySelector(".tragamonedas").classList.add("ganar4");
        asignarPuntos(6);
      } else if (posiciones[0] === posiciones[1] || 
                 posiciones[1] === posiciones[2] || 
                 posiciones[2] === posiciones[3]) {
        document.querySelector(".tragamonedas").classList.add("ganar1");
        asignarPuntos(4);
      } else if (posiciones[0] === posiciones[3]) {
        document.querySelector(".tragamonedas").classList.add("ganar4");
        asignarPuntos(2);
      }

      setTimeout(() => document.querySelector(".tragamonedas").classList.remove("ganar1", "ganar2", "ganar3", "ganar4"), 2000);
    });
}

// Obtener el botón de jugar
const spinButton = document.getElementById('botonJugar');
const reelSound = new Audio('../IMG/a1.mp3');

// Función para reproducir el sonido del carrete
function reproducirSonido() {
  reelSound.play().catch(error => {
    console.error('Error al reproducir el sonido:', error);
  });
}

// Agregar un controlador de eventos al botón
spinButton.addEventListener('click', function() {
  // Deshabilitar el botón mientras los carretes estén girando
  spinButton.disabled = true;

  fetch('spin.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'action=spin'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      if (data.spin >= 0) {
        reproducirSonido();
        girarTodos().then(() => {
          document.getElementById('spinCount').textContent = data.spin;
          spinButton.disabled = false;
        });
      } else {
        document.getElementById('spinCount').textContent = data.spin;
        spinButton.disabled = false;
      }
    } else {
      alert(data.message);
      spinButton.disabled = false;
    }
  })
  .catch(error => {
    console.error('Error:', error);
    spinButton.disabled = false;
  });
});

// Obtener referencia al elemento de "TOTAL WIN"
const totalWinElement = document.getElementById('tx');

// Función para asignar puntos adicionales al usuario y mostrarlos en "TOTAL WIN"
function asignarPuntos(puntos) {
  fetch('puntajes.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `action=increment_score&points=${puntos}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const newScore = data.new_score;
      document.querySelector('.coins').textContent = `Puntaje: ${newScore}`;
      totalWinElement.textContent = `TOTAL WIN: ${puntos}`;
    } else {
      console.error('Error al asignar puntos:', data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

