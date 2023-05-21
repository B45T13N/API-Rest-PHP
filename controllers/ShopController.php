<?php
namespace APITest\Controller;

use APITest\Classes\Shop;
use http\Env\Request;

class ShopController extends AbstractController
{
    /**
     * @Route('/api/shop_list.php')
     * @Method('GET')
     */
    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');

    	$db = $this->getDatabaseConnection();

        $list_shop = Shop::getAll($db, 0, 15);

        echo json_encode($list_shop);
    }

    /**
     * @Route('/api/shop_list.php')
     * @Method('POST')
     */
    public function shopListByName()
    {
        header('Content-Type: application/json; charset=utf-8');

        $db = $this->getDatabaseConnection();

        $filter = (isset($_POST['filter']) && !empty($_POST['filter']))? $_POST['filter']:'';

        $list_shop = Shop::getShopsFilteredByName($db, $filter);

        echo json_encode($list_shop);
    }

    /**
     * @Route('/api/shop_detail.php')
     * @Method('GET')
     */
    public function detail()
    {
        //header('Content-Type: application/json; charset=utf-8');
        $id = (isset($_GET['id']) && !empty($_GET['id']))? $_GET['id']:0;

        $db = $this->getDatabaseConnection();

    	if (!empty($id)) {

    		$result = Shop::getShopById($db, $id);

            if($result)
            {
                http_response_code(200);
                echo json_encode($result);
            }
            else
            {
                http_response_code(404);
                echo json_encode(array("message" => "This shop doesn't exist."));
            }

    	} else {
            http_response_code(404);
            echo json_encode(array("message" => "This shop doesn't exist."));
    	}

    }



    /**
     * @Route('/api/delete_shop.php')
     * @Method('DELETE')
     */
    public function delete()
    {
        $db = $this->getDatabaseConnection();

        $id = (isset($_POST['id']) && !empty($_POST['id'])) ? $_POST['id'] : 0;

        if (!empty($id)) {

            $shop = new Shop($db, $id);

            $result = $shop->delete();

            if($result)
            {
                http_response_code(200);
                echo json_encode(array("message" => "This shop has been delete"));
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "This shop doesn't exist or it's already deleted."));
            }

        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "This shop doesn't exist or it's already deleted."));
        }

    }

    /**
     * @Route('/api/create_shop.php')
     * @Method('POST')
     */
    public function create()
    {
        $db = $this->getDatabaseConnection();

        $name = $_POST['name'];
        $city = $_POST['city'];

        if (!empty($name) && !empty($city)) {

            $result = Shop::create($db, $name, $city);

            if($result)
            {
                http_response_code(201);
                echo json_encode(array("message" => "This shop has been created"));
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "This shop doesn't exist or it's already deleted."));
            }

        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Too few arguments to add a new shop"));
        }

    }

    /**
     * @Route('/api/update_shop.php')
     * @Method('PUT')
     */
    public function update()
    {
        $db = $this->getDatabaseConnection();

        $donnees = json_decode(file_get_contents("php://input"), true);
        $name = $donnees['name'];
        $city = $donnees['city'];
        $id = $donnees['id'];

        if (!empty($id) && !empty($name) && !empty($city))
        {
            $result = Shop::update($db, $name, $city, $id);

            if($result)
            {
                http_response_code(201);
                echo json_encode(array("message" => "This shop has been updated"));
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "This shop doesn't exist or it's already deleted."));
            }

        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Too few arguments to update a shop"));
        }

    }
}