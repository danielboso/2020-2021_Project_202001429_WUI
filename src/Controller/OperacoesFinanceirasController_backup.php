<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * OperacoesFinanceiras Controller
 *
 * @property \App\Model\Table\OperacoesFinanceirasTable $OperacoesFinanceiras
 * @method \App\Model\Entity\OperacoesFinanceira[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OperacoesFinanceirasController_backup extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Usuarios', 'CnpjFundos', 'DistribuidorFundos', 'TipoOperacoesFinanceiras'],
        ];
        $operacoesFinanceiras = $this->paginate($this->OperacoesFinanceiras);

        $this->set(compact('operacoesFinanceiras'));
    }

    /**
     * View method
     *
     * @param string|null $id Operacoes Financeira id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $operacoesFinanceira = $this->OperacoesFinanceiras->get($id, [
            'contain' => ['Usuarios', 'CnpjFundos', 'DistribuidorFundos', 'TipoOperacoesFinanceiras', 'RelCarteirasOperacoes'],
        ]);

        $this->set(compact('operacoesFinanceira'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $operacoesFinanceira = $this->OperacoesFinanceiras->newEmptyEntity();
        if ($this->request->is('post')) {
            $operacoesFinanceira = $this->OperacoesFinanceiras->patchEntity($operacoesFinanceira, $this->request->getData());
            if ($this->OperacoesFinanceiras->save($operacoesFinanceira)) {
                $this->Flash->success(__('The operacoes financeira has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The operacoes financeira could not be saved. Please, try again.'));
        }
        $usuarios = $this->OperacoesFinanceiras->Usuarios->find('list', ['limit' => 200]);
        $cnpjFundos = $this->OperacoesFinanceiras->CnpjFundos->find('list', ['limit' => 200]);
        $distribuidorFundos = $this->OperacoesFinanceiras->DistribuidorFundos->find('list', ['limit' => 200]);
        $tipoOperacoesFinanceiras = $this->OperacoesFinanceiras->TipoOperacoesFinanceiras->find('list', ['limit' => 200]);
        $this->set(compact('operacoesFinanceira', 'usuarios', 'cnpjFundos', 'distribuidorFundos', 'tipoOperacoesFinanceiras'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Operacoes Financeira id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $operacoesFinanceira = $this->OperacoesFinanceiras->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $operacoesFinanceira = $this->OperacoesFinanceiras->patchEntity($operacoesFinanceira, $this->request->getData());
            if ($this->OperacoesFinanceiras->save($operacoesFinanceira)) {
                $this->Flash->success(__('The operacoes financeira has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The operacoes financeira could not be saved. Please, try again.'));
        }
        $usuarios = $this->OperacoesFinanceiras->Usuarios->find('list', ['limit' => 200]);
        $cnpjFundos = $this->OperacoesFinanceiras->CnpjFundos->find('list', ['limit' => 200]);
        $distribuidorFundos = $this->OperacoesFinanceiras->DistribuidorFundos->find('list', ['limit' => 200]);
        $tipoOperacoesFinanceiras = $this->OperacoesFinanceiras->TipoOperacoesFinanceiras->find('list', ['limit' => 200]);
        $this->set(compact('operacoesFinanceira', 'usuarios', 'cnpjFundos', 'distribuidorFundos', 'tipoOperacoesFinanceiras'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Operacoes Financeira id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $operacoesFinanceira = $this->OperacoesFinanceiras->get($id);
        if ($this->OperacoesFinanceiras->delete($operacoesFinanceira)) {
            $this->Flash->success(__('The operacoes financeira has been deleted.'));
        } else {
            $this->Flash->error(__('The operacoes financeira could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
