<?php

    namespace Src\Models;

    use Src\Core\DBConnection;
    use Src\Entities\Observacao;

    class ObservacaoModel extends DBConnection {
        public function __construct() {
            parent::__construct('observacao');
        }

        /** @var Array<Src\Model\Tarefa> */
        public function _select(Array $columns = ['*'], Array $where = [], String $limit = null, Array $order = null): Array {
            (Array) $entidadesFormatadas = [];
            
            if (count($where) > 0) {
                $observacoes = $this->selectBy($where, $columns, $limit, $order);

            } else {
                $observacoes = $this->selectAll($columns);
            }

            foreach ($observacoes as $observacaoRegistro) {
                $observacao = new Observacao();

                $observacao->setId($observacaoRegistro['id']);
                $observacao->setDescricao($observacaoRegistro['descricao']);
                $observacao->setDataAbertura($observacaoRegistro['data_abertura']);
                $observacao->setIdTarefa($observacaoRegistro['id_tarefa_fk']);
                $observacao->setIdResponsavel($observacaoRegistro['id_responsavel_fk']);

                array_push($entidadesFormatadas, $observacao);
            }

            return $entidadesFormatadas;
        }

        /** @var Number  */
        public function _insert(Array $dados) {
            $colunas = [
                'descricao' => $dados['descricao'],
                'data_abertura' => $dados['dataAbertura'],
                'id_tarefa_fk' => $dados['tarefa'],
                'id_responsavel_fk' => $dados['responsavel']
            ];

            return $this->insert($colunas);
        }

        /** @var Number  */
        public function _update($colunas, $where) {
            return $this->update($colunas, $where);
        }
    }