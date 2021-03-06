<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Mailer\Mailer;

//use App\Controller\TableRegistry;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 * @method \App\Model\Entity\Usuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosController_backup2 extends AppController {

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index() {
		$this->paginate = [
			'contain' => ['TipoPlanos', 'TipoEtapasRegistros'],
		];
		$usuarios = $this->paginate($this->Usuarios);

		$this->set(compact('usuarios'));
	}

	/**
	 * View method
	 *
	 * @param string|null $id Usuario id.
	 * @return \Cake\Http\Response|null|void Renders view
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function view($id = null) {
		$usuario = $this->Usuarios->get($id, [
			'contain' => ['TipoPlanos', 'TipoEtapasRegistros', 'CarteirasInvestimentos', 'OperacoesFinanceiras'],
		]);

		$this->set(compact('usuario'));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add() {
		$usuario = $this->Usuarios->newEmptyEntity();
		if ($this->request->is('post')) {
			$usuario = $this->Usuarios->patchEntity($usuario, $this->request->getData());
			if ($this->Usuarios->save($usuario)) {
				$this->Flash->success(__('The usuario has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The usuario could not be saved. Please, try again.'));
		}
		$tipoPlanos = $this->Usuarios->TipoPlanos->find('list', ['limit' => 200]);
		$tipoEtapasRegistros = $this->Usuarios->TipoEtapasRegistros->find('list', ['limit' => 200]);
		$this->set(compact('usuario', 'tipoPlanos', 'tipoEtapasRegistros'));
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id Usuario id.
	 * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($id = null) {
		$usuario = $this->Usuarios->get($id, [
			'contain' => [],
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$usuario = $this->Usuarios->patchEntity($usuario, $this->request->getData());
			if ($this->Usuarios->save($usuario)) {
				$this->Flash->success(__('The usuario has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('The usuario could not be saved. Please, try again.'));
		}
		$tipoPlanos = $this->Usuarios->TipoPlanos->find('list', ['limit' => 200]);
		$tipoEtapasRegistros = $this->Usuarios->TipoEtapasRegistros->find('list', ['limit' => 200]);
		$this->set(compact('usuario', 'tipoPlanos', 'tipoEtapasRegistros'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id Usuario id.
	 * @return \Cake\Http\Response|null|void Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null) {
		$this->request->allowMethod(['post', 'delete']);
		$usuario = $this->Usuarios->get($id);
		if ($this->Usuarios->delete($usuario)) {
			$this->Flash->success(__('The usuario has been deleted.'));
		} else {
			$this->Flash->error(__('The usuario could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}

	/*
	 * ****************************************************************************************
	 * ****************************************************************************************
	 */

	public function login($id = null, $recemRegistrado = null) {
		//$session = $this->request->getSession();
		if ($this->request->is('post')) {
			$dadosLogin = $this->request->getData(); //$this->Usuarios->patchEntity($usuario, $this->request->getData());
			$query = $this->Usuarios->find()->contain(['TipoPlanos', 'TipoEtapasRegistros'])->where('cpf=' . $dadosLogin['cpf']);
			$usuario = $query->first();
			$session = $this->request->getSession();
			if ($usuario != null) {
				if ($dadosLogin['senha'] == $usuario['senha']) { //verifica que login e senha s??o v??lidos
					// find
					$podeLogar = $usuario['tipo_etapas_registro']['podeLogar'];
					$this->set(compact('usuario'));
					if ($podeLogar) { // se usuario j?? confirmou email e est?? em etapas mais avan??adas do registro, ent??o loga
						$words = explode(' ', $usuario['nome']);
						$session->write('User.firstname', $words[0]); // apenas para mostrar nome no canto superior direito
						//$session->write('User', $usuario);
						$session->write('User.id', $usuario['id']); // o mais importante
						$session->write('User.plano', $usuario['tipo_plano_id']);
						$session->write('User.etapa', $usuario['tipo_etapas_registro_id']);
						$this->Flash->success(__('Seu login foi realizado com sucesso, {0}.', $words[0]));//,'.words[0]));
						return $this->redirect('/');
					} else { // mensagem de erro informando que precisa avan??ar no registro
						$this->Flash->error(__('Voc?? ainda n??o confirmou seu e-mail. Por favor, acesse a conta de e-mail cadastrada e clique no link na mensagem enviada a voc??.'));
						//return $this->redirect('/');
					}
				}
			}
			$session->write('User.id', null);
			$this->Flash->error(__('Login incorreto. Por favor, tente novamente.'));
		}
	}

	public function logout() {
		$session = $this->request->getSession();
		$session->write('User', null);
		$session->write('User.id', null);
		$session->write('User.plano', null);
		return $this->redirect('/');
	}

	function generateRandomValidationCode() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 32; $i++) {
			$randstring .= $characters[rand(0, strlen($characters))];
		}
		return $randstring;
	}

	function registerWaitingForUserToValidateCode($id) {
		$code = $this->generateRandomValidationCode();
		$dateTime = new \DateTime('now');
		$dt = $dateTime->format('Y-m-d\TH:i:s.u');
		$params = 'id=' . $id . '&vc=' . $code . '&dt=' . $dt;
		$url = 'http://fundosinvest.inf.ufsc.br/Usuarios/validate_email';
		$link = $url . '?' . $params;
		echo $link;
		//exit();
		$usuariosAguardando = TableRegistry::getTableLocator()->get('UsuariosAguardandoValidacoesEmails');
		// apaga eventuais registros de espera por c??digos anteriores
		$numEnvios = 1;
		$query = $usuariosAguardando->find()
				->where(['usuarios_id' => $id]);
		$row = $query->first();
		if ($row) {
			$numEnvios = $row['num_envios'] + 1;
			$query->delete()
					->where(['usuarios_id' => $id])
					->execute();
		}
		// inclui novo registro
		$query = $usuariosAguardando->query();
		$query->insert(['usuarios_id', 'codigo_validacao', 'data_envio_email', 'num_envios']);
		$query->values(['usuarios_id' => $id, 'codigo_validacao' => $code, 'data_envio_email' => $dt, 'num_envios' => $numEnvios])
				->execute();
		return $link;
	}

	public function validateEmail() {
		$id = $this->getRequest()->getParam('?')['id'];
		$vc = $this->getRequest()->getParam('?')['vc'];
		$dt = $this->getRequest()->getParam('?')['dt'];
		$usuariosAguardando = TableRegistry::getTableLocator()->get('UsuariosAguardandoValidacoesEmails');
		$query = $usuariosAguardando->find()
				->where(['usuarios_id' => $id, 'codigo_validacao' => $vc]);
		$row = $query->first();
		if ($row) { // achou usu??rio e c??digo corretos
			// atualiza tabela do usuario informando que ele j?? validou o email
			$tiposEtapas = TableRegistry::getTableLocator()->get('TipoEtapasRegistros');
			$query = $tiposEtapas->find()
					->where(['nome_curto' => 'ValidacaoEmail']);
			$row = $query->first(); // TODO: sempre deve encontrar, mas deveria checar isso
			$novaEtapaId = $row['id'];
			$this->Usuarios->query()->update()
					->set(['tipo_etapas_registro_id' => $novaEtapaId])
					->where(['id' => $id])
					->execute();
			// apaga usu??rio da tabela de esperando pelo c??digo
			$usuariosAguardando->query()->delete()
					->where(['usuarios_id' => $id, 'codigo_validacao' => $vc])
					->execute();
			$this->Flash->success(__('Seu endere??o de e-mail foi validado com sucesso. Voc?? j?? pode realizar seu login. Bem-vindo.'));
		} else {
			// verifica se esse usu??rio j?? pode logar ou n??o, antes de dar a mensagem adequada
			$query = $this->Usuarios->find()
					->contain('TipoEtapasRegistros')
					->where(['Usuarios.id' => $id]);
			$query->execute();
			$row = $query->first();
			if ($row) {
				if ($row['tipo_etapas_registro']['podeLogar']) {
					$this->Flash->error(__('O c??digo informado n??o ?? mais v??lido. Esse usu??rio j?? validou o e-mail anteriormente e j?? pode realizar o login.'));
					return $this->redirect(['controller' => 'Usuarios', 'action' => 'login', $id, true]);
				}
			}
			$this->Flash->error(__('O c??digo informado n??o ?? mais v??lido. Possivelmente uma mensagem mais recente com outro c??digo foi enviada ou o prazo definido para esse c??digo j?? expirou. Procure por uma mensagem mais recente no seu e-mail ou tente fazer o login e solicite o envio de um novo c??digo.'));
		}
		return $this->redirect(['controller' => 'Usuarios', 'action' => 'login', $id, true]);
	}

	public function register() {
		//var_dump($this->registerWaitingForUserToValidateCode(4));
		//exit();
		$usuario = $this->Usuarios->newEmptyEntity();
		if ($this->request->is('post')) {
			$this->sendEmail();
			exit();
			$data = $this->request->getData();
			$usuario = $this->Usuarios->patchEntity($usuario, $data);
			// completa campos inexistentes no formul??rio
			$usuario->coment = "";
			$usuario->dt_reg = date('y-m-d');
			$usuario->tipo_etapas_registro_id = 1;
			$usuario->tipo_plano_id = 1;
			if ($saved = $this->Usuarios->save($usuario)) {
				$this->Flash->success(__('O usu??rio foi registrado com sucesso.'));
				$id = $saved['id'];
				$link = $this->registerWaitingForUserToValidateCode($id);
				$emailSent = $this->sendEmail($usuario, $link);
				if ($emailSent) {
					$this->Flash->success(__('Uma mensagem foi enviada ao e-mail registrado. Confirme seu e-mail clicando no link existente na mensagem enviada e ent??o fa??a o login abaixo, usando as informa????es registradas.'));
				} else {
					$this->Flash->error(__('N??o foi poss??vel enviar uma mensagem ao e-mail registrado. Essa mensagem ?? importante pois possui um link para confirma????o do endere??o de e-mail. Tentaremos enviar essa mensagem novamente periodicamente, ou voc?? pode tentar fazer o login com as informa????es registradas e ter?? op????o de reenviarmos a mensagem ou de alterar seu sendere??o de e-mail.'));
				}
				return $this->redirect(['controller' => 'Usuarios', 'action' => 'login', $usuario->get('id'), true]);
			}
			$this->Flash->error(__('O usu??rio n??o p??de ser registrado. Corrija as informa????es explicitadas abaixo e tente novamente.'));
		}
		//$tipoPlanos = $this->Usuarios->TipoPlanos->find('list', ['limit' => 200]);
		//$tipoEtapasRegistros = $this->Usuarios->TipoEtapasRegistros->find('list', ['limit' => 200]);
		$this->set(compact('usuario')); //, 'tipoPlanos', 'tipoEtapasRegistros'));
	}

	function sendValidationEmail($usuario, $link) {
		try {
			$mailer = new Mailer('default');
			$mailer->addTo($usuario['email']);
			$mailer->setSubject('Seu Registro no FundosInvest: Valida????o de e-mail');
			$resp = $mailer->deliver('Clique no link para validar seu e-mail e habilitar sua conta gratuitamente: ' . $link);
			return true;
		} catch (Exception $ex) {
			return false;
		}
		//var_dump($resp);
		/*
		  $email = new \Cake\Mailer\Email();
		  $email->setTo('cancian@lisha.ufsc.br');
		  $email->setSubject('TESTE');
		  //$email->setMessage('MENSAGEM');
		  $email->send('MENSAGEM');

		  /*
		  $mailer = new Mailer('default');
		  $mailer->setFrom(['fundos.invest.rlc@gmail.com' => 'FundosInvest'])
		  ->setTo('cancian@lisha.ufsc.br')
		  ->setSubject('About')
		  ->deliver('My message');

		  /*
		  $email->template('email')
		  ->subject('Student Portal - Signup')
		  ->emailFormat('html')
		  ->to($this->request->data['email'])
		  //->viewVars(['confirmation_link' => $confirmation_link,'username'=>$username])
		  ->viewVars(['username' => $username])
		  ->from('dotnetdev555@gmail.com')
		  ->send();
		  if ($email->send()) {
		  // Success
		  echo "mail sent";
		  } else {
		  echo "Mail not sent";
		  // Failure
		  }
		 * */
	}

}
