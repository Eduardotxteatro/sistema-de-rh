<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Funcionario Entity
 *
 * @property string $ID
 * @property string $CARGO_ID
 * @property string $NOME
 * @property string $EMAIL
 * @property string $CPF
 * @property \Cake\I18n\FrozenTime $DATA_ADMISSAO
 * @property bool $ATIVO
 * @property \Cake\I18n\FrozenTime|null $CREATED
 * @property \Cake\I18n\FrozenTime|null $MODIFIED
 */
class Funcionario extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'CARGO_ID' => true,
        'NOME' => true,
        'EMAIL' => true,
        'CPF' => true,
        'DATA_ADMISSAO' => true,
        'ATIVO' => true,
        'CREATED' => true,
        'MODIFIED' => true,
    ];
}
