<?php

class MediaFlagAudioSource extends MediaFlag
{

	protected function getIcon()
	{
		if (preg_match('/\.MD\.|\.mic\.dubbed\./i', $this->file))
			return 'MD';
		else if (preg_match('/\.LD\.|\.line\.dubbed\./i', $this->file))
			return 'LD';
		else
			return false;
	}

}
