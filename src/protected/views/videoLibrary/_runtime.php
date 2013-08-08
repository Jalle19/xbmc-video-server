<?php

// Runtime is not always available, if so don't render anything
if ((int)$runtime > 0)
	echo CHtml::tag('p', array(), (int)($runtime / 60).' min');