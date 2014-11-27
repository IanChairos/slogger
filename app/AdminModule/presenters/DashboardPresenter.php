<?php

namespace App\AdminModule\Presenters;

use App\Model\repository\ILogRepository;

/**
 * @name DashboardPresenter
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class DashboardPresenter extends SecuredPresenter {
	
	public function actionDefault() {
		// render two tables
		$repository = $this->getLogRepository();
		$this->template->errorsByTime = $repository->getLastErrors(20);
		$this->template->errorsByPriority = $repository->getTopPriorityErrors(20);
	}
	
	
	public function actionDetail($id) {
		$error = $this->getLogRepository()->get($id);
		if( !$error ) {
			throw new \Nette\Application\BadRequestException('Unknown error ['.$id.']');
		}
		$this->template->error = $error;
	}
	
	/**
	 * @return ILogRepository
	 */
	private function getLogRepository() {
		return $this->context->getService('LogRepository');
	}
	
}
