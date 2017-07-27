<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Legislation;

use Blossom\Classes\TableGateway;

class ActionTypesTable extends TableGateway
{
	public function __construct() { parent::__construct('legislationActionTypes', __namespace__.'\ActionType'); }

	public function find($fields=null, $order='ordering', $paginated=false, $limit=null)
	{
        return parent::find($fields, $order, $paginated, $limit);
	}
}
