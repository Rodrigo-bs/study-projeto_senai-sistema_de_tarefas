<?php

    namespace Src\Routes;

    use Src\Routes\Routes;
    use Src\Core\Functions;

    class Erro {
        public function e404(): void {
            Functions::_loadView('e404', []);

            return;
        }
    }