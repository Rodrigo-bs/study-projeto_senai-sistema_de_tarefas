<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Observacao extends AbstractMigration
{
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
    public function up(): void  {
        $table = $this->table('observacao')
                ->addColumn('descricao', 'text')
                ->addColumn('data_abertura', 'date', ['null' => false])
                ->addColumn('id_tarefa_fk', 'integer')
                ->addColumn('id_responsavel_fk', 'integer')
                ->addForeignKey('id_tarefa_fk', 'tarefa', 'id', ['delete' => 'CASCADE'])
                ->addForeignKey('id_responsavel_fk', 'usuario', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
