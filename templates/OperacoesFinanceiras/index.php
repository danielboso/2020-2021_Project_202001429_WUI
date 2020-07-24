<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OperacoesFinanceira[]|\Cake\Collection\CollectionInterface $operacoesFinanceiras
 */
?>
<div class="operacoesFinanceiras index content">
    <?= $this->Html->link(__('New Operacoes Financeira'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Operacoes Financeiras') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('usuario_id') ?></th>
                    <th><?= $this->Paginator->sort('cnpj_fundo_id') ?></th>
                    <th><?= $this->Paginator->sort('distribuidora_id') ?></th>
                    <th><?= $this->Paginator->sort('op_aplicacao') ?></th>
                    <th><?= $this->Paginator->sort('por_valor') ?></th>
                    <th><?= $this->Paginator->sort('valor_total') ?></th>
                    <th><?= $this->Paginator->sort('valor_cota') ?></th>
                    <th><?= $this->Paginator->sort('quantidade_cotas') ?></th>
                    <th><?= $this->Paginator->sort('data') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operacoesFinanceiras as $operacoesFinanceira): ?>
                <tr>
                    <td><?= $this->Number->format($operacoesFinanceira->id) ?></td>
                    <td><?= $operacoesFinanceira->has('usuario') ? $this->Html->link($operacoesFinanceira->usuario->nome, ['controller' => 'Usuarios', 'action' => 'view', $operacoesFinanceira->usuario->id]) : '' ?></td>
                    <td><?= $operacoesFinanceira->has('cnpj_fundo') ? $this->Html->link($operacoesFinanceira->cnpj_fundo->id, ['controller' => 'CnpjFundos', 'action' => 'view', $operacoesFinanceira->cnpj_fundo->id]) : '' ?></td>
                    <td><?= $this->Number->format($operacoesFinanceira->distribuidora_id) ?></td>
                    <td><?= h($operacoesFinanceira->op_aplicacao) ?></td>
                    <td><?= h($operacoesFinanceira->por_valor) ?></td>
                    <td><?= $this->Number->format($operacoesFinanceira->valor_total) ?></td>
                    <td><?= $this->Number->format($operacoesFinanceira->valor_cota) ?></td>
                    <td><?= $this->Number->format($operacoesFinanceira->quantidade_cotas) ?></td>
                    <td><?= h($operacoesFinanceira->data) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $operacoesFinanceira->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $operacoesFinanceira->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $operacoesFinanceira->id], ['confirm' => __('Are you sure you want to delete # {0}?', $operacoesFinanceira->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>