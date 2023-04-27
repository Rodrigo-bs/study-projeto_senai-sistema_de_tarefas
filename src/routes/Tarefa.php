<?php

    namespace Src\Routes;

    use Src\Controllers\TarefaController;

    use Src\Core\Functions;

    class Tarefa extends TarefaController {
        private function _valid() {
            $dados = [
                'titulo',
                'descricao',
                'tipo',
                'prioridade'
            ];

            $dados = Functions::_toPost($dados);

            $dados['dataAbertura'] = date('Y-m-d');
            $dados['responsavel'] = current(explode('-', $_COOKIE['usuario']));

            foreach ($dados as $key => $informacao) {
                if ($informacao == null || $informacao == '') {
                    Functions::_setMessage('Preencha todos os campos!', 'aviso');
                    return;
                }
            }

            return $dados;
        }

        public function listar(Array $params): void {
            $offset = isset($params[0]) ? $params[0] : 0;

            list($tarefas, $usuarios, $quantidadeDeTarefas) = $this->toListAll(true, true, $offset);

            Functions::_loadTemplate([
                'title' => 'Tarefa - Listagem',
                'view' => 'tarefa/listagem',
                'params' => [
                    'listagem' => $tarefas,
                    'usuarios' => $usuarios,
                    'quantidadeDeTarefas' => $quantidadeDeTarefas,
                    'params' => $params
                ]
            ]);

            return;
        }

        public function cadastro(Array $params) {
            if (Functions::_isGet()) {
                Functions::_loadTemplate([
                    'title' => 'Tarefa - Cadastro',
                    'view' => 'tarefa/formulario-cadastro',
                    'params' => [
                        'params' => $params
                    ]
                ]);

                return;
            }

            $dados = $this->_valid();
            
            if ($dados == null) {
                Functions::_loadTemplate([
                    'title' => 'Tarefa - Cadastro',
                    'view' => 'tarefa/formulario-cadastro',
                    'params' => [
                        'dados' => $dados
                    ]
                ]);

                return;
            }

            $registro = $this->toInsert($dados);

            if (!$registro) {
                Functions::_setMessage('Erro interno!', 'erro');

                Functions::_loadTemplate([
                    'title' => 'Tarefa - Cadastro',
                    'view' => 'tarefa/formulario-cadastro',
                    'params' => [
                        'dados' => $dados
                    ]
                ]);

                return;
            }

            Functions::_setMessage('Tarefa com o id #' . $registro->getId() . ' inserida com sucesso!', 'sucesso');
            header('Location: ' . URL . '/');
            return;
        }

        public function detalhar(Array $params) {
            $idDaTarefa = $params[0];

            list($tarefa, $usuarios, $observacoes) = $this->toDetalhar($idDaTarefa);
            
            $usuarioAtual;

            if (!$tarefa) {
                header('Location: ' . URL . '/');
                return;
            }

            foreach ($usuarios as $usuario) {
                if ($usuario->getId() == $tarefa->getIdResponsavel()) {
                    $usuarioAtual = $usuario;
                }
            }
            
            if (Functions::_isGet()) {
                Functions::_loadTemplate([
                    'title' => 'Tarefa - Detalhar',
                    'view' => 'tarefa/detalhar',
                    'params' => [
                        'observacoes' => $observacoes,
                        'usuario_id' => explode('-', $_COOKIE['usuario'])[0],
                        'usuarios' => $usuarios,
                        'usuario' => $usuarioAtual,
                        'tarefa' => $tarefa
                    ]
                ]);
                return;
            }
        }

        public function assumir($params) {
            $idDaTarefa = $params[0];
            $idDoUsuario = $params[1];

            $idDoUsuarioDaSessao = explode('-', $_COOKIE['usuario'])[0];

            if ($idDoUsuario != $idDoUsuarioDaSessao) {
                header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
                Functions::_setMessage('Usuário é diferente do usuário logado', 'erro');
                return;
            }

            $tarefa = $this->toAssumir($idDoUsuario, $idDaTarefa);

            if (!$tarefa) {
                header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
                Functions::_setMessage('Erro interno!', 'erro');
                return;
            }

            header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
            Functions::_setMessage('Tarefa assumida com sucesso!', 'sucesso');
            return;
        }

        public function finalizar($params) {
            $idDaTarefa = $params[0];

            $idDoUsuarioDaSessao = explode('-', $_COOKIE['usuario'])[0];

            $resultado = $this->toFinalizar($idDaTarefa, $idDoUsuarioDaSessao);

            if (count($resultado) > 0 && isset($resultado['erro']) && $resultado['erro']) {
                header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
                Functions::_setMessage($resultado['mensagem'], 'erro');
                return;
            }

            header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
            Functions::_setMessage('Tarefa finalizada com sucesso!', 'sucesso');
            return;
        }
    }