<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CargosFixture
 */
class CargosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'ID' => ['type' => 'decimal', 'length' => '22', 'null' => false, 'default' => '"SYSTEM"."ISEQ$$_71729".nextval', 'comment' => null, 'precision' => null, 'unsigned' => null],
        'NOME' => ['type' => 'string', 'length' => '100', 'null' => false, 'default' => null, 'comment' => null, 'precision' => null, 'collate' => null],
        'SALARIO_BASE' => ['type' => 'decimal', 'length' => '10', 'precision' => '2', 'null' => false, 'default' => null, 'comment' => null, 'unsigned' => null],
        'CREATED' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'SYSDATE', 'comment' => null, 'precision' => null],
        'MODIFIED' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => 'SYSDATE
', 'comment' => null, 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ID'], 'length' => []],
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
                'NOME' => 'Lorem ipsum dolor sit amet',
                'SALARIO_BASE' => 1.5,
                'CREATED' => '2026-09-22 18:48:18',
                'MODIFIED' => '2026-09-22 18:48:18',
            ],
        ];
        parent::init();
    }
}
