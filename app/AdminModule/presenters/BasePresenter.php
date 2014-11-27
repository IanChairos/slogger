<?php

namespace App\AdminModule\Presenters;

use \Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter {

	public function flashSuccess($message) {
		$this->flashMessage($message, 'success');
	}

	
	public function flashWarning($message) {
		$this->flashMessage($message, 'warning');
	}

	
	public function flashError($message) {
		$this->flashMessage($message, 'error');
	}
	
}
