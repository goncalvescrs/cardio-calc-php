<?php
  // 1. IMPORTAÇÃO DA LÓGICA (O "Use Case")
  require_once 'src/calcularAvaliacaoFisio.php';

  // 2. ESTADO INICIAL DA TELA
  $exibirResultado = false;
  $dadosPaciente = [];
  $erroMensagem = '';

  // 3. FLUXO DE CONTROLE (Interceptando o POST do formulário)
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    sleep(1);
          

    // Captura e sanitiza os dados que vieram do HTML
    $nome = !empty($_POST['nome']) ? trim($_POST['nome']) : "Anônimo";
    $idade = isset($_POST['idade']) ? (int)$_POST['idade'] : 0;
    $genero = $_POST['genero'] ?? '';
    $peso = isset($_POST['peso']) ? (float)$_POST['peso'] : 0;
    $altura = isset($_POST['altura']) ? (float)$_POST['altura'] : 0;

    // Chama a regra de negócio isolada
    $resposta = calcularAvaliacaoFisio($nome, $idade, $genero, $peso, $altura);
    
    // Verifica o estado da resposta para decidir o que renderizar
    if (isset($resposta['sucesso']) && $resposta['sucesso'] === true) {
        $dadosPaciente = $resposta;
        $exibirResultado = true;
    } else {
        $erroMensagem = $resposta['mensagem'] ?? 'Erro ao processar os dados.';
    }
  }
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FisioHelp - Avaliação</title>
    <link rel="stylesheet" href="styles/style.css" />
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Calculadora</h1>
        <p>Valores Preditivos Cardiorresp. e Força</p>
      </header>

      <main class="form-section">
        <div id="error-container">
            <?php if (!empty($erroMensagem)): ?>
                <div class="error-text"><?= htmlspecialchars($erroMensagem) ?></div>
            <?php endif; ?>
        </div>

        <form method="POST" action="#resultado">
          <div class="input-group">
            <label for="nome">Nome do Paciente</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" />
          </div>

          <div class="grid-inputs">
            <div class="input-group">
              <label for="idade">Idade (anos)</label>
              <input type="number" id="idade" name="idade" value="<?= htmlspecialchars($_POST['idade'] ?? '') ?>" />
            </div>
            
            <div class="input-group">
              <label for="genero">Gênero</label>
              <select id="genero" name="genero" >
                <option value="" disabled <?= empty($_POST['genero']) ? 'selected' : '' ?>>Selecione...</option>
                <option value="masculino" <?= (isset($_POST['genero']) && $_POST['genero'] === 'masculino') ? 'selected' : '' ?>>Masculino</option>
                <option value="feminino" <?= (isset($_POST['genero']) && $_POST['genero'] === 'feminino') ? 'selected' : '' ?>>Feminino</option>
              </select>
            </div>
            
            <div class="input-group">
              <label for="peso">Peso (kg)</label>
              <input type="number" step="any" id="peso" name="peso" value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>" />
            </div>
            
            <div class="input-group">
              <label for="altura">Altura (cm)</label>
              <input type="number" step="any" id="altura" name="altura" value="<?= htmlspecialchars($_POST['altura'] ?? '') ?>" />
            </div>
          </div>

          
          <!-- <button type="submit" id="btnCalcular">
            <span id="btn-text">Calcular Valores</span>
          </button> -->

          <button type="submit" id="btnCalcular" 
            onclick="this.innerHTML='<div class=\'spinner\'></div> Calculando...';">
            <span id="btn-text">Calcular Valores</span>
          </button>
        </form>
      </main>

      <?php if ($exibirResultado): ?>
        <section id="resultado" class="result-section">
          <h3>Paciente: <span id="res-nome"><?= htmlspecialchars($dadosPaciente['nome']) ?></span></h3>

          <h4 class="section-title">Manovacuometria Prevista</h4>
          <div class="result-cards">
            <div class="card">
              <h4>PImax</h4>
              <p><span id="pi-val"><?= $dadosPaciente['pimax'] ?></span> <small>cmH2O</small></p>
            </div>
            <div class="card">
              <h4>PEmax</h4>
              <p><span id="pe-val"><?= $dadosPaciente['pemax'] ?></span> <small>cmH2O</small></p>
            </div>
          </div>

          <h4 class="section-title" style="margin-top: 20px">
            Dinamometria Prevista
          </h4>
          <div class="result-cards secondary-result">
            <div class="card">
              <h4>Mão Dominante</h4>
              <p><span id="dom-val"><?= $dadosPaciente['dom'] ?></span> <small>kgf</small></p>
            </div>
            <div class="card">
              <h4>Não Dominante</h4>
              <p><span id="ndom-val"><?= $dadosPaciente['ndom'] ?></span> <small>kgf</small></p>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </div>

    <footer>
      <p>© 2026 | Calculadora da Isabella Eira</p>
    </footer>
  </body>
</html>