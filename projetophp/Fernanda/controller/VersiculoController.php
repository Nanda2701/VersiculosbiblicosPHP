<?php

class VersiculoController
{
    private Versiculo $modelo;
    private Usuario $usuarios;

    public function __construct(Versiculo $modelo, Usuario $usuarios)
    {
        $this->modelo = $modelo;
        $this->usuarios = $usuarios;
    }

    public function handle(string $pagina): void
    {
        switch ($pagina) {
            case 'listar':
                $this->listar();
                break;
            case 'detalhe':
                $this->detalhe();
                break;
            case 'inserir':
                $this->inserir();
                break;
            case 'editar':
                $this->editar();
                break;
            case 'apagar':
                $this->apagar();
                break;
            case 'cadastro':
                $this->cadastro();
                break;
            case 'login':
                $this->login();
                break;
            case 'sair':
                $this->sair();
                break;
            case 'home':
            default:
                $this->home();
                break;
        }
    }

    private function home(): void
    {
        $this->render('home', [
            'versiculos' => $this->modelo->maisRecentes(5),
        ]);
    }

    private function listar(): void
    {
        $this->render('listar', [
            'versiculos' => $this->modelo->listar(),
        ]);
    }

    private function detalhe(): void
    {
        $idEnviado = $_GET['id'] ?? null;
        $idRecebido = is_scalar($idEnviado)
            ? filter_var($idEnviado, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            : false;
        $versiculo = $idRecebido ? $this->modelo->buscarPorId($idRecebido) : null;

        if (!$versiculo) {
            http_response_code(404);
        }

        $this->render('detalhe', [
            'versiculo' => $versiculo,
        ]);
    }

    private function inserir(): void
    {
        if (!$this->utilizadorAtual()) {
            header('Location: index.php?pagina=login');
            exit;
        }

        $erro = null;
        $dados = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $_POST;
            $textoEnviado = $_POST['versiculo'] ?? '';
            $texto = is_string($textoEnviado) ? trim($textoEnviado) : '';
            $upload = $_FILES['foto'] ?? null;
            $uploadValido = is_array($upload)
                && isset($upload['error'], $upload['size'], $upload['tmp_name'])
                && is_int($upload['error'])
                && is_int($upload['size'])
                && is_string($upload['tmp_name']);

            if (!$this->tokenValido()) {
                $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
            } elseif ($texto === '') {
                $erro = 'O texto do versículo é obrigatório.';
            } elseif (strlen($texto) > 65535) {
                $erro = 'O texto não pode ultrapassar 65.535 bytes, limite do campo TEXT na base de dados.';
            } elseif (!$uploadValido || $upload['error'] !== UPLOAD_ERR_OK) {
                $erro = 'Selecione uma imagem válida para carregar e confirme que o ficheiro não excede o limite do servidor.';
            } elseif ($upload['size'] > 5 * 1024 * 1024) {
                $erro = 'A imagem não pode ultrapassar 5 MB.';
            } else {
                $temporario = $upload['tmp_name'];
                $mime = is_uploaded_file($temporario)
                    ? (new finfo(FILEINFO_MIME_TYPE))->file($temporario)
                    : false;
                $extensoes = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/gif'  => 'gif',
                    'image/webp' => 'webp',
                ];

                if (!isset($extensoes[$mime])) {
                    $erro = 'Formato não permitido. Use JPG, PNG, GIF ou WebP.';
                } else {
                    $pastaFotos = dirname(__DIR__) . '/foto';

                    if (!is_dir($pastaFotos) && !mkdir($pastaFotos, 0755, true) && !is_dir($pastaFotos)) {
                        $erro = 'Não foi possível preparar a pasta para guardar a imagem.';
                    }

                    if ($erro === null) {
                        $nomeFoto = bin2hex(random_bytes(16)) . '.' . $extensoes[$mime];
                        $destino = $pastaFotos . '/' . $nomeFoto;

                        if (!move_uploaded_file($temporario, $destino)) {
                            $erro = 'Não foi possível guardar a imagem.';
                        } else {
                            try {
                                $id = $this->modelo->criar(
                                    (int) $_SESSION['usuario']['id'],
                                    $texto,
                                    $nomeFoto
                                );

                                header('Location: index.php?pagina=detalhe&id=' . $id);
                                exit;
                            } catch (PDOException $e) {
                                if (is_file($destino) && !unlink($destino)) {
                                    error_log('Não foi possível remover a imagem após falha ao guardar o versículo.');
                                }
                                error_log('Falha PDO ao guardar um versículo: ' . $e->getMessage());
                                $erro = 'Não foi possível guardar o versículo. Tente novamente.';
                            }
                        }
                    }
                }
            }
        }

