<?php

    namespace Src\Entities;

    class Observacao {
        private $id = '';
        private $descricao = '';
        private $id_tarefa;
        private $id_responsavel = '';
        private $data_abertura = '';

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getDescricao() {
            return $this->descricao;
        }

        public function setDescricao($descricao) {
            $this->descricao = $descricao;
        }

        public function getDataAbertura($formatacao = false) {
            if ($formatacao) {
                return date('d/m/Y', strtotime($this->data_abertura));
            }

            return $this->data_abertura;
        }

        public function setDataAbertura($dataAbertura) {
            $this->data_abertura = $dataAbertura;
        }

        public function getIdTarefa() {
            return $this->id_tarefa;
        }

        public function setIdTarefa($idTarefa) {
            $this->id_tarefa = $idTarefa;
        }

        public function getIdResponsavel() {
            return $this->id_responsavel;
        }

        public function setIdResponsavel($idResponsavel) {
            $this->id_responsavel = $idResponsavel;
        }
    }