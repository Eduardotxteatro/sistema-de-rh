<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Funcionarios Model
 *
 * @method \App\Model\Entity\Funcionario newEmptyEntity()
 * @method \App\Model\Entity\Funcionario newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Funcionario findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Funcionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Funcionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Funcionario[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Funcionario[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Funcionario[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Funcionario[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FuncionariosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('funcionarios');
        $this->setDisplayField('ID');
        $this->setPrimaryKey('ID');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->decimal('ID')
            ->allowEmptyString('ID', null, 'create');

        $validator
            ->decimal('CARGO_ID')
            ->requirePresence('CARGO_ID', 'create')
            ->notEmptyString('CARGO_ID');

        $validator
            ->scalar('NOME')
            ->maxLength('NOME', '150')
            ->requirePresence('NOME', 'create')
            ->notEmptyString('NOME');

        $validator
            ->scalar('EMAIL')
            ->maxLength('EMAIL', '150')
            ->requirePresence('EMAIL', 'create')
            ->notEmptyString('EMAIL')
            ->add('EMAIL', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('CPF')
            ->maxLength('CPF', '14')
            ->requirePresence('CPF', 'create')
            ->notEmptyString('CPF')
            ->add('CPF', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->dateTime('DATA_ADMISSAO')
            ->requirePresence('DATA_ADMISSAO', 'create')
            ->notEmptyDateTime('DATA_ADMISSAO');

        $validator
            ->boolean('ATIVO')
            ->notEmptyString('ATIVO');

        $validator
            ->dateTime('CREATED')
            ->allowEmptyDateTime('CREATED');

        $validator
            ->dateTime('MODIFIED')
            ->allowEmptyDateTime('MODIFIED');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['CPF']), ['errorField' => 'CPF']);
        $rules->add($rules->isUnique(['EMAIL']), ['errorField' => 'EMAIL']);

        return $rules;
    }
}
