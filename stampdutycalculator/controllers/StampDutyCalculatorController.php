<?php
/**
 * Stamp Duty Calculator plugin for Craft CMS
 *
 * @author    Robert Games
 * @copyright Copyright (c) 2018 Robert Games
 * @link      https://padmedia.co.uk
 * @package   StampDutyCalculator
 * @since     1.0.0
 */

namespace Craft;

class StampDutyCalculatorController extends BaseController
{

		protected $allowAnonymous = array('actionCalculate');
		protected $sdlt = 0;

		/**
		 * Calculate the StampDuty
		 *
		 * @throws Exception
		 */

    public function actionCalculate()
    {

			// Require a Post Request
			$this->requirePostRequest();

			// Load Validation Model
			$calc = new StampDutyCalculatorModel();

			// Grab Property Details
			$calc->propertyValue = preg_replace('/[^0-9]/', '', craft()->request->getPost('propertyValue'));
			$calc->purchaseType = craft()->request->getPost('purchaseType');

			// Validate Property Detai;s
			$calc->validate();

			// Property Details Validate
			if (!$calc->hasErrors())
			{

				// Calculate Stamp Duty
				$this->sdlt = craft()->stampDutyCalculator->calculate($calc);

				// Format Stamp Duty
				$this->sdlt = craft()->NumberFormatter->formatCurrency($this->sdlt, 'GBP',TRUE);

				// Is this an ajax request?
				if (craft()->request->isAjaxRequest())
				{
					// Return JSON Repsone
					$this->returnJson(array(
						'success'		=> true,
						'stampDuty'	=> $this->sdlt
					));
				}

				// Not AJAX - Are we redirecting to another page?
				if (craft()->request->getPost('redirect') != '')
				{
					// Redirect swapping {stampDuty} variable in redirect input
					$this->redirectToPostedUrl(array('stampDuty' => $this->sdlt));
				}

				// Not Redirecting - Pass SDLT back to page it was submitted
				craft()->urlManager->setRouteVariables(array(
        	'stampDuty' => $this->sdlt
    		));

			}

			/*
			 * Validation Error - Return Errors
			 */

			// If Ajax request return Errors in JSON
			if (craft()->request->isAjaxRequest())
			{
				return $this->returnErrorJson($calc->getErrors());
			}

			// Not Ajax Lets return errors inline
			else
			{
				craft()->userSession->setError('There was a problem with your submission, please check the form and try again!');
				craft()->urlManager->setRouteVariables(array(
					'error' => $calc
				));
			}
    }
}
