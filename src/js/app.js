// Declaración de variables para el flujo de pasos y datos de la cita
let paso = 1; // Define el paso actual en 1
const pasoInicial = 1; // Establece el primer paso como 1
const pasoFinal = 3; // Establece el último paso como 3

const cita = {
  id: "",
  nombre: "",
  fecha: "",
  hora: "",
  servicios: [],
};

// Espera a que el DOM esté completamente cargado antes de iniciar la aplicación
document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

// Función principal para iniciar la aplicación
function iniciarApp() {
  // Muestra la sección correspondiente al paso actual
  mostrarSeccion();

  // Configura la funcionalidad de los tabs
  tabs();

  // Configura la visibilidad de los botones de paginación
  configurarBotonesPaginador();

  // Maneja el evento de avanzar a la siguiente página
  paginaSiguiente();

  // Maneja el evento de retroceder a la página anterior
  paginaAnterior();

  // Consulta y muestra los servicios desde una API
  consultarAPI();

  // ID del cliente
  idCliente();

  // Nombre del cliente pasado al objeto cita
  nombreCliente();

  // Seleccionar fecha para ser pasada al objeto cita
  seleccionarFecha();

  // Seleccionar hora para ser pasada al objeto cita
  seleccionarHora();

  // Mostrar resumen
  mostrarResumen();
}

// Función que muestra la sección correspondiente al paso actual
function mostrarSeccion() {
  // Oculta la sección que estaba visible previamente
  const seccionAnterior = document.querySelector(".mostrar");
  if (seccionAnterior) {
    seccionAnterior.classList.remove("mostrar");
  }

  // Muestra la sección actual según el paso
  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add("mostrar");

  // Actualiza la clase del tab para resaltar el paso actual
  const tabAnterior = document.querySelector(".actual");
  if (tabAnterior) {
    tabAnterior.classList.remove("actual");
  }
  const tab = document.querySelector(`[data-paso="${paso}"]`);
  tab.classList.add("actual");
}

// Configura los eventos de los tabs para cambiar de sección
function tabs() {
  const botonesTabs = document.querySelectorAll(".tabs button");
  botonesTabs.forEach((boton) => {
    boton.addEventListener("click", (e) => {
      // Actualiza el paso y muestra la nueva sección correspondiente
      paso = parseInt(e.target.dataset.paso);
      mostrarSeccion();
      configurarBotonesPaginador();
    });
  });
}

// Configura la visibilidad de los botones de paginación según el paso actual
function configurarBotonesPaginador() {
  const botonAnterior = document.querySelector("#anterior");
  const botonSiguiente = document.querySelector("#siguiente");

  if (paso === 1) {
    // Si estamos en el primer paso, oculta el botón "Anterior" y muestra el botón "Siguiente"
    botonAnterior.classList.add("ocultar");
    botonSiguiente.classList.remove("ocultar");
  } else if (paso === 3) {
    // Si estamos en el último paso, muestra el botón "Anterior" y oculta el botón "Siguiente"
    botonAnterior.classList.remove("ocultar");
    botonSiguiente.classList.add("ocultar");
    mostrarResumen();
  } else {
    // Si estamos en un paso intermedio, muestra ambos botones
    botonAnterior.classList.remove("ocultar");
    botonSiguiente.classList.remove("ocultar");
  }
}

// Maneja el evento de retroceder a la página anterior
function paginaAnterior() {
  const botonAnterior = document.querySelector("#anterior");

  botonAnterior.addEventListener("click", function () {
    if (paso <= pasoInicial) return; // Evita retroceder más allá del primer paso
    paso--; // Disminuye el valor del paso actual
    configurarBotonesPaginador();
    mostrarSeccion();
  });
}

// Maneja el evento de avanzar a la siguiente página
function paginaSiguiente() {
  const botonSiguiente = document.querySelector("#siguiente");

  botonSiguiente.addEventListener("click", function () {
    if (paso >= pasoFinal) return; // Evita avanzar más allá del último paso
    paso++; // Aumenta el valor del paso actual
    configurarBotonesPaginador();
    mostrarSeccion();
  });
}

// Consulta una API y muestra los servicios obtenidos
async function consultarAPI() {
  try {
    const url = "/api/servicios";
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch (error) {
    console.log(error);
  }
}

// Muestra los servicios en la interfaz
function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;

    // Crea elementos para mostrar el nombre y precio del servicio
    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("nombre-servicio");
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.classList.add("precio-servicio");
    precioServicio.textContent = `$ ${precio}`;

    // Crea un contenedor para el servicio con su ID y función onclick
    const servicioDiv = document.createElement("DIV");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    // Agrega los elementos al contenedor del servicio
    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    // Agrega el contenedor del servicio al contenedor de servicios en la interfaz
    document.querySelector("#servicios").appendChild(servicioDiv);
  });
}

