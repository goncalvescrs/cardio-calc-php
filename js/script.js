document.addEventListener("DOMContentLoaded", () => {
  const formulario = document.getElementById("formCalculadora");
  const botao = document.getElementById("btnCalcular");
  const resultado = document.getElementById("resultado");

  // spinner no clique
  if (formulario && botao) {
    formulario.addEventListener("submit", function () {
      botao.innerHTML = '<div class="spinner"></div> Calculando...';
      botao.style.pointerEvents = "none";
      botao.style.opacity = "0.8";
    });
  }

  // scroll para mostrar o resultado
  if (resultado) {
    resultado.scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }
});
