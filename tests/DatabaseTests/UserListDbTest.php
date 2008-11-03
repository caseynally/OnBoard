<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for UserList.
 * Generated by PHPUnit on 2008-09-29 at 10:55:37.
 */
class UserListDbTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var    UserList
     * @access protected
     */


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
	$dir = dirname(__FILE__);
	exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/../testData.sql");

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
    }

    /**
     * @todo Implement testFind().
     */
    public function testFind() {
    	$PDO = Database::getConnection();
    	$query = $PDO->query('select id from users order by username');
    	$result = $query->fetchAll();

    	$list = new UserList();
    	$list->find();
    	foreach($list as $i=>$user)
    	{
    		$this->assertEquals($user->getId(),$result[$i]['id']);
    	}

    }
}
?>