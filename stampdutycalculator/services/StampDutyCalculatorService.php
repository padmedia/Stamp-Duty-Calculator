<?php
/**
 * Stamp Duty Calculator plugin for Craft CMS
 *
 * StampDutyCalculator Service
 *
 * --snip--
 * All of your plugin’s business logic should go in services, including saving data, retrieving data, etc. They
 * provide APIs that your controllers, template variables, and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 * --snip--
 *
 * @author    Robert Games
 * @copyright Copyright (c) 2018 Robert Games
 * @link      https://padmedia.co.uk
 * @package   StampDutyCalculator
 * @since     1.0.0
 */

namespace Craft;

class StampDutyCalculatorService extends BaseApplicationComponent
{
    /**
     * Calculates Stamp Duty Based on Property Value and Purchase Type
     *
     * @param		$calc Object
		 * @return	stampduty
     *
     */

		protected $sdlt = 0; //
		protected $taxablePortion = 0;

    public function calculate($calc)
    {

			// First-Time buyers purchasing a property over £500,000 are excempt
			// from Stamp Duty Relief

			if ($calc->propertyValue > 500000 && $calc->purchaseType == 'firstTimeBuyer')
			{
				$calc->purchaseType = 'singleProperty';
			}

			// Stamp Duty is charged differently depending on the buyer
			switch ($calc->purchaseType)
			{

				case 'firstTimeBuyer':

					// First time buyers pay SDLT over 300,000
					if ($calc->propertyValue > 300000 && $calc->propertyValue <= 500000)
					{
						$this->taxablePortion = $calc->propertyValue - 300000;
						$this->sdlt =  $this->taxablePortion * 0.05; // 5%
					}
					break;

				// More than one Property
				case 'additionalProperty':

					// No Stamp Duty under 40,000
					if ($calc->propertyValue >= 40000 && $calc->propertyValue <= 125000)
					{
						$this->taxablePortion = $calc->propertyValue;
						$this->sdlt = $this->taxablePortion * 0.03; // 3%
					}

					if ($calc->propertyValue > 125000 && $calc->propertyValue <= 250000)
					{
						$this->taxablePortion = $calc->propertyValue - 125000;
						$this->sdlt = $this->taxablePortion * 0.05 + 3750; // 5%
					}

					if ($calc->propertyValue > 250000 && $calc->propertyValue <= 925000)
					{
						$this->taxablePortion = $calc->propertyValue - 250000;
						$this->sdlt = $this->taxablePortion * 0.08 + 10000; // 13%
					}

					if ($calc->propertyValue > 925000 && $calc->propertyValue <= 1500000)
					{
						$this->taxablePortion = $calc->propertyValue - 925000;
						$this->sdlt = $this->taxablePortion * 0.13 + 64000; // 13%
					}

					if ($calc->propertyValue > 1500000)
					{
						$this->taxablePortion = $calc->propertyValue - 1500000;
						$this->sdlt = $this->taxablePortion * 0.15 + 138750; // 1@%
					}

					break;


				// Single Property
				default:

					if ($calc->propertyValue > 125000 && $calc->propertyValue <= 250000)
					{
						$this->taxablePortion = $calc->propertyValue - 125000;
						$this->sdlt = $this->taxablePortion * 0.02; // 2%
					}

					if ($calc->propertyValue > 250000 && $calc->propertyValue <= 925000)
					{
						$this->taxablePortion = $calc->propertyValue - 250000;
						$this->sdlt = $this->taxablePortion * 0.05 + 2500; // 5%
					}

					if ($calc->propertyValue > 925000 && $calc->propertyValue <= 1500000)
					{
						$this->taxablePortion = $calc->propertyValue - 925000;
						$this->sdlt = $this->taxablePortion * 0.1 + 36250; // 10%
					}

					if ($calc->propertyValue > 1500000)
					{
						$this->taxablePortion = $calc->propertyValue - 1500000;
						$this->sdlt = $this->taxablePortion * 0.12 + 93750; // 1@%
					}

					break;

			}

			return $this->sdlt;

    }

}
