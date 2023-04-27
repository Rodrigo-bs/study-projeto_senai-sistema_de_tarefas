<?php

    namespace Src\Entities;

    class Tarefa {
        private $id = '';
        private $titulo = '';
        private $descricao = '';
        private $tipo = '';
        private $prioridade = '';
        private $data_abertura = '';
        private $status = '';
        private $id_responsavel = '';

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getTitulo() {
            return $this->titulo;
        }

        public function setTitulo($titulo) {
            $this->titulo = $titulo;
        }

        public function getDescricao() {
            return $this->descricao;
        }

        public function setDescricao($descricao) {
            $this->descricao = $descricao;
        }

        public function getTipo($formatacao = false) {
            if ($formatacao) {
                $tipos = [
                    '1' => 'Incidente',
                    '2' => 'Solicitação de Serviço',
                    '3' => 'Melhorias',
                    '4' => 'Projetos'
                ];

                return $tipos[$this->tipo];
            }

            return $this->tipo;
        }

        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }

        public function getPrioridade($formatacao = false) {
            if ($formatacao) {
                $prioridade = [
                    '1' => 'Alta',
                    '2' => 'Média',
                    '3' => 'Baixa',
                    '4' => 'Sem Prioridade'
                ];

                return $prioridade[$this->tipo];
            }

            return $this->prioridade;
        }

        public function setPrioridade($prioridade) {
            $this->prioridade = $prioridade;
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

        public function getStatus($formatacao = false) {
            if ($formatacao) {
                $status = [
                    '0' => 'Aberto',
                    '1' => 'Fechado'
                ];

                return $status[$this->status];
            }

            return $this->status;
        }

        public function setStatus($status) {
            $this->status = $status;
        }

        public function getIdResponsavel() {
            return $this->id_responsavel;
        }
        
        public function setIdResponsavel($idResponsavel) {
            $this->id_responsavel = $idResponsavel;
        }
    }