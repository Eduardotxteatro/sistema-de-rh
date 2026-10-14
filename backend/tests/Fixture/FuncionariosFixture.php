<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncionariosFixture
 */
class FuncionariosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'ID' => ['type' => 'decimal', 'length' => '22', 'null' => false, 'default' => '"SYSTEM"."ISEQ$$_71732".nextval', 'comment' => null, 'precision' => null, 'unsigned' => null],
        'CARGO_ID' => ['type' => 'decimal', 'length' => '22', 'null' => false, 'default' => null, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'NOME' => ['type' => 'string', 'length' => '150', 'null' => false, 'default' => null, 'comment' => null, 'precision' => null, 'collate' => null],
        'EMAIL' => ['type' => 'string', 'length' => '150', 'null' => false, 'default' => null, 'comment' => null, 'precision' => null, 'collate' => null],
        'CPF' => ['type' => 'string', 'length' => '14', 'null' => false, 'default' => null, 'comment' => null, 'precision' => null, 'collate' => null],
        'DATA_ADMISSAO' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => null, 'precision' => null],
        'ATIVO' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1 ', 'comment' => null, 'precision' => null],
        'CREATED' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'SYSDATE', 'comment' => null, 'precision' => null],
        'MODIFIED' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'SYSDATE', 'comment' => null, 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
            'SYS_C008678' => ['type' => 'unique', 'columns' => ['CPF'], 'length' => []],
            'SYS_C008677' => ['type' => 'unique', 'columns' => ['EMAIL'], 'length' => []],
            'FK_FUNC_CARGO' => ['type' => 'foreign', 'columns' => ['CARGO_ID'], 'references' => ['SYSTEM.CARGOS', 'ID'], 'update' => 'setNull', 'delete' => 'noAction', 'length' => []],
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'ID' => 1.5,
                'CARGO_ID' => 1.5,
                'NOME' => 'Lorem ipsum dolor sit amet',
                'EMAIL' => 'Lorem ipsum dolor sit amet',
                'CPF' => 'Lorem ipsum ',
                'DATA_ADMISSAO' => '2026-09-22 18:49:51',
                'ATIVO' => 1,
                'CREATED' => '2026-09-22 18:49:51',
                'MODIFIED' => '2026-09-22 18:49:51',
            ],
        ];
        parent::init();
    }
}
