<?php

function calcularAvaliacaoFisio($nome, $idade, $genero, $peso, $altura) {
    // 1. Validação
    $validacao = validarPaciente($nome, $idade, $peso, $altura, $genero);
    if ($validacao !== true) {
        return ['sucesso' => false, 'mensagem' => $validacao];
    }

    // 2. Lógica de Manovacuometria
    if ($genero === "masculino") {
        $piPrevista = -0.8 * $idade + 155.3;
        $pePrevista = -0.81 * $idade + 165.3;
        $fatorGenero = 1;
    } else {
        $piPrevista = -0.49 * $idade + 110.4;
        $pePrevista = -0.61 * $idade + 115.6;
        $fatorGenero = 0;
    }

    // 3. Lógica de Dinamometria
    $forcaDominante = 39.996 - 0.382 * $idade + 0.174 * $peso + 13.628 * $fatorGenero;
    $forcaNaoDominante = 44.968 - 0.42 * $idade + 0.11 * $peso + 9.274 * $fatorGenero;

    // 4. Lógica de TC6M
    if ($genero === "masculino") {
        $distanciaPrevista = 7.57 * $altura - 5.02 * $idade - 1.76 * $peso - 309;
    } else {
        $distanciaPrevista = 2.11 * $altura - 2.29 * $peso - 5.78 * $idade + 667;
    }

    return [
        'sucesso' => true,
        'nome' => $nome,
        'pimax' => number_format($piPrevista, 1, '.', ''),
        'pemax' => number_format($pePrevista, 1, '.', ''),
        'dom' => number_format($forcaDominante, 1, '.', ''),
        'ndom' => number_format($forcaNaoDominante, 1, '.', ''),
        'tc6m' => number_format($distanciaPrevista, 1, '.', ''),
    ];
}

function validarPaciente($nome, $idade, $peso, $altura, $genero) {
    if (empty($nome) || empty($idade) || empty($peso) || empty($altura) || empty($genero)) {
        return "Preencha todos os campos corretamente.";
    }
    if ($idade < 1 || $idade > 110) {
        return "Insira uma IDADE válida!";
    }
    if ($peso < 15 || $peso > 350) {
        return "Insira um PESO válido!";
    }
    if ($altura < 50 || $altura > 250) {
        return "Insira uma ALTURA válida em centímetros! ";
    }
    
    return true;
}