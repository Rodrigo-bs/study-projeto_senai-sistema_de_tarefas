<?php

    namespace Src\Routes;

    use Src\Controllers\ObservacaoController;
    use Src\Controllers\TarefaController;

    use Src\Core\Functions;


    class Observacao extends ObservacaoController {
        private function _valid($id, $up = false) {
            $dados = [
                'descricao',
            ];

            $dados = Functions::_toPost($dados);

            if ($up) {
                $dados['id'] = $id;
                return $dados;
            }

            $dados['dataAbertura'] = date('Y-m-d');
            $dados['tarefa'] = $id;
            $dados['responsavel'] = current(explode('-', $_COOKIE['usuario']));

            foreach ($dados as $key => $informacao) {
                if ($informacao == null || $informacao == '') {
                    header('Location: ' . URL . '/tarefa/detalhar/' . $id);
                    Functions::_setMessage('Preencha todos os campos!', 'aviso');
                    return;
                }
            }

            return $dados;
        }

        public function cadastro($params) {
            $idDaTarefa = $params[0];

            $dados = $this->_valid($idDaTarefa);

            if ($dados == null) {
                Functions::_setMessage('Preencha todos os campos!', 'alerta');
                header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
                return;
            }

            $registro = $this->toInsert($dados);

            if (!$registro) {
                Functions::_setMessage('Erro interno!', 'erro');
                header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
                return;
            }

            Functions::_setMessage('Observacao com o id #' . $registro->getId() . ' inserida com sucesso!', 'sucesso');
            header('Location: ' . URL . '/tarefa/detalhar/' . $idDaTarefa);
            return;
        }

        public function editar($params) {
            $idDaObservacao = $params[0];

            if (Functions::_isGet()) {
                $observacao = $this->toEdit([], $idDaObservacao);
                
                Functions::_loadTemplate([
                    'title' => 'Observação - Editar',
                    'view' => 'observacao/editar',
                    'params' => [
                        'observacao' => $observacao,
                        'id_observacao' => $idDaObservacao
                    ]
                ]);

                return;
            }

            $dados = $this->_valid($idDaObservacao, true);

            list($observacao, $resultado) = $this->toEdit($dados, $idDaObservacao);

            if (!$resultado && isset($resultado['erro']) && $resultado['erro']) {
                Functions::_setMessage($resultado['mensagem'], 'erro');
                header('Location: ' . URL . '/observacao/editar/' . $observacao->getId());
                return;
            }

            Functions::_setMessage('Observação com id #' . $observacao->getId() . ' editada com sucesso!', 'sucesso');
            header('Location: ' . URL . '/tarefa/detalhar/' . $observacao->getIdTarefa());
            return;
        }
    }