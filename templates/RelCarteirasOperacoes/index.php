<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RelCarteirasOperaco[]|\Cake\Collection\CollectionInterface $relCarteirasOperacoes
 */
?>
<div class="relCarteirasOperacoes index content">
    <?= $this->Html->link(__('New Rel Carteiras Operaco'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Rel Carteiras Operacoes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('carteiras_investimento_id') ?></th>
                    <th><?= $this->Paginator->sort('operacoes_financeira_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relCarteirasOperacoes as $relCarteirasOperaco): ?>
                <tr>
                    <td><?= $relCarteirasOperaco->has('carteiras_investimento') ? $this->Html->link($relCarteirasOperaco->carteiras_investimento->id, ['controller' => 'CarteirasInvestimentos', 'action' => 'view', $relCarteirasOperaco->carteiras_investimento->id]) : '' ?></td>
                    <td><?= $relCarteirasOperaco->has('operacoes_financeira') ? $this->Html->link($relCarteirasOperaco->operacoes_financeira->id, ['controller' => 'OperacoesFinanceiras', 'action' => 'view', $relCarteirasOperaco->operacoes_financeira->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $relCarteirasOperaco->carteiras_investimento_id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $relCarteirasOperaco->carteiras_investimento_id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $relCarteirasOperaco->carteiras_investimento_id], ['confirm' => __('Are you sure you want to delete # {0}?', $relCarteirasOperaco->carteiras_investimento_id)]) ?>
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
