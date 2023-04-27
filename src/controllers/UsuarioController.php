<?php

    namespace Src\Controllers;

    use Src\Models\UsuarioModel;
    use Src\Core\Functions;

    class UsuarioController  {
        private $usuarioModel;

        public function __construct() {
            $this->usuarioModel = new UsuarioModel();
        }

        public function toListAll() {
            $resultado = $this->usuarioModel->_select();
            return $resultado;
        }

        public function toLogin($email, $senha) {
            $usuario = current($this->usuarioModel->_select(['*'], [
                'email' => $email
            ]));

            // valida o email
            if ($usuario && $usuario->getId() > 0) {
                $senhaIsValida = Functions::_dcrypt($senha, $usuario->getSenha(), $usuario->getSalt());

                // valida a senha
                if ($senhaIsValida) {
                    $token = current(Functions::_crypt($usuario->getId() . $usuario->getNome(), $usuario->getSalt()));

                    return [
                        'erro' => false,
                        'token' => $token,
                        'id' => $usuario->getId(),
                        'nome' => $usuario->getNome()
                    ];
                } else {
                    return [
                        'erro' => true,
                        'mensagem' => 'Senha Inválida!'
                    ];
                }
            } else {
                return [
                    'erro' => true,
                    'mensagem' => 'Email Inválido!'
                ];
            }
        }

        public static function tokenIsValid() {
            $this->_validToken($token);
        }

    }