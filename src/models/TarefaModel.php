<?php

    namespace Src\Models;

    use Src\Core\DBConnection;
    use Src\Entities\Tarefa;

    class TarefaModel extends DBConnection {
        public function __construct() {
            parent::__construct('tarefa');
        }

        /** @var Array<Src\Model\Tarefa> */
        public function _select(Array $columns = ['*'], Array $where = [], String $limit = null, Array $order = null, String $offset = null): Array {
            (Array) $entidadesFormatadas = [];
            
            if (count($where) > 0) {
                $tarefas = $this->selectBy($where, $columns, $limit, $order, $offset);

            } else {
                $tarefas = $this->selectAll($columns);
            }

            foreach ($tarefas as $tarefaRegistro) {
                if (isset($tarefaRegistro['id']) && isset($tarefaRegistro['titulo'])) {
                    $tarefa = new Tarefa();

                    $tarefa->setId($tarefaRegistro['id']);
                    $tarefa->setTitulo($tarefaRegistro['titulo']);
                    $tarefa->setDescricao($tarefaRegistro['descricao']);
                    $tarefa->setTipo($tarefaRegistro['tipo']);
                    $tarefa->setPrioridade($tarefaRegistro['prioridade']);
                    $tarefa->setDataAbertura($tarefaRegistro['data_abertura']);
                    $tarefa->setStatus($tarefaRegistro['status']);
                    $tarefa->setIdResponsavel($tarefaRegistro['id_responsavel_fk']);

                    array_push($entidadesFormatadas, $tarefa);
                } else {
                    array_push($entidadesFormatadas, $tarefaRegistro);
                }
            }

            return $entidadesFormatadas;
        }

        /** @var Number  */
        public function _insert(Array $dados) {
            $colunas = [
                'titulo' => $dados['titulo'],
                'descricao' => $dados['descricao'],
                'tipo' => $dados['tipo'],
                'prioridade' => $dados['prioridade'],
                'data_abertura' => $dados['dataAbertura'],
                'status' => 0,
                'id_responsavel_fk' => $dados['responsavel'],
            ];

            return $this->insert($colunas);
        }

        /** @var Number  */
        public function _update($colunas, $where) {
            return $this->update($colunas, $where);
        }
    }