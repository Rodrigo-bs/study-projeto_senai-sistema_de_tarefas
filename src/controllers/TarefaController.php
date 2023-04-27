<?php

    namespace Src\Controllers;

    use Src\Models\UsuarioModel;
    use Src\Models\ObservacaoModel;

    use Src\Models\TarefaModel;

    class TarefaController  {
        private $tarefaModel;
        
        protected $usuarioModel;
        protected $observacaoModel;
        
        public function __construct() {
            $this->usuarioModel = new UsuarioModel();
            $this->observacaoModel = new ObservacaoModel();
            $this->tarefaModel = new TarefaModel();
        }

        public function toListAll($retornoCompleto = false, $paginacao = false, $offSet = 0) {
            $tarefas = $this->tarefaModel->_select();
            
            // Gambiarra, tava sem tempo kkkk
            if ($retornoCompleto && $paginacao) {
                $quantidadeDeTarefas = current($this->tarefaModel->_select(['count(id)']));
                $usuarios = $this->usuarioModel->_select();
                
                $tarefas = $this->tarefaModel->_select(['*'], ['status' => '0'], '5', [
                    'column' => 'id',
                    'order' => 'ASC'
                ], $offSet);

                return [$tarefas, $usuarios, $quantidadeDeTarefas];
            } else if ($retornoCompleto) {
                $usuarios = $this->usuarioModel->_select();
                $tarefas = $this->tarefaModel->_select(['*'], ['status' => '1']);
                
                return [$tarefas, $usuarios];
            }

            return $tarefas;
        } 

        public function toInsert($dados) {
            $inseriu = $this->tarefaModel->_insert($dados);

            if (!$inseriu) {
                return [
                    'erro' => true,
                    'mensagem' => 'Tarefa não inserida'
                ];
            }

            // Seleciona o ultimo registro
            $tarefa = $this->tarefaModel->_select(['*'], [
                'id_responsavel_fk' => $dados['responsavel']
            ], '1', [
                'column' => 'id',
                'order' => 'DESC'
            ]);

            if (count($tarefa) < 0) {
                return [
                    'erro' => true,
                    'mensagem' => 'Tarefa não inserida'
                ];
            }

            return current($tarefa);
        }

        public function toDetalhar(String $id) {
            $tarefa = $this->tarefaModel->_select(['*'], [
                'id' => $id
            ]);

            $usuarios = $this->usuarioModel->_select();

            $observacoes = $this->observacaoModel->_select(['*'], [
                'id_tarefa_fk' => $id
            ]);

            return [
                current($tarefa),
                $usuarios,
                $observacoes
            ];
        }

        public function toAssumir(String $idDoUsuario, String $idDaTarefa) {
            $tarefa = $this->tarefaModel->_update([
                'id_responsavel_fk' => $idDoUsuario
            ], [
                'id' => $idDaTarefa
            ]);

            return $tarefa;
        }

        public function toFinalizar(String $idDaTarefa, String $idDoUsuarioDaSessao) {
            $tarefa = current($this->tarefaModel->_select(['*'], [
                'id' => $idDaTarefa
            ]));

            if ($tarefa->getIdResponsavel() != $idDoUsuarioDaSessao) {
                return [
                    'erro' => true,
                    'mensagem' => 'Usuário não é o responsável da tarefa'
                ];
            }

            $tarefa = $this->tarefaModel->_update([
                'status' => '1'
            ], [
                'id' => $idDaTarefa
            ]);

            return;
        }
    }