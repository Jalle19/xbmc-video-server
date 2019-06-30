<?php

trait HasPremieredTrait
{

	/**
	 * @var string the date the movie premiered
	 */
	public $premiered;


	/**
	 * @return string|int
	 */
	public function getRenderedYear()
	{
		// If premiered is available we use the year from that
		if ($this->premiered !== '')
		{
			$premiereDate = DateTime::createFromFormat('Y-m-d', $this->premiered);

			if ($premiereDate !== false)
			{
				return $premiereDate->format('Y');
			}
		}

		// Year is usually zero or 1601 when it's not available
		if ($this->year !== 0 && $this->year !== 1601)
			echo $this->year;

		// If nothing is available
		return Yii::t('Misc', 'Not available');
	}
}
