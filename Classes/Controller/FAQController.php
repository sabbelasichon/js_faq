<?php
namespace JS\JsFaq\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014-2016 Jainish Senjaliya <jainishsenjaliya@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * FAQController
 */
class FAQController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	/**
	 * fAQRepository
	 *
	 * @var \JS\JsFaq\Domain\Repository\FAQRepository
	 * @inject
	 */
	protected $fAQRepository = NULL;

	/**
	 * configuration
	 *
	 * @var \JS\JsFaq\Service\Configuration
	 * @inject
	 */
	protected $configuration = NULL;
	
	/**
	 * action faq
	 *
	 * @return void
	 */
	public function faqAction() {

		$this->contentObj = $this->configurationManager->getContentObject();

		$this->settings['contentID'] = md5($this->contentObj->data['uid']);

		$template = $this->configuration->template();

		if($template==1){
			
			$detail = 0;

			if ($this->request->hasArgument('faq')) {

				$data = $this->request->getArguments('faq');

				if(intVal($data['faq'])){
					$detail = $data['faq'];
				}
			}
			
			$faq = $this->fAQRepository->getFAQData($detail);

			if ($this->settings['main']['displayFAQ'] == 'CategoryGroupWise' && $detail==0) {
				$faq = $this->fAQRepository->getFAQCategoryData($faq,$this->settings['main']['categories']);
			}

			if (count($faq) == 0) {
				$template = array("error"=>array('no_records'));
			}
			
			$this->view->assign('FAQ', $faq);
			$this->view->assign('detail', $detail);
		}

		$this->view->assign('template', $template);
		$this->view->assign('settings', $this->settings);

		// Include Additional Data
		$this->configuration->additionalData();
	}
}