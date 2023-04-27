<?php

    namespace Src\Models;

    use Src\Core\DBConnection;
    use Src\Entities\Usuario;

    class UsuarioModel extends DBConnection {
        public function __construct() {
            parent::__construct('usuario');
        }

        /** @var Array<Src\Entities\Tarefa> */
        public function _select($columns = ['*'], $where = []): Array {
            (Array) $entidadesFormatadas = [];
            
            if (count($where) > 0) {
                $usuarios = $this->selectBy($where, $columns);

            } else {
                $usuarios = $this->selectAll($columns);
            }

            foreach ($usuarios as $usuarioRegistro) {
                $usuario = new Usuario();

                $usuario->setId($usuarioRegistro['id']);
                $usuario->setNome($usuarioRegistro['nome']);
                $usuario->setEmail($usuarioRegistro['email']);
                $usuario->setSenha($usuarioRegistro['senha']);
                $usuario->setSalt($usuarioRegistro['salt']);

                array_push($entidadesFormatadas, $usuario);
            }

            return $entidadesFormatadas;
        }
    }