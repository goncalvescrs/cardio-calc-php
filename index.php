<?php
  require_once 'src/calcularAvaliacaoFisio.php';

  $exibirResultado = false;
  $dadosPaciente = [];
  $erroMensagem = '';

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
          
    $nome = !empty($_POST['nome']) ? trim($_POST['nome']) : "Anônimo";
    $idade = isset($_POST['idade']) ? (int)$_POST['idade'] : 0;
    $genero = $_POST['genero'] ?? '';
    $peso = isset($_POST['peso']) ? (float)$_POST['peso'] : 0;
    $altura = isset($_POST['altura']) ? (float)$_POST['altura'] : 0;

    $resposta = calcularAvaliacaoFisio($nome, $idade, $genero, $peso, $altura);
    
    if (isset($resposta['sucesso']) && $resposta['sucesso'] === true) {
        $dadosPaciente = $resposta;
        $exibirResultado = true;
    } else {
        $erroMensagem = $resposta['mensagem'] ?? 'Erro ao processar os dados.';
    }

    sleep(1);
  }
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FisioHelp - Avaliação</title>
    <link rel="stylesheet" href="styles/style.css" />
    <script src="js/script.js" defer></script>
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

        <form id="formCalculadora" method="POST" action="">
          <div class="input-group">
            <label for="nome">Nome do Paciente</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" onfocus="this.value=''"/>
          </div>
          
          <div class="grid-inputs">
            <div class="input-group">
              <label for="idade">Idade (anos)</label>
              <input type="number" id="idade" name="idade" value="<?= htmlspecialchars($_POST['idade'] ?? '') ?>" onfocus="this.value=''"/>
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
              <input type="number" step="any" id="peso" name="peso" value="<?= htmlspecialchars($_POST['peso'] ?? '') ?>" onfocus="this.value=''"/>
            </div>
            
            <div class="input-group">
              <label for="altura">Altura (cm)</label>
              <input type="number" step="any" id="altura" name="altura" value="<?= htmlspecialchars($_POST['altura'] ?? '') ?>" onfocus="this.value=''"/>
            </div>
          </div>

          <button type="submit" id="btnCalcular" >
            <span id="btn-text">Calcular Valores</span>
          </button>
        </form>
      </main>

      <?php if ($exibirResultado): ?>
        <section id="resultado" class="result-section">
            <div class="paciente-info-grid">
              <p><strong>Paciente:</strong> <span id="res-nome"><?= htmlspecialchars($nome) ?></span></p>
              
              <div class="paciente-grid">
                  <p><strong>Idade:</strong> <span id="res-idade"><?= htmlspecialchars($idade) ?></span> anos</p>
                  <p><strong>Gênero:</strong> <span id="res-genero"><?= htmlspecialchars($genero) ?></span></p>
                  <p><strong>Peso:</strong> <span id="res-peso"><?= htmlspecialchars($peso) ?></span> kg</p>
                  <p><strong>Altura:</strong> <span id="res-altura"><?= htmlspecialchars($altura) ?></span> cm</p>
              </div>
          </div>

          <h4 class="section-title">Predito Manovacuometria</h4>
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
            Predito Dinamometria
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

          <h4 class="section-title" style="margin-top: 20px">
            Predito TC6M
          </h4>
          <div class="result-cards third-result">
            <div class="card">
              <h4>Distância Prevista</h4>
              <p><span id="dom-val"><?= $dadosPaciente['tc6m'] ?></span> <small>m</small></p>
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