<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers;

use Application\Models\Legislation\Legislation;
use Application\Models\Legislation\LegislationTable;
use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class LegislationController extends Controller
{
    public function index()
    {
        $table = new LegislationTable();
        $list  = $table->find();

        $this->template->blocks[] = new Block('legislation/list.inc', ['legislation'=>$list]);
    }

    public function update()
    {
        if (!empty($_REQUEST['id'])) {
            try { $legislation = new Legislation($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMesssages'][] = $e; }
        }
        else { $legislation = new Legislation(); }

        if (isset($legislation)) {
            if (isset($_POST['number'])) {
                try {
                    $legislation->handleUpdate($_POST);
                    $legislation->save();
                    header('Location: '.BASE_URL.'/legislation');
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMesssages'][] = $e; }
            }
            $this->template->blocks[] = new Block('legislation/updateForm.inc', ['legislation'=>$legislation]);
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
        }
    }
}
