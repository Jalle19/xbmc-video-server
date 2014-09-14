<?php

/* @var $actorDataProvider LibraryDataProvider */

?>
<div class="cast">

	<h3><?php echo Yii::t('Media', 'Cast'); ?></h3>

	<?php echo FormHelper::helpBlock(Yii::t('Movies', "Click an image to see other movies with that person, or click the name to go to the person's IMDb page")); ?>

	<div class="row-fluid">
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$actorDataProvider,
			'itemView'=>'//videoLibrary/_actorGridItem',
			'itemsTagName'=>'ul',
			'itemsCssClass'=>'thumbnails actor-grid',
			'enablePagination'=>false,
			'template'=>'{items}'
		)); ?>
	</div>

</div>