// Maneja la selección de un servicio
function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;
  const divSeleccionado = document.querySelector(`[data-id-servicio="${id}"]`);

  // Comprueba si un servicio ya fue agregado a la cita
  if (servicios.some((agregado) => agregado.id === id)) {
    // Si el servicio ya está presente en la cita, se elimina
    // Utiliza el método 'filter' para crear un nuevo arreglo sin el servicio actual
    cita.servicios = servicios.filter((guardado) => guardado.id !== id);
    divSeleccionado.classList.remove("seleccionado");
  } else {
    // Si el servicio no está en la cita, se añade
    // Utiliza el operador spread para agregar el servicio al arreglo de servicios
    cita.servicios = [...servicios, servicio];
    divSeleccionado.classList.add("seleccionado");
  }

  console.log(cita); // Muestra el estado actual de la cita
}

// Nombre del cliente para ser pasado al objeto de cita
function nombreCliente() {
  cita.nombre = document.querySelector("#nombre").value;
}

// ID del cliente para ser pasado al objeto de cita
function idCliente() {
  cita.id = document.querySelector("#id").value;
}

// Fecha de la cita para ser pasada al objeto de cita
function seleccionarFecha() {
  const inputFecha = document.querySelector("#fecha");

  inputFecha.addEventListener("input", (e) => {
    const dia = new Date(e.target.value).getUTCDay();

    if ([0, 6].includes(dia)) {
      e.target.value = "";
      mostrarAlerta("Fines de semana no permitidos", "error", ".formulario");
    } else {
      cita.fecha = e.target.value;
    }
  });
}

// Hora de la cita para pasar al objeto cita
function seleccionarHora() {
  const inputHora = document.querySelector("#hora");
  inputHora.addEventListener("input", (e) => {
    const horaCita = e.target.value;

    const hora = horaCita.split(":")[0];
    if (hora < 10 || hora > 18) {
      e.target.value = "";
      mostrarAlerta("Fuera de horario", "error", ".formulario");
    } else {
      cita.hora = e.target.value;
      console.log(cita);
    }
  });
}

// Resumen servicio
function mostrarResumen() {
  const resumen = document.querySelector(".contenido-resumen");

  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  if (Object.values(cita).includes("") || cita.servicios.length === 0) {
    console.log("hacen falta campos");
    mostrarAlerta(
      "Faltan datos de servicios, Fecha y Hora",
      "error",
      ".contenido-resumen",
      false
    );

    return;
  }

  // Formatear el div de resumen
  const { nombre, fecha, hora, servicios } = cita;

  // Heading para servicios en resumen
  const headingServicios = document.createElement("H3");
  headingServicios.textContent = "Resumen de servicios";
  resumen.appendChild(headingServicios);

  servicios.forEach((servicio) => {
    const { id, precio, nombre } = servicio;

    const contenedorServicio = document.createElement("DIV");
    contenedorServicio.classList.add("contenedor-servicio");

    const textoServicio = document.createElement("p");
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    resumen.appendChild(contenedorServicio);
  });

  // Heading para servicios en resumen
  const headingCita = document.createElement("H3");
  headingCita.textContent = "Resumen de cita";
  resumen.appendChild(headingCita);

  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // Formatear la fecha en español
  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth();
  const dia = fechaObj.getDate() + 2;
  const year = fechaObj.getFullYear();

  const fechaUTC = new Date(Date.UTC(year, mes, dia));
  const opciones = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fechaFormateada = fechaUTC.toLocaleDateString("es-AR", opciones);
  console.log(fechaFormateada);

  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

  const horaCita = document.createElement("p");
  horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

  const botonReservar = document.createElement("BUTTON");
  botonReservar.classList.add("boton");
  botonReservar.textContent = "Reservar Cita";
  botonReservar.onclick = reservarCita;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);

  resumen.appendChild(botonReservar);
}

// Reservar cita
async function reservarCita() {
  // Desestructura los datos de la cita: nombre, fecha, hora y servicios
  const { nombre, fecha, hora, servicios, id } = cita;

  // Crea un array con los IDs de los servicios de la cita
  const idServicio = servicios.map((servicio) => servicio.id);

  // Crea un objeto FormData para enviar los datos al servidor
  const datos = new FormData();
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("usuarioId", id);
  datos.append("servicios", idServicio);

  // URL a la cual se realizará la solicitud POST
  try {
    const url = "/api/citas";

    // Realiza una solicitud POST al servidor con los datos proporcionados
    const respuesta = await fetch(url, {
      method: "POST",
      body: datos, // Envía los datos en el cuerpo de la solicitud
    });

    // Espera la respuesta del servidor y conviértela a formato JSON
    const resultado = await respuesta.json();

    // Imprime el resultado en la consola
    console.log(resultado.resultado.resultado);

    if (resultado.resultado.resultado === true) {
      Swal.fire({
        icon: "success",
        title: "Cita Creada",
        text: "Tu cita fue creada correctamente",
        button: "ok",
      }).then(() => {
        setTimeout(() => {
          window.location.reload();
        }, 3000);
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al guardar la cita",
    });
  }
}

// Alertas
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  // Previene que se generen mas de una alerta
  const alertaPrevia = document.querySelector(".alerta");
  if (alertaPrevia) {
    alertaPrevia.remove();
  }

  // Scripting para crear la alerta
  const alerta = document.createElement("DIV");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta");
  alerta.classList.add(tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  // Eliminar la alerta
  if (desaparece) {
    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }
}
