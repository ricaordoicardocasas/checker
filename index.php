<?php
session_start();
error_reporting(0);

// Definir o fuso horário de Brasília
date_default_timezone_set('America/Sao_Paulo');

// Caminho para o arquivo JSON de usuários
$users_file = 'users.json';

// Função para carregar usuários do arquivo JSON
function load_users() {
    global $users_file;
    if (file_exists($users_file)) {
        $json = file_get_contents($users_file);
        return json_decode($json, true) ?: [];
    }
    return [];
}

// Função para salvar usuários no arquivo JSON
function save_users($users) {
    global $users_file;
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
}

// Carregar usuários
$users = load_users();

// Credenciais do administrador
$admin_user = "admin";
$admin_password = "da281009";

// Verificar login
if (isset($_POST['usuario'], $_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verificar se é o administrador
    if ($usuario === $admin_user && $senha === $admin_password) {
        $_SESSION['logado'] = true;
        $_SESSION['is_admin'] = true;
        header("Location: index.php");
        exit;
    } 
    // Verificar se é um usuário comum
    elseif (isset($users[$usuario]) && $users[$usuario]['password'] === $senha) {
        if ($users[$usuario]['expiration'] > time()) {
            $_SESSION['logado'] = true;
            $_SESSION['is_admin'] = false;
            $_SESSION['username'] = $usuario;
            $_SESSION['expiration'] = $users[$usuario]['expiration'];
            header("Location: index.php");
            exit;
        } else {
            $erro = "Conta expirada!";
        }
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

// Processar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Processar criação de novo usuário
if (isset($_POST['create_user'], $_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $new_user = $_POST['new_user'];
    $new_password = $_POST['new_password'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Converter horários para timestamp no fuso de Brasília
    $start_timestamp = strtotime($start_time . ' America/Sao_Paulo');
    $end_timestamp = strtotime($end_time . ' America/Sao_Paulo');

    // Validar horários
    if ($start_timestamp === false || $end_timestamp === false) {
        $admin_erro = "Formato de data/hora inválido!";
    } elseif ($end_timestamp <= time()) {
        $admin_erro = "Horário de expiração já passou!";
    } elseif ($end_timestamp <= $start_timestamp) {
        $admin_erro = "Horário de expiração deve ser posterior ao início!";
    } elseif (isset($users[$new_user])) {
        $admin_erro = "Usuário já existe!";
    } else {
        $users[$new_user] = [
            'password' => $new_password,
            'start_time' => date('Y-m-d H:i:s', $start_timestamp),
            'expiration' => $end_timestamp
        ];
        save_users($users);
        $admin_success = "Usuário $new_user criado com sucesso!";
    }
}

// Processar edição de usuário
if (isset($_POST['edit_user'], $_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $old_username = $_POST['old_username'];
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Converter horários para timestamp no fuso de Brasília
    $start_timestamp = strtotime($start_time . ' America/Sao_Paulo');
    $end_timestamp = strtotime($end_time . ' America/Sao_Paulo');

    // Validar horários
    if ($start_timestamp === false || $end_timestamp === false) {
        $admin_erro = "Formato de data/hora inválido!";
    } elseif ($end_timestamp <= time()) {
        $admin_erro = "Horário de expiração já passou!";
    } elseif ($end_timestamp <= $start_timestamp) {
        $admin_erro = "Horário de expiração deve ser posterior ao início!";
    } elseif (!isset($users[$old_username])) {
        $admin_erro = "Usuário não existe!";
    } elseif ($old_username !== $new_username && isset($users[$new_username])) {
        $admin_erro = "Novo nome de usuário já existe!";
    } else {
        // Atualizar usuário
        if ($old_username !== $new_username) {
            $users[$new_username] = $users[$old_username];
            unset($users[$old_username]);
        }
        $users[$new_username]['password'] = $new_password;
        $users[$new_username]['start_time'] = date('Y-m-d H:i:s', $start_timestamp);
        $users[$new_username]['expiration'] = $end_timestamp;
        save_users($users);
        $admin_success = "Usuário $new_username editado com sucesso!";
    }
}

// Processar exclusão de usuário
if (isset($_POST['delete_user'], $_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $delete_user = $_POST['delete_user'];
    if (isset($users[$delete_user])) {
        unset($users[$delete_user]);
        save_users($users);
        $admin_success = "Usuário $delete_user excluído com sucesso!";
    } else {
        $admin_erro = "Usuário não existe!";
    }
}

// Verificar expiração para usuário logado
if (isset($_SESSION['logado'], $_SESSION['username']) && !$_SESSION['is_admin']) {
    $username = $_SESSION['username'];
    if (isset($users[$username]) && $users[$username]['expiration'] <= time()) {
        session_destroy();
        header("Location: index.php?logout=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CHECKER GG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style type="text/css">
        .nav-tabs {
            background-color: #181A1E;
            border-radius: 5px;
            border: 1px solid rgb(205, 90, 161);
        }
        .nav-tabs li a {
            color: #fff;
        }
        .tab-content {
            background-color: #181A1E;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        .nav-tabs > li > a {
            border: medium none;
        }
        .nav-tabs > li > a:hover {
            background-color: #181A1E !important;
            border: medium none;
            border-radius: 0;
            color: #fff;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        .active {
            background-color: #181A1E !important;
        }
        textarea {
            background: #0F1116;
            color: #fff;
            width: 100%;
            border: none;
            padding: 10px;
            resize: none;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        textarea:focus {
            box-shadow: 0 0 0 0;
            border: 0 none;
            outline: 0;
        }
        .cookie-input {
            background: #14192e;
            color: #fff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 10px; /* Bordas arredondadas */
            border: 1px solid rgb(218, 87, 178);
        }
        .cookie-input:focus {
            box-shadow: 0 0 0 0;
            border: 0 none;
            outline: 0;
        }
        .cookie-submit-btn {
            background-color: #181A1E;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        .cookie-submit-btn:hover {
            background-color: #181A1E;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        button {
            padding: 10px 20px;
            background-color: #181A1E;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #181A1E;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        .thread-control {
            margin-top: 10px;
            padding: 10px;
            background: #181A1E;
            border-radius: 5px;
            border: 1px solid rgb(218, 87, 178);
        }
        .thread-slider {
            width: 100%;
        }
        .title-neon {
            font-size: 60px;
            font-weight: 900;
            background: linear-gradient(90deg, #ff00cc, rgb(221, 51, 255), #00ffcc);
            background-size: 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientFlow 3s ease infinite;
            text-shadow: 0 0 10px rgba(255, 0, 255, 0.7), 
                        0 0 20px rgba(0, 255, 255, 0.5);
            text-align: center;
            margin: 20px 0;
        }
        @keyframes gradientFlow {
            0% { background-position: 0% }
            50% { background-position: 100% }
            100% { background-position: 0% }
        }
        .btn-neon {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #ff00cc, rgb(255, 51, 255), #00ffcc);
            background-size: 300%;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.7), 
                        0 0 20px rgba(0, 255, 255, 0.5);
            transition: transform 0.3s ease;
            animation: gradientFlow 3s ease infinite;
        }
        .btn-neon:hover {
            transform: scale(1.1);
        }
        .btn-api {
            padding: 10px 25px;
            font-size: 20px;
            margin: 10px;
            border-radius: 30px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .btn-api:hover {
            transform: scale(1.1);
        }
        .btn-api-selected {
            border: 3px solid rgb(218, 87, 178);
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.7), 
                        0 0 20px rgba(0, 255, 255, 0.5);
        }
        .btn-erede { background: linear-gradient(90deg, #ff6200, #a100ff); }
        .btn-cvv2 { background: linear-gradient(90deg, #00ff00, #a100ff); }
        .btn-visa-bb { background: linear-gradient(90deg, #ffff00, #a100ff); }
        .btn-paypal { background: linear-gradient(90deg, #007bff, #a100ff); }
        .btn-stripe { background: linear-gradient(90deg, #8000ff, #a100ff); }
        #countdown {
            color: #ff0000;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body style="background: #0F1116;" class="p-3">
    <!-- Sons -->
    <audio id="startSound" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>
    <audio id="aprovadoSound" src="https://actions.google.com/sounds/v1/cartoon/clang_and_wobble.ogg" preload="auto"></audio>
    <audio id="endSound" src="https://www.soundjay.com/button/sounds/button-4.mp3" preload="auto"></audio>

<?php if (!isset($_SESSION['logado'])): ?>
    <h1 class="title-neon">CHECKER GG</h1>
    <div class="container text-white rounded shadow p-3 my-4" style="background: #181A1E; border-radius: 5px; border: 1px solid rgb(218, 87, 178);">
        <h3 class="text-center">Login</h3>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="usuario" class="form-control mb-3 cookie-input" placeholder="Usuário" required style="width: 100%;" />
            </div>
            <div class="form-group">
                <input type="password" name="senha" class="form-control mb-3 cookie-input" placeholder="Senha" required style="width: 100%;" />
            </div>
            <button type="submit" class="btn btn-neon btn-block">Entrar</button>
        </form>
    </div>
<?php elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <div class="container text-white rounded shadow p-3 my-4" style="background: #181A1E; border-radius: 5px; border: 1px solid rgb(218, 87, 178);">
        <h3 class="mb-4 text-center">Painel de Administração</h3>
        <a href="index.php?logout=1" class="btn btn-danger mb-3">Sair</a>
        
        <?php if (isset($admin_erro)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($admin_erro); ?></div>
        <?php endif; ?>
        <?php if (isset($admin_success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($admin_success); ?></div>
        <?php endif; ?>

        <h4>Criar Novo Usuário</h4>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="new_user" class="form-control mb-3 cookie-input" placeholder="Novo Usuário" required />
            </div>
            <div class="form-group">
                <input type="password" name="new_password" class="form-control mb-3 cookie-input" placeholder="Nova Senha" required />
            </div>
            <div class="form-group">
                <label>Início do Acesso (Horário de Brasília)</label>
                <input type="datetime-local" name="start_time" class="form-control mb-3 cookie-input" required />
            </div>
            <div class="form-group">
                <label>Fim do Acesso (Horário de Brasília)</label>
                <input type="datetime-local" name="end_time" class="form-control mb-3 cookie-input" required />
            </div>
            <button type="submit" name="create_user" class="btn btn-neon btn-block">Criar Usuário</button>
        </form>

        <h4 class="mt-5">Usuários Cadastrados</h4>
        <table class="table text-white">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Início</th>
                    <th>Expiração</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $username => $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($username); ?></td>
                        <td><?php echo htmlspecialchars($data['start_time']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $data['expiration']); ?></td>
                        <td><?php echo $data['expiration'] > time() ? 'Ativo' : 'Expirado'; ?></td>
                        <td class="action-buttons">
                            <button class="btn btn-warning btn-sm edit-btn" 
                                    data-toggle="modal" 
                                    data-target="#editUserModal"
                                    data-username="<?php echo htmlspecialchars($username); ?>" 
                                    data-password="<?php echo htmlspecialchars($data['password']); ?>" 
                                    data-start="<?php echo htmlspecialchars($data['start_time']); ?>" 
                                    data-end="<?php echo date('Y-m-d\TH:i', $data['expiration']); ?>">Renovar</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir o usuário <?php echo htmlspecialchars($username); ?>?');">
                                <input type="hidden" name="delete_user" value="<?php echo htmlspecialchars($username); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal para Edição de Usuário -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="background: #181A1E; color: #fff;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="edit-user-form">
                            <input type="hidden" name="old_username" id="edit_old_username" />
                            <div class="form-group">
                                <label>Usuário</label>
                                <input type="text" name="new_username" id="edit_username" class="form-control mb-3 cookie-input" required />
                            </div>
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" name="new_password" id="edit_password" class="form-control mb-3 cookie-input" required />
                            </div>
                            <div class="form-group">
                                <label>Início do Acesso (Horário de Brasília)</label>
                                <input type="datetime-local" name="start_time" id="edit_start_time" class="form-control mb-3 cookie-input" required />
                            </div>
                            <div class="form-group">
                                <label>Fim do Acesso (Horário de Brasília)</label>
                                <input type="datetime-local" name="end_time" id="edit_end_time" class="form-control mb-3 cookie-input" required />
                            </div>
                            <button type="submit" name="edit_user" class="btn btn-neon btn-block">Salvar Alterações</button>
                            <button type="button" class="btn btn-secondary btn-block mt-2" data-dismiss="modal">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <h1 class="title-neon">CHECKER GG</h1>
    <div class="container text-white rounded shadow p-3 my-4" style="background: #181A1E; border-radius: 5px; border: 1px solid rgb(218, 87, 178);">
        <div class="container-fluid">
            <h3 id="gate-title"><i class="fas fa-cogs"></i> GATE E-REDE</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="countdown">Tempo restante: Carregando...</span>
                <a href="index.php?logout=1" class="btn btn-danger">Sair</a>
            </div>
            <div class="api-buttons mt-3">
                <button class="btn-api btn-erede btn-api-selected" data-api="E-Rede">E-Rede</button>
                <button class="btn-api btn-cvv2" data-api="CVV2">CVV2</button>
                <button class="btn-api btn-visa-bb" data-api="VISA-BB">VISA-BB</button>
                <button class="btn-api btn-paypal" data-api="PAYPAL">PAYPAL</button>
                <button class="btn-api btn-stripe" data-api="STRIPE">STRIPE</button>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <div class="buttons">
                <button class="btn btn-dark " id="chk-start"><i class="fas fa-play"></i> Iniciar</button>
                <button class="btn btn-dark " id="chk-pause" disabled><i class="fas fa-pause"></i> Pausar</button>
                <button class="btn btn-dark " id="chk-stop" disabled><i class="fas fa-stop"></i> Parar</button>
                <button class="btn btn-dark " id="chk-clean"><i class="fas fa-trash-alt"></i> Limpar</button>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <span class="badge badge-warning" id="estatus">Aguardando inicio...</span>
        </div>
        <div class="thread-control mt-3">
            <label>Threads: <span id="threadsValue">1</span></label>
            <input type="range" class="thread-slider" id="threadsSlider" min="1" max="2" value="1">
        </div>
    </div>

    <div class="container p-0 shadow">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
            <li class="nav-item">
                <a class="nav-link active" style="border: none;" id="home-tab" data-toggle="tab" href="#chk-home" role="tab" aria-controls="home" aria-selected="true"><i class="far fa-credit-card" style="color: #fff;"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="border: none;" id="profile-tab" data-toggle="tab" href="#chk-lives" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-thumbs-up fa-lg" style="color: #fff;"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="border: none;" id="contact-tab" data-toggle="tab" href="#chk-dies" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-thumbs-down fa-lg" style="color: #fff;"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="border: none;" id="contact-tab" data-toggle="tab" href="#chk-errors" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-times fa-lg" style="color: #fff;"></i></a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active px-3 pt-4 pb-3" id="chk-home" role="tabpanel" aria-labelledby="home-tab">
                <div class="my-2">
                    Aprovadas: <span class="val-lives" style="font-weight: bold;">0</span>
                    Reprovadas: <span class="val-dies" style="font-weight: bold;">0</span>
                    Errors: <span class="val-errors" style="font-weight: bold;">0</span>
                    Testadas: <span class="val-tested" style="font-weight: bold;">0</span>
                    Total: <span class="val-total" style="font-weight: bold;">0</span>
                </div>
                <div class="container-fluid p-0 mt-2">
                    <textarea id="lista_cartoes" placeholder="Insira sua lista..." rows="10" cols="rounded shadow"></textarea>
                </div>
            </div>
            <div class="tab-pane fade show px-3 pt-4 pb-3" id="chk-lives" role="tabpanel" aria-labelledby="home-tab">
                <h5>Aprovadas</h5>
                <span>Total: <span class="val-lives">0</span></span>
                <br>
                <button class="btn btn-dark btn-neon" id="copyButton"><i class="fas fa-copy"></i></button>
                <button class="btn btn-dark btn-neon" onclick="apagarValoresLives()"><i class="fas fa-trash-alt"></i></button>
                <br>
                <div id="lives" style="overflow:auto;"></div>
            </div>
            <div class="tab-pane fade show px-3 pt-4 pb-3" id="chk-dies" role="tabpanel" aria-labelledby="home-tab">
                <h5>Reprovadas</h5>
                <span>Total: <span class="val-dies">0</span></span>
                <br>
                <button class="btn btn-dark btn-neon" onclick="apagarValoresDies()"><i class="fas fa-trash-alt"></i></button>
                <br>
                <div id="dies" style="overflow:auto;"></div>
            </div>
            <div class="tab-pane fade show px-3 pt-4 pb-3" id="chk-errors" role="tabpanel" aria-labelledby="home-tab">
                <h5>Erros</h5>
                <span>Total: <span class="val-errors">0</span></span>
                <br>
                <button class="btn btn-dark btn-neon" onclick="apagarValoresErrors()"><i class="fas fa-trash-alt"></i></button>
                <br>
                <div id="errors" style="overflow:auto;"></div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
<script src="script.js"></script>

<script type="text/javascript">
    // Bloquear botão direito do mouse
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Bloquear atalhos de ferramentas de desenvolvedor
    document.addEventListener('keydown', function(e) {
        // Bloquear F12
        if (e.key === 'F12') {
            e.preventDefault();
            return false;
        }
        // Bloquear Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
        if (e.ctrlKey && (e.key === 'I' || e.key === 'i' || e.key === 'J' || e.key === 'j' || e.key === 'U' || e.key === 'u')) {
            e.preventDefault();
            return false;
        }
        // Permitir apenas Ctrl+C e Ctrl+V
        if (e.ctrlKey && (e.key === 'C' || e.key === 'c' || e.key === 'V' || e.key === 'v')) {
            return true;
        }
        // Bloquear outras combinações de Ctrl
        if (e.ctrlKey) {
            e.preventDefault();
            return false;
        }
    });
</script>

<?php if (isset($_SESSION['logado']) && !$_SESSION['is_admin']): ?>
<script type="text/javascript">
    // Cronômetro para o tempo restante
    function startCountdown(expiration) {
        const countdownElement = document.getElementById('countdown');
        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000); // Tempo atual em segundos
            const timeLeft = expiration - now; // Tempo restante em segundos
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Tempo expirado!';
                window.location.href = 'index.php?logout=1'; // Deslogar automaticamente
                return;
            }
            const days = Math.floor(timeLeft / (24 * 60 * 60));
            const hours = Math.floor((timeLeft % (24 * 60 * 60)) / (60 * 60));
            const minutes = Math.floor((timeLeft % (60 * 60)) / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `Tempo restante: ${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000); // Atualiza a cada segundo
    }

    // Iniciar o cronômetro com o tempo de expiração
    const expirationTime = <?php echo isset($_SESSION['expiration']) ? $_SESSION['expiration'] : 0; ?>;
    if (expirationTime > 0) {
        startCountdown(expirationTime);
    }

    function apagarValoresLives() {
        $("#lives").html("");
        $("#val-lives").text("0");
    }
    function apagarValoresDies() {
        $("#dies").html("");
        $("#val-dies").text("0");
    }
    function apagarValoresErrors() {
        $("#errors").html("");
        $("#val-errors").text("0");
    }

    $(document).ready(function() {
        // Configuração do toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right"
        };

        // Configuração do botão de copiar
        $("#copyButton").click(function() {
            var range = document.createRange();
            range.selectNode(document.getElementById("lives"));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();
            toastr["success"]("Conteúdo copiado para a área de transferência!");
        });

        // Variáveis globais
        var lista = [];
        var total = 0;
        var tested = 0;
        var lives = 0;
        var dies = 0;
        var errors = 0;
        var stopped = true;
        var paused = true;
        var threads = 1;
        var activeThreads = 0;
        var currentIndex = 0;
        var selectedApi = "E-Rede"; // API padrão

        // Atualizar título do gate com base na API selecionada
        function updateGateTitle(api) {
            $("#gate-title").html(`<i class="fas fa-cogs"></i> GATE ${api}`);
        }

        // Configuração dos botões de API
        $(".btn-api").click(function() {
            $(".btn-api").removeClass("btn-api-selected");
            $(this).addClass("btn-api-selected");
            selectedApi = $(this).data("api");
            updateGateTitle(selectedApi);
        });

        // Inicializar título com API padrão
        updateGateTitle(selectedApi);

        // Controle de threads
        $("#threadsSlider").on("input", function() {
            threads = parseInt($(this).val());
            $("#threadsValue").text(threads);
        });

        function getNextItem() {
            if (currentIndex >= lista.length) return null;
            return lista[currentIndex++];
        }

        function updateCounters() {
            $(".val-total").text(total);
            $(".val-lives").text(lives);
            $(".val-dies").text(dies);
            $(".val-errors").text(errors);
            $(".val-tested").text(tested);
        }

        function testar() {
            if (stopped || paused) {
                activeThreads--;
                if (activeThreads <= 0 && !paused) {
                    finalizarTeste();
                }
                return;
            }

            var conteudo = getNextItem();
            if (!conteudo) {
                activeThreads--;
                if (activeThreads <= 0) {
                    finalizarTeste();
                }
                return;
            }

            activeThreads++;
            const apiUrl = "apis5/" + selectedApi + ".php";

            $.get(apiUrl, { lista: conteudo })
                .done(function(response) {
                    if (stopped || paused) {
                        activeThreads--;
                        return;
                    }

                    tested++;
                    removelinha();

                    if (response.includes("Aprovada")) {
                        lives++;
                        $("#estatus").attr("class", "badge badge-success").text(conteudo + " -> LIVE");
                        toastr["success"]("Aprovada! " + conteudo);
                        $("#lives").append(response + "<br>");
                        document.getElementById("aprovadoSound").play();
                        $.post("saveLives.php", { lives: response });
                    } else if (response.includes("Reprovada")) {
                        dies++;
                        $("#estatus").attr("class", "badge badge-danger").text(conteudo + " -> DIE");
                        toastr["error"]("Reprovada! " + conteudo);
                        $("#dies").append(response + "<br>");
                    } else {
                        errors++;
                        $("#estatus").attr("class", "badge badge-warning").text(conteudo + " -> ERROR");
                        toastr["warning"]("Resposta Inesperada! " + conteudo);
                        $("#errors").append(response + "<br>");
                    }

                    updateCounters();
                    setTimeout(testar, 50); // Delay de 50ms
                })
                .fail(function() {
                    errors++;
                    $("#estatus").attr("class", "badge badge-warning").text(conteudo + " -> ERROR");
                    toastr["error"]("Erro ao testar cartão: " + conteudo);
                    $("#errors").append("Erro: " + conteudo + "<br>");
                    updateCounters();
                    activeThreads--;
                    setTimeout(testar, 50);
                });
        }

        function removelinha() {
            if (lista.length > 0) {
                lista.splice(0, 1);
                $("#lista_cartoes").val(lista.join("\n"));
            } else {
                $("#lista_cartoes").val("");
            }
        }

        function finalizarTeste() {
            $("#estatus").attr("class", "badge badge-success").text("Teste finalizado");
            toastr["success"]("Teste de " + total + " itens finalizado");
            $("#chk-start").removeAttr('disabled');
            $("#chk-clean").removeAttr('disabled');
            $("#chk-stop").attr("disabled", "true");
            $("#chk-pause").attr("disabled", "true");
            stopped = true;
            document.getElementById("endSound").play();
        }

        // ========== START ========== //
        $("#chk-start").click(function() {
            const inputText = $("#lista_cartoes").val().trim();
            if (!inputText) {
                toastr["warning"]("Insira uma lista para iniciar.");
                $("#lista_cartoes").focus();
                return;
            }

            lista = inputText.split("\n").filter(item => item.trim() !== "");
            total = lista.length;

            if (total > 600) {
                toastr["error"]("Limite máximo de 600 cartões excedido!");
                return;
            }

            tested = 0;
            lives = 0;
            dies = 0;
            errors = 0;
            currentIndex = 0;
            stopped = false;
            paused = false;
            activeThreads = 0;

            $("#lives, #dies, #errors").html("");
            updateCounters();

            toastr["success"]("Checker Iniciado com " + threads + " threads");
            $("#estatus").attr("class", "badge badge-success").text("Checker iniciado, aguarde...");
            document.getElementById("startSound").play();

            $("#chk-stop").removeAttr('disabled');
            $("#chk-pause").removeAttr('disabled');
            $("#chk-start").attr("disabled", "true");
            $("#chk-clean").attr("disabled", "true");

            for (var i = 0; i < threads; i++) {
                testar();
            }
        });

        // ========== PAUSE ========== //
        function pause() {
            $("#chk-start").removeAttr('disabled');
            $("#chk-pause").attr("disabled", "true");
            paused = true;
            toastr["info"]("Checker Pausado!");
            $("#estatus").attr("class", "badge badge-info").text("Checker pausado...");
        }

        $("#chk-pause").click(function() {
            pause();
        });

        // ========== STOP ========== //
        function stop() {
            stopped = true;
            $("#chk-start").removeAttr('disabled');
            $("#chk-clean").removeAttr('disabled');
            $("#chk-stop").attr("disabled", "true");
            $("#chk-pause").attr("disabled", "true");
            toastr["info"]("Checker Parado!");
            $("#estatus").attr("class", "badge badge-secondary").text("Checker parado...");
        }

        $("#chk-stop").click(function() {
            stop();
        });

        // ========== CLEAN ========== //
        function clean() {
            lista = [];
            total = 0;
            tested = 0;
            lives = 0;
            dies = 0;
            errors = 0;
            stopped = true;

            $("#lives, #dies, #errors").html("");
            updateCounters();
            $("#lista_cartoes").val("");
            toastr["info"]("Checker Limpo!");
        }

        $("#chk-clean").click(function() {
            clean();
        });
    });
</script>
<?php endif; ?>

<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
<script>
    $(document).ready(function() {
        $('#editUserModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const username = button.data('username');
            const password = button.data('password');
            const start = button.data('start');
            const end = button.data('end');

            const modal = $(this);
            modal.find('#edit_old_username').val(username);
            modal.find('#edit_username').val(username);
            modal.find('#edit_password').val(password);
            modal.find('#edit_start_time').val(start.substring(0, 16));
            modal.find('#edit_end_time').val(end);
        });

        $('#editUserModal').on('hidden.bs.modal', function() {
            $(this).find('#edit_old_username').val('');
            $(this).find('#edit_username').val('');
            $(this).find('#edit_password').val('');
            $(this).find('#edit_start_time').val('');
            $(this).find('#edit_end_time').val('');
        });
    });
</script>
<?php endif; ?>
</body>

</html>
