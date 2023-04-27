<?php

    namespace Src\Core;

    class Functions {

        /** @var Array<String> */
        public static function _toPost(Array $values, Bool $valid = true) {
            $valores;

            foreach ($values as $value) {
                if ($valid) {
                    $valores[$value] = filter_input(INPUT_POST, $value, FILTER_SANITIZE_SPECIAL_CHARS);
                } else {
                    $valores[$value] = filter_input(INPUT_POST, $value);
                }
            }

            return $valores;
        }

        /** @var Boolean */
        public static function _isGet() {
            if (isset($_POST) && count($_POST) > 0) {
                return false;
            }

            return true;
        }

        /**  @var void */
        public static function _loadTemplate(Array $data): void {
            include './src/views/template.phtml';
        }
    
        /**  @var void */
        public static function _loadView(String $view, Array $data): void {
            include './src/views/' . $view . '.phtml';
        }

        /** @var array<string>  */
        public static function _crypt(String $valor, String $salt = null): array {
            $salt = $salt == null ? md5($valor) : $salt;

            return [
                crypt($valor, $salt . PRIVATE_KEY),
                $salt
            ];
        }

        /** @var bool  */
        public static function _dcrypt(String $valor, String $hash, String $salt): bool {
            $hashDoValorPassado = crypt($valor, $salt . PRIVATE_KEY);

            if ($hashDoValorPassado === $hash) {
                return true;
            } else {
                return false;
            }
        }

        /** @var void */
        public static function _setMessage(String $mensagem, String $tipo): void {
            $_SESSION['mensagem'] = $mensagem;
            $_SESSION['mensagem_tipo'] = $tipo;
        }

        public static function _destroySession(String $nomeDaSessao) {
            unset($_SESSION['nomeDaSessao']);
        }
    }