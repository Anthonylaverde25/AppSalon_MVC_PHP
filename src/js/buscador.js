document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

function iniciarApp() {
  buscarPorFecha();
}

function buscarPorFecha() {
  const fechaInput = document.querySelector("#fecha");
  fechaInput.addEventListener("input", (e) => {
    const fechaSelecionada = e.target.value;
    window.location = `?fecha=${fechaSelecionada}`;
    console.log(fechaSelecionada);
  });
}
