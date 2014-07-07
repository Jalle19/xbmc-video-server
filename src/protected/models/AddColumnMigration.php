<?php

/**
 * Base class for migrations that add a column to a table.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class AddColumnMigration extends CDbMigration
{

	/**
	 * @return string
	 */
	abstract protected function getTableName();

	/**
	 * @return string
	 */
	abstract protected function getColumnName();

	/**
	 * @return string
	 */
	abstract protected function getColumnType();

	public function up()
	{
		$table = Yii::app()->db->schema->getTable($this->getTableName());

		if ($table === null)
			return false;

		// Check if column already exists
		$columns = $table->getColumnNames();

		if (!in_array($this->getColumnName(), $columns))
			$this->addColumn($this->getTableName(), $this->getColumnName(), $this->getColumnType());
	}

	public function down()
	{
		$this->dropColumn($this->getTableName(), $this->getColumnName());
	}

}
