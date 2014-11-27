<?php

namespace App\Model;

use App\Model\repository\ILogRepository;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use App\Model\entity\LogEntity;

/**
 * @name AlertService
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class AlertService {

	/** @var ILogRepository */
	private $logRepository;

	/** @var IMailer */
	private $mailer;

	/** @var array */
	private $config;

	/** @var array */
	private $reportedErrors = array();
	

	public function __construct(ILogRepository $logRepository, IMailer $mailer, $config) {
		$this->logRepository = $logRepository;
		$this->mailer = $mailer;
		$this->config = $config;
	}


	public function checkLog() {
		$this->reportFatalErrors();
		$this->reportRepeatingErrors();
		$this->markReportedErrors();
	}

	
	private function reportFatalErrors() {
		$this->reportErrors($this->logRepository->getNewFatalErrors(), new \DateTime(), LogEntity::PRIORITY_FATAL, 1);
	}

	
	private function reportRepeatingErrors() {
		foreach ($this->config['priority'] as $priority => $priorityConfig) {
			$now = new \DateTime();
			$dateTimeFrom = $now->sub(new \DateInterval('PT' . $priorityConfig['timeSpan'] . 'M'));

			$repeatingErrors = $this->logRepository->getNewRepeatingErrors($priority, $dateTimeFrom, $priorityConfig['count']);
			$this->reportErrors($repeatingErrors, new \DateTime(), $priority, $priorityConfig['timeSpan']);
		}
	}

	
	private function reportErrors(array $errors, \DateTime $reportedAt, $priority, $timeSpan) {
		if( !$errors ) {
			return;
		}
		
		$this->emailAlert($errors, $priority, $timeSpan);
		foreach ($errors as $errorRow) {
			list($count,$error) = $errorRow;
			$this->reportedErrors[$error->getHash()] = $reportedAt->format('Y-m-d H:i:s');
		}
	}

	
	private function markReportedErrors() {
		$this->logRepository->markReportedMessages($this->reportedErrors);
		$this->reportedErrors = array();
	}

	
	private function emailAlert(array $errors, $priority, $timeSpan) {
		$html = '<br/><h1>[' . strtoupper($priority) . '] errors encountered in ' . $timeSpan . ' minute window:</h1><br/>';

		foreach( $this->sortErrorsByService($errors) as $service => $errorRows ) {
			$html .= '<br/><h2>Service <strong>[' . $service . ']</strong> encountered these errors :</h2><br/>';
			foreach ($errorRows as $errorRow) {
				list($count,$error) = $errorRow;
				$html .= '<h2>' . $count . 'x ['.$priority.'] ' . $error->getTitle() . '</h2>';
				$html .= '<p>' . $error->getDescription() . '</p><br/>';
			}
			
		}
		
		$subject = $priority == LogEntity::PRIORITY_FATAL ? 'Fatal errors' : 'Increased number of errors';
		$this->sendEmail($subject, $html);
	}
	
	
	private function sortErrorsByService(array $errors) {
		$mapped = array();
		foreach ($errors as $errorRow) {
			list($count,$error) = $errorRow;
			$mapped[$error->getService()][] = array($count,$error);
		}
		return $mapped;
	}

	
	private function sendEmail($subject, $html) {
		$mail = new Message();
//		$mail->setFrom($this->config['emailFrom'], $this->config['emailTo']);
		$mail->setHtmlBody($html);
		$mail->setSubject($subject);
//		$mail->addTo($this->config['emailTo']);
//		$this->mailer->send($mail);
		$msg = PHP_EOL.'<hr/>---EMAIL--'.PHP_EOL. print_r( $mail->getHeaders(),TRUE ). print_r( $mail->getHtmlBody(), TRUE);
		file_put_contents(LOG_DIR.'/mail.log.html', $msg, FILE_APPEND);
	}

}
