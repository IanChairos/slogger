<?php

namespace App\AdminModule\Presenters;

/**
 * @name SecuredPresenter
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class SecuredPresenter extends BasePresenter {

	public function startup() {
		parent::startup();
		
		if( !$this->user->isLoggedIn() ) {
			$this->redirect('Sign:in');
		}
	}
	
}
