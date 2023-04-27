<?php

    namespace Src;

    session_start();
    
    include './src/core/Functions.php';

    use Src\Routes\Erro;

    class App {
        private $namespace = 'Src\\Routes\\';

        private $erro = false;
        private $mensagemErro = '';

        public function app(): void {
            $this->routeSystem();
        }

        public function validLogin(): void {
            if (!isset($_COOKIE['token'])) {
                header('Location: ' .URL. '/usuario/login');
                exit;
            }
        }

        private function routeSystem(): void {
            (Array) $route = $this->getRoute();

            // valida as rotas
            $this->_validRoute($route);

            if ($this->erro) {
                $class = new Erro();
                $class->e404($this->mensagemErro);
                return;
            }

            $class = $this->namespace . $route['model'];
            $action = $route['action'];

            if ($action != 'login') {
                $this->validLogin();
            }

            $classInstance = new $class();
            $classInstance->$action($route['params']);
        }

        private function _validRoute(array $route): void {
            if (!preg_match("/^[a-z-?A-Z]+$/i", $route['action'])) {
                $this->setErro('Rota não existe');
                return;
            }

            (int) $class = $this->namespace . $route['model'];
            
            if (!class_exists($class)) {
                $this->setErro('Rota não existe');
                return;
            }

            if (!method_exists($class, $route['action'])) {
                $this->setErro('Rota não existe');
                return;
            }
        }

        protected function setErro(string $mensagem) {
            $this->erro = true;
            $this->mensagemErro = $mensagem;

            return false;
        }

        private function getRoute(): Array {
            $uri = explode('/', $_SERVER['REQUEST_URI']);
            array_shift($uri);

            if (count($uri) > 0 && $uri[0] != '') {
                $model = $uri[0];
                $action = isset($uri[1]) ? $uri[1] : '';
                $params = [];

                array_shift($uri); // remove model
                array_shift($uri); // remove action

                foreach ($uri as $parametro) {
                    if ($parametro != '') {
                        array_push($params, $parametro);
                    }
                }

                return [
                    'model' => $model,
                    'action' => $action == '' ? 'listar' : $action,
                    'params' => $params
                ];
            }

            return [
                'model' => 'Tarefa',
                'action' => 'listar',
                'params' => []
            ];
        }
    }