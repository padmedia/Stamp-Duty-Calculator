<?php
namespace Craft;

class StampDutyCalculatorModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'propertyValue'     => array(AttributeType::Number, 'required' => true, 'label' => 'Property Value'),
			'purchaseType'      => array(AttributeType::String, 'required' => true, 'label' => 'Purchase Type')
		);
	}
}
