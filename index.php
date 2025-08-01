<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <title>CHECKER</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Fontes & Icons -->
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
  />

  <style>
    /* Seu CSS original */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: "Orbitron", monospace;
      background: radial-gradient(circle, #1b1b2f 0%, #090a1a 100%);
      color: #f0f0f0;
      text-align: center;
    }
    .container {
      max-width: 960px;
      margin: 30px auto;
      padding: 25px;
      background-color: rgba(0, 0, 0, 0.65);
      border-radius: 20px;
      box-shadow: 0 0 20px #bb00ff88;
    }
    h5 {
      margin-bottom: 20px;
      color: #bb00ff;
      display: flex;
      align-items: center;
      font-size: 22px;
      justify-content: center;
      gap: 12px;
    }

    #apiSelect {
      width: 320px;
      height: 30px;
      font-weight: 700;
      font-size: 16px;
      padding: 2px 12px;
      background-color: #1a0a2a;
      border: 2px solid #bb00ff;
      color: #bb00ff;
      cursor: pointer;
      margin: 10px auto 20px auto;
      border-radius: 12px;
      transition: background-color 0.3s ease, color 0.3s ease;
      font-family: "Orbitron", monospace;
      display: block;
      line-height: 26px;
      text-align-last: center;
    }
    #apiSelect:hover,
    #apiSelect:focus {
      background-color: #33005a;
      color: #fff;
      outline: none;
    }

    textarea {
      width: 100%;
      height: 180px;
      background-color: #000;
      color: #fff;
      border-radius: 12px;
      border: none;
      padding: 12px;
      font-family: monospace;
      font-size: 15px;
      resize: vertical;
      overflow-y: auto;
      margin-bottom: 20px;
    }

    .btn {
      font-weight: 700;
      padding: 10px 18px;
      border: none;
      border-radius: 12px;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 0 10px #bb00ff55;
      transition: all 0.3s ease;
      min-width: 110px;
      user-select: none;
    }
    #start {
      background-color: #7e00ff;
      color: #fff;
      box-shadow: 0 0 15px #bb00ff;
    }
    #start:hover {
      background-color: #bb00ff;
      color: #000;
      box-shadow: 0 0 20px #ee33ff;
    }

    #pause {
      background-color: #ffaa00;
      color: #000;
      box-shadow: 0 0 15px #ffaa00cc;
    }
    #pause:hover {
      background-color: #ffcc33;
      box-shadow: 0 0 20px #ffdd55;
    }

    #stop {
      background-color: #ff0044;
      color: #fff;
      box-shadow: 0 0 15px #ff3366;
    }
    #stop:hover {
      background-color: #ff3366;
      box-shadow: 0 0 20px #ff6688;
    }

    #clear {
      background-color: #555555;
      color: #fff;
      box-shadow: 0 0 10px #888888;
    }
    #clear:hover {
      background-color: #777777;
      box-shadow: 0 0 15px #aaaaaa;
    }

    .button-group {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 25px;
    }

    #status-bar {
      margin-bottom: 20px;
      padding: 8px 12px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      font-size: 14px;
      display: flex;
      justify-content: center;
      gap: 20px;
      font-weight: 700;
      user-select: none;
    }

    #lives,
    #dies {
      white-space: pre-wrap;
      background: #0a0a0a;
      padding: 15px;
      border-radius: 15px;
      height: 160px;
      overflow-y: auto;
      font-family: monospace;
      margin-top: 5px;
      text-align: left;
    }
    #lives {
      color: #0f0;
    }
    #dies {
      color: #f55;
    }

    .section-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
    }
    .section-title > div {
      display: flex;
      gap: 10px;
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h5>
      <i class="fas fa-credit-card"></i> CHECKER
      <i class="fas fa-credit-card"></i>
    </h5>

    <label for="apiSelect">Selecionar API:</label>
    <select id="apiSelect">
      <option value="cvv2">CVV2</option>
      <option value="bb">VISA BB</option>
	  <option value="gringa">Gringa</option>
    </select>

    <div id="status-bar">
      <span id="status-msg">Aguardando...</span> |
      Testados: <span id="status-count">0</span> |
      Aprovados: <span id="aprovadas-count">0</span>
    </div>

    <textarea id="lista" placeholder="Lista de cartões... (até 500)"></textarea>

    <div class="button-group">
      <button class="btn" id="start"><i class="fas fa-play"></i> Iniciar</button>
      <button class="btn" id="pause"><i class="fas fa-pause"></i> Pausar</button>
      <button class="btn" id="stop"><i class="fas fa-stop"></i> Parar</button>
      <button class="btn" id="clear"><i class="fas fa-trash-alt"></i> Limpar</button>
    </div>

    <div class="section-title">
      <h5>Aprovadas</h5>
      <div>
        <button class="btn" id="copyLives"
          ><i class="fas fa-copy"></i> Copiar</button
        >
        <button class="btn" id="toggleLives"
          ><i class="fas fa-eye"></i> Mostrar</button
        >
      </div>
    </div>
    <div id="lives" class="hidden"></div>

    <div class="section-title">
      <h5>Reprovadas</h5>
      <div>
        <button class="btn" id="toggleDies"><i class="fas fa-eye"></i> Mostrar</button>
      </div>
    </div>
    <div id="dies" class="hidden"></div>
  </div>

  <!-- Sons -->
  <audio
    id="startSound"
    src="https://www.soundjay.com/button/beep-07.wav"
    preload="auto"
  ></audio>
  <audio
    id="aprovadoSound"
    src="https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg"
    preload="auto"
  ></audio>
  <audio
    id="endSound"
    src="https://www.soundjay.com/button/sounds/button-4.mp3"
    preload="auto"
  ></audio>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    let lista = [],
      paused = false,
      stopped = true,
      aprovadas = 0,
      activeThreads = 0,
      maxThreads = 2;

    function updateStatus(msg) {
      $("#status-msg").text(msg);
    }

    function updateProgress(totalInicial) {
      const testados = totalInicial - lista.length;
      $("#status-count").text(testados);
    }

    function worker(totalInicial) {
      if (paused || stopped) {
        if (lista.length === 0 && activeThreads === 0) {
          updateStatus("Teste finalizado.");
          document.getElementById("endSound").play();
          toastr.info("Todos os cartões foram testados.");
        }
        return;
      }
      const item = lista.shift();
      if (!item) {
        activeThreads--;
        if (activeThreads === 0) {
          updateStatus("Teste finalizado.");
          document.getElementById("endSound").play();
          toastr.info("Todos os cartões foram testados.");
        }
        return;
      }
      activeThreads++;
      const selectedApi = "apis5/" + $("#apiSelect").val() + ".php";

      $.get(selectedApi, { lista: item })
        .done(function (res) {
          if (res.includes("Aprovada")) {
            $("#lives").append(res + "\n");
            aprovadas++;
            $("#aprovadas-count").text(aprovadas);
            document.getElementById("aprovadoSound").play();
            toastr.success("Live encontrada!");
            $.post("saveLives.php", { lives: res });
          } else {
            $("#dies").append(res + "\n");
          }
          $("#lista").val(lista.join("\n")).scrollTop(0);
          updateProgress(totalInicial);
          activeThreads--;
          setTimeout(() => worker(totalInicial), 50); // Delay menor para maior velocidade
        })
        .fail(function () {
          toastr.error("Erro ao testar cartão.");
          $("#lista").val(lista.join("\n")).scrollTop(0);
          updateProgress(totalInicial);
          activeThreads--;
          setTimeout(() => worker(totalInicial), 50);
        });
    }

    $("#start").click(function () {
      const inputText = $("#lista").val().trim();
      if (!inputText) return toastr.warning("Insira uma lista para iniciar.");

      lista = inputText.split("\n").filter((line) => line.trim() !== "");
      const totalInicial = lista.length;

      if (totalInicial > 150) {
        toastr.error("Limite máximo de 150 cartões excedido!");
        return;
      }

      aprovadas = 0;
      paused = false;
      stopped = false;
      activeThreads = 0;
      $("#lives, #dies").html("");
      $("#aprovadas-count").text("0");
      $("#status-count").text("0");
      document.getElementById("startSound").play();
      updateStatus("Executando...");
      toastr.success("Checker iniciado!");
      updateProgress(totalInicial);

      // Inicia as threads
      for (let i = 0; i < maxThreads; i++) {
        worker(totalInicial);
      }
    });

    $("#pause").click(() => {
      if (!stopped) {
        paused = !paused;
        updateStatus(paused ? "Pausado" : "Executando...");
        toastr.info(paused ? "Checker pausado." : "Execução retomada.");
        if (!paused) {
          for (let i = 0; i < maxThreads; i++) {
            worker(lista.length);
          }
        }
      }
    });

    $("#stop").click(() => {
      stopped = true;
      paused = false;
      updateStatus("Parado");
      toastr.error("Execução parada.");
    });

    $("#clear").click(() => {
      $("#lista").val("");
      $("#lives, #dies").html("");
      aprovadas = 0;
      $("#status-count").text("0");
      $("#aprovadas-count").text("0");
      updateStatus("Aguardando...");
      toastr.success("Lista e resultados limpos.");
    });

    $("#toggleDies").click(() => {
      $("#dies").toggleClass("hidden");
      $("#toggleDies").html(
        $("#dies").hasClass("hidden")
          ? '<i class="fas fa-eye"></i> Mostrar'
          : '<i class="fas fa-eye-slash"></i> Ocultar'
      );
    });

    $("#toggleLives").click(() => {
      $("#lives").toggleClass("hidden");
      $("#toggleLives").html(
        $("#lives").hasClass("hidden")
          ? '<i class="fas fa-eye"></i> Mostrar'
          : '<i class="fas fa-eye-slash"></i> Ocultar'
      );
    });

    $("#copyLives").click(() => {
      const aprovadasText = $("#lives").text().trim();
      if (!aprovadasText) {
        toastr.warning("Nenhuma aprovada para copiar.");
        return;
      }
      navigator.clipboard
        .writeText(aprovadasText)
        .then(() => {
          toastr.success("Aprovadas copiadas!");
        })
        .catch(() => {
          toastr.error("Erro ao copiar.");
        });
    });
  </script>
</body>
</html>
