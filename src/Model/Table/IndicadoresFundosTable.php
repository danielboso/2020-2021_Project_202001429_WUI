<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IndicadoresFundos Model
 *
 * @property \App\Model\Table\CnpjFundosTable&\Cake\ORM\Association\BelongsTo $CnpjFundos
 * @property \App\Model\Table\TipoBenchmarksTable&\Cake\ORM\Association\BelongsTo $TipoBenchmarks
 *
 * @method \App\Model\Entity\IndicadoresFundo newEmptyEntity()
 * @method \App\Model\Entity\IndicadoresFundo newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresFundo get($primaryKey, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresFundo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IndicadoresFundo[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class IndicadoresFundosTable extends Table
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

        $this->setTable('indicadores_fundos');
        $this->setDisplayField('cnpj_fundo_id');
        $this->setPrimaryKey(['cnpj_fundo_id', 'periodo_meses', 'data_final']);

        $this->belongsTo('CnpjFundos', [
            'foreignKey' => 'cnpj_fundo_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('TipoBenchmarks', [
            'foreignKey' => 'tipo_benchmark_id',
        ]);
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
            ->integer('periodo_meses')
            ->allowEmptyString('periodo_meses', null, 'create');

        $validator
            ->date('data_final')
            ->allowEmptyDate('data_final', null, 'create');

        $validator
            ->decimal('rentabilidade')
            ->requirePresence('rentabilidade', 'create')
            ->notEmptyString('rentabilidade');

        $validator
            ->decimal('desvio_padrao')
            ->requirePresence('desvio_padrao', 'create')
            ->notEmptyString('desvio_padrao');

        $validator
            ->integer('num_valores')
            ->allowEmptyString('num_valores');

        $validator
            ->decimal('rentab_min')
            ->allowEmptyString('rentab_min');

        $validator
            ->decimal('rentab_max')
            ->allowEmptyString('rentab_max');

        $validator
            ->decimal('max_drawdown')
            ->requirePresence('max_drawdown', 'create')
            ->notEmptyString('max_drawdown');

        $validator
            ->integer('meses_acima_bench')
            ->allowEmptyString('meses_acima_bench');

        $validator
            ->decimal('sharpe')
            ->allowEmptyString('sharpe');

        $validator
            ->decimal('beta')
            ->allowEmptyString('beta');

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
        $rules->add($rules->existsIn(['cnpj_fundo_id'], 'CnpjFundos'));
        $rules->add($rules->existsIn(['tipo_benchmark_id'], 'TipoBenchmarks'));

        return $rules;
    }
}
