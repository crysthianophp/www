<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Profiler\Profiler;

use Application\Model\SempleModel;
use Application\Model\Hydrator\SempleModelHydrator;
use Application\Model\Hydrator\Strategy\SempleHydratorStrategy;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	/*$connection = $this->getServiceLocator()->get('db');
    	$profiler = new Profiler();
    	$connection->setProfiler($profiler);*/
    	/*$query = $connection->query('SELECT * FROM cards WHERE type = ?', $connection::QUERY_MODE_PREPARE);
    	$replacements = array('number');
    	$result = $query->execute($replacements);*/

    	/*$query = $connection->query('SELECT * FROM cards WHERE type = ?', array('number'));

    	foreach($query as $res) {
    		echo '<pre>'.print_r($res, true).'</pre>';
    	}
    	die;*/

    	/*$statement = $connection->createStatement();
    	$statement->setSql('SELECT * FROM cards WHERE type = :type AND color = :color');
    	$container = new ParameterContainer(array(
    		'type' => 'picture', 'color' => 'diamond'
    	));
    	$statement->setParameterContainer($container);
    	$statement->prepare();

    	$result = $statement->execute();

    	foreach($result as $res) {
    		//echo '<pre>'.print_r($res).'</pre>';
    	}
    	$results = $profiler->getProfiles();
    	print_r($results);*/
    	//die;

        /*$dbOne = $this->getServiceLocator()->get('db_one');
        $dbOne = $this->getServiceLocator()->get('db_two');*/

        $model = new SempleModel();

        $data = array(
            'id' => 'Some id',
            'value' => 'Alguns impressionante Valor',
            'description' => 'Pecunia non olet'
        );

        $hydrator = new SempleModelHydrator();
        $hydrator->addStrategy(
            'primary', 
            new SempleHydratorStrategy()
        );

        $newObject = $hydrator->hydrate($data, $model);

        $extract = $hydrator->extract($newObject);

        echo "<pre>".print_r($extract, true)."</pre>";die;

        return new ViewModel();
    }

    public function ex1Action()
    {
    	$connection = $this->getServiceLocator()->get('db');
    	/*$sql = new Sql($connection);
    	$insert = $sql->insert('cards');*/
    	$insert = new Insert('cards');
    	$insert->columns(array(
    		'color',
    		'type',
    		'value'
    	));
    	$insert->values(array(
    		'color' => 'diamond',
    		'type' => 'picture',
    		'value' => 'Goblin'
    	));
    	//echo $insert->getSqlString();die;

    	/*$statement = $sql->prepareStatementForSqlObject($insert);
    	$result = $statement->execute();
    	print_r($result);*/


    	$tableGateway = new TableGateway('cards', $connection);

    	try{
    		$tableGateway->insertWith($insert);
    		echo "Inserido com sucesso";
    		$hasResult = true;
    		if(isset($hasResult)) {
    			$primaryKey = $tableGateway->getLastInsertValue();

    			$update = new Update('cards');
    			$update->set(array(
    				'color' => 'spade',
    				'value' => '10',
    				'type' => 'number'
    			));

    			$where = new Where();
    			$where->equalTo('id', $primaryKey);
    			$update->where($where);
    			$updated = $tableGateway->updateWith($update);
    		}
    	}catch(Exception $e){
    		echo "Erro ao inserir novo registro";
    	}
    	die;
    }
}
