<?php

    namespace Src\Controllers;

    use Src\Controllers\TarefaController;
    use Src\Controllers\UsuarioController;

    use Src\Models\ObservacaoModel;

    class ObservacaoController {
        private $observacaoModel;
        
        public function __construct() {
            $this->observacaoModel = new ObservacaoModel();
        }

        public function toListAll() {
            $observacoes = $this->observacaoModel->_select();
            return $observacoes;
        }

        public function toInsert($dados) {
            $inseriu = $this->observacaoModel->_insert($dados);

            if (!$inseriu) {
                return [
                    'erro' => true,
                    'mensagem' => 'Observação não inserida'
                ];
            }

            // Seleciona o ultimo registro
            $observacao = $this->observacaoModel->_select(['*'], [
                'id_responsavel_fk' => $dados['responsavel']
            ], '1', [
                'column' => 'id',
                'order' => 'DESC'
            ]);

            if (count($observacao) < 0) {
                return [
                    'erro' => true,
                    'mensagem' => 'Observação não inserida'
                ];
            }

            return current($observacao);
        }

        public function toEdit($dados = [], $idObservacao) {
            $observacao = current($this->observacaoModel->_select(['*'], [
                'id' => $idObservacao
            ]));

            if ($dados) {
                $resultado = $this->observacaoModel->_update([
                    'descricao' => $dados['descricao']
                ], [
                    'id' => $dados['id']
                ]);

                if (!$resultado) {
                    return [
                        'erro' => true,
                        'mensagem' => 'Erro interno!'
                    ];
                }

                return [$observacao, $resultado];
            }

            return $observacao;
        }
    }