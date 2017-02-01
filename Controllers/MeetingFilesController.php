<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Committee;
use Application\Models\MeetingFile;
use Application\Models\MeetingFilesTable;

use Blossom\Classes\Controller;
use Blossom\Classes\Block;

class MeetingFilesController extends Controller
{
    public function index()
    {
        $search = [];

        if (!empty($_GET['committee_id'])) {
            try {
                $committee = new Committee($_GET['committee_id']);

                $this->template->title = $committee->getName();
                $this->template->blocks[] = new Block('committees/breadcrumbs.inc',  ['committee' => $committee]);
                $this->template->blocks[] = new Block('committees/header.inc',       ['committee' => $committee]);

                $search['committee_id'] = $committee->getId();
            }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        $table = new MeetingFilesTable();
        $list  = $table->find($search);

        $this->template->blocks[] = new Block('meetingFiles/list.inc', [
            'files'     => $list,
            'committee' => isset($committee) ? $committee : null
        ]);
    }

    public function update()
    {
        if (!empty($_REQUEST['meetingFile_id'])) {
            try { $file = new MeetingFile($_REQUEST['meetingFile_id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            // New files must have a committee_id passed in.
            if (!empty($_REQUEST['committee_id'])) {
                try {
                    $committee = new Committee($_REQUEST['committee_id']);
                    $file = new MeetingFile();
                    $file->setCommittee($committee);
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
        }

        if (isset($file)) {
            if (isset($_POST['committee_id'])) {
                try {
                    $file->handleUpdate($_POST);
                    if (isset($_FILES['meetingFile'])) {
                        $file->setFile($_FILES['meetingFile']);
                    }
                    $file->save();

                    $return_url = BASE_URL."/committees/meetings?committee_id={$file->getCommittee_id()};year={$file->getMeetingDate('Y')}";
                    header("Location: $return_url");
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            $committe = $file->getCommittee();
            $this->template->title = $committee->getName();
            $this->template->blocks[] = new Block('committees/breadcrumbs.inc',  ['committee' => $committee]);
            $this->template->blocks[] = new Block('committees/header.inc',       ['committee' => $committee]);
            $this->template->blocks[] = new Block('meetingFiles/updateForm.inc', ['meetingFile' => $file]);
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
        }
    }

    public function download()
    {
        if (!empty($_GET['meetingFile_id'])) {
            try {
                $file = new MeetingFile($_GET['meetingFile_id']);
                $this->template->setFilename('file');
                $this->template->blocks[] = new Block('files/download.inc', ['downloadFile'=>$file]);
            }
            catch (\Exception $e) {
                header('HTTP/1.1 404 Not Found', true, 404);
                $this->template->blocks[] = new Block('404.inc');
            }
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
        }
    }

    public function delete()
    {
        if (!empty($_GET['meetingFile_id'])) {
            try {
                $file = new MeetingFile($_GET['meetingFile_id']);
                $committe = $file->getCommittee();
                $file->delete();
                header('Location: '.BASE_URI.'/meetingFiles?committe_id='.$committe->getId());
                exit();
            }
            catch (\Exception $e) {
                header('HTTP/1.1 404 Not Found', true, 404);
                $this->template->blocks[] = new Block('404.inc');
            }
        }
        else {
            header('HTTP/1.1 404 Not Found', true, 404);
            $this->template->blocks[] = new Block('404.inc');
        }
    }
}
