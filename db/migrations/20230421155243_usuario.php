<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Src\Core\Functions;

final class Usuario extends AbstractMigration {
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void {
        $table = $this->table('usuario')
                ->addColumn('nome', 'string', ['limit' => 100])
                ->addColumn('email', 'string', ['limit' => 255])
                ->addColumn('senha', 'string', ['limit' => 100])
                ->addColumn('salt', 'string', ['limit' => 64]);

        $table->create();

        $senha = Functions::_crypt('12345678');
        $this->execute("INSERT INTO usuario (nome, email, senha, salt) VALUES ('rodrigo da silva brito', 'rodrigo@gmail.com', '" . $senha[0] . "', '" . $senha[1] . "')");
        $this->execute("INSERT INTO usuario (nome, email, senha, salt) VALUES ('pedro da silva ribeiro', 'pedro@gmail.com', '" . $senha[0] . "', '" . $senha[1] . "')");
    }
}
