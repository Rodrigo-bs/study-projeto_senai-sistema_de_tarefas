<?php

    namespace Src\Routes;

    use Src\Controllers\UsuarioController;
    use Src\Core\Functions;

    class Usuario extends UsuarioController {
        public function login(Array $params): void {

            /* verifico o tipo da requisição */
            if (Functions::_isGet()) {
                Functions::_loadView('login', $params);
                return;
            }

            /** @var Array<Src\Core\Functions> */
            (Array) $informacoes = Functions::_toPost([
                'email',
                'senha'
            ]);
            
            (Array) $logado = $this->toLogin($informacoes['email'], $informacoes['senha']);
            
            if ($logado['erro']) {
                Functions::_setMessage($logado['mensagem'], 'erro');
                Functions::_loadView('login', $params);
                return;
            }

            $cookieOptions = [
                'expires' => time() + (3600 * 3), // 3h
                'path' => '/'
            ];

            setcookie('usuario', implode('-', [$logado['id'], $logado['nome']]), $cookieOptions);
            setcookie('token', $logado['token'], $cookieOptions);

            header('Location: ' . URL . '/');
            exit;
        }

        public function logout(): void {
            if (isset($_COOKIE['token'])) {
                session_destroy();

                setcookie('token', null, -1, '/');
                setcookie('usuario', null, -1, '/');

                header('Location: ' . URL . '/');
                exit;
            }

            header('Location: ' . URL . '/');
            exit;
        }
    }