        $this->render('inserir', [
            'erro'     => $erro,
            'dados'    => $dados,
        ]);
    }

    private function editar(): void
    {
        $usuario = $this->utilizadorAtual();
        if (!$usuario) {
            header('Location: index.php?pagina=login');
            exit;
        }

        $idEnviado = $_REQUEST['id'] ?? null;
        $id = is_scalar($idEnviado)
            ? filter_var($idEnviado, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            : false;

        if (!$id) {
            http_response_code(404);
            $this->render('detalhe', ['versiculo' => null]);
            return;
        }

        $versiculo = $this->modelo->buscarPorId($id);
        if (!$versiculo || (int) $versiculo['usuario_id'] !== (int) $usuario['id']) {
            http_response_code(403);
            $this->render('detalhe', ['versiculo' => $versiculo]);
            return;
        }

        $erro = null;
        $dados = [
            'versiculo' => $versiculo['versiculo'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $_POST;
            $textoEnviado = $_POST['versiculo'] ?? '';
            $texto = is_string($textoEnviado) ? trim($textoEnviado) : '';
            $upload = $_FILES['foto'] ?? null;
            $temFotoNova = is_array($upload) && isset($upload['error']) && $upload['error'] !== UPLOAD_ERR_NO_FILE;

            if (!$this->tokenValido()) {
                $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
            } elseif ($texto === '') {
                $erro = 'O texto do versículo é obrigatório.';
            } elseif (strlen($texto) > 65535) {
                $erro = 'O texto não pode ultrapassar 65.535 bytes, limite do campo TEXT na base de dados.';
            } elseif ($temFotoNova) {
                $uploadValido = is_array($upload)
                    && isset($upload['error'], $upload['size'], $upload['tmp_name'])
                    && is_int($upload['error'])
                    && is_int($upload['size'])
                    && is_string($upload['tmp_name']);

                if (!$uploadValido || $upload['error'] !== UPLOAD_ERR_OK) {
                    $erro = 'Selecione uma imagem válida para carregar e confirme que o ficheiro não excede o limite do servidor.';
                } elseif ($upload['size'] > 5 * 1024 * 1024) {
                    $erro = 'A imagem não pode ultrapassar 5 MB.';
                } else {
                    $temporario = $upload['tmp_name'];
                    $mime = is_uploaded_file($temporario)
                        ? (new finfo(FILEINFO_MIME_TYPE))->file($temporario)
                        : false;
                    $extensoes = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/webp' => 'webp',
                    ];

                    if (!isset($extensoes[$mime])) {
                        $erro = 'Formato não permitido. Use JPG, PNG, GIF ou WebP.';
                    } else {
                        $pastaFotos = dirname(__DIR__) . '/foto';
                        if (!is_dir($pastaFotos) && !mkdir($pastaFotos, 0755, true) && !is_dir($pastaFotos)) {
                            $erro = 'Não foi possível preparar a pasta para guardar a imagem.';
                        }

                        if ($erro === null) {
                            $nomeFoto = bin2hex(random_bytes(16)) . '.' . $extensoes[$mime];
                            $destino = $pastaFotos . '/' . $nomeFoto;

                            if (!move_uploaded_file($temporario, $destino)) {
                                $erro = 'Não foi possível guardar a imagem.';
                            } else {
                                $fotoAntiga = $versiculo['foto'] ?? null;
                                if (is_string($fotoAntiga) && $fotoAntiga !== '' && is_file($pastaFotos . '/' . $fotoAntiga) && $fotoAntiga !== $nomeFoto) {
                                    @unlink($pastaFotos . '/' . $fotoAntiga);
                                }

                                if ($this->modelo->atualizar($id, (int) $usuario['id'], $texto, $nomeFoto)) {
                                    header('Location: index.php?pagina=detalhe&id=' . $id);
                                    exit;
                                }

                                $erro = 'Não foi possível atualizar o versículo.';
                                if (is_file($destino)) {
                                    @unlink($destino);
                                }
                            }
                        }
                    }
                }
            } else {
                if ($this->modelo->atualizar($id, (int) $usuario['id'], $texto)) {
                    header('Location: index.php?pagina=detalhe&id=' . $id);
                    exit;
                }

                $erro = 'Não foi possível atualizar o versículo.';
            }
        }

        $this->render('editar', [
            'erro' => $erro,
            'dados' => $dados,
            'versiculo' => $versiculo,
        ]);
    }

    private function apagar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->tokenValido()) {
            http_response_code(405);
            return;
        }

        $usuario = $this->utilizadorAtual();
        if (!$usuario) {
            header('Location: index.php?pagina=login');
            exit;
        }

        $idEnviado = $_POST['id'] ?? null;
        $id = is_scalar($idEnviado)
            ? filter_var($idEnviado, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            : false;

        if (!$id) {
            http_response_code(404);
            return;
        }

        $versiculo = $this->modelo->buscarPorId($id);
        if (!$versiculo || (int) $versiculo['usuario_id'] !== (int) $usuario['id']) {
            http_response_code(403);
            return;
        }

        $pastaFotos = dirname(__DIR__) . '/foto';
        $foto = is_string($versiculo['foto'] ?? null) ? $versiculo['foto'] : '';

        if ($this->modelo->apagar($id, (int) $usuario['id'])) {
            if ($foto !== '' && is_file($pastaFotos . '/' . $foto)) {
                @unlink($pastaFotos . '/' . $foto);
            }

            header('Location: index.php?pagina=listar');
            exit;
        }

        http_response_code(500);
    }

    private function cadastro(): void
    {
        if ($this->utilizadorAtual()) {
            header('Location: index.php');
            exit;
        }

        $erro = null;
        $dados = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $_POST;
            $nomeEnviado = $_POST['nome'] ?? '';
            $emailEnviado = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirmacao = $_POST['confirmacao'] ?? '';
            $nome = is_string($nomeEnviado) ? trim($nomeEnviado) : '';
            $email = is_string($emailEnviado) ? trim($emailEnviado) : '';

            if (!$this->tokenValido()) {
                $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
            } elseif ($nome === '' || mb_strlen($nome, 'UTF-8') > 100) {
                $erro = 'Informe um nome com até 100 caracteres.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 100) {
                $erro = 'Informe um e-mail válido com até 100 caracteres.';
            } elseif (!is_string($senha) || strlen($senha) < 8) {
                $erro = 'A senha deve ter pelo menos 8 caracteres.';
            } elseif (strlen($senha) > 72) {
                $erro = 'A senha não pode ultrapassar 72 caracteres.';
            } elseif (!is_string($confirmacao) || $senha !== $confirmacao) {
                $erro = 'A confirmação da senha não corresponde.';
            } else {
                try {
                    $id = $this->usuarios->criar($nome, $email, $senha);
                    session_regenerate_id(true);
                    $this->renovarToken();
                    $_SESSION['usuario'] = [
                        'id' => $id,
                        'nome' => $nome,
                    ];

                    header('Location: index.php');
                    exit;
                } catch (PDOException $e) {
                    if (($e->errorInfo[1] ?? null) === 1062) {
                        $erro = 'Este e-mail já está cadastrado.';
                    } else {
                        error_log('Falha PDO ao cadastrar um utilizador: ' . $e->getMessage());
                        $erro = 'Não foi possível criar a conta. Tente novamente.';
                    }
                }
            }
        }

        $this->render('cadastro', [
            'erro'  => $erro,
            'dados' => $dados,
        ]);
    }

    private function login(): void
    {
        if ($this->utilizadorAtual()) {
            header('Location: index.php');
            exit;
        }

        $erro = null;
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emailEnviado = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $email = is_string($emailEnviado) ? trim($emailEnviado) : '';

            if (!$this->tokenValido()) {
                $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
            } elseif (!is_string($senha) || $email === '') {
                $erro = 'Informe o e-mail e a senha.';
            } else {
                $usuario = $this->usuarios->buscarPorEmail($email);

                if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                    $erro = 'E-mail ou senha incorretos.';
                } else {
                    session_regenerate_id(true);
                    $this->renovarToken();
                    $_SESSION['usuario'] = [
                        'id' => (int) $usuario['id'],
                        'nome' => $usuario['nome'],
                    ];

                    header('Location: index.php');
                    exit;
                }
            }
        }

        $this->render('login', [
            'erro'  => $erro,
            'email' => $email,
        ]);
    }

    private function sair(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->tokenValido()) {
            http_response_code(405);
            return;
        }

        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: index.php');
        exit;
    }

    private function utilizadorAtual(): ?array
    {
        $usuario = $_SESSION['usuario'] ?? null;

        return is_array($usuario)
            && isset($usuario['id'], $usuario['nome'])
            && is_int($usuario['id'])
            && is_string($usuario['nome'])
            ? $usuario
            : null;
    }

    private function tokenValido(): bool
    {
        $token = $_POST['csrf_token'] ?? null;
        $tokenSessao = $_SESSION['csrf_token'] ?? null;

        return is_string($token)
            && is_string($tokenSessao)
            && hash_equals($tokenSessao, $token);
    }

    private function renovarToken(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    private function render(string $vista, array $viewData = []): void
    {
        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $viewData['usuarioAtual'] = $this->utilizadorAtual();
        $viewData['csrfToken'] = $_SESSION['csrf_token'];
        extract($viewData, EXTR_SKIP);
        require dirname(__DIR__) . '/views/' . $vista . '.php';
    }
}