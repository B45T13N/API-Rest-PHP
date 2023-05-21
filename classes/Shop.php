<?php
namespace APITest\Classes;

use APITest\Classes\Exception\ShopException;

class Shop
{
    /**
     * The table name
     *
     * @access  protected
     * @var     string
     */
	protected static $table_name = 'magasin';

    /**
     * The primary key name
     *
     * @access  protected
     * @var     string
     */
    protected static $pk_name = 'id';

    /**
     * The object datas
     *
     * @access  private
     * @var     array
     */
	private $_array_datas = array();
	
    /**
     * The object id
     *
     * @access  private
     * @var     int
     */
	private $id;

    /**
     * The lang id
     *
     * @access  private
     * @var     int
     */
	private $lang_id = 1;

    /**
     * The link to the database
     *
     * @access  public
     * @var     object
     */
	public $db;

    /**
     * Shop constructor.
     *
     * @param      $db
     * @param      $datas
     *
     * @throws ShopException
     */
	public function __construct($db, $datas)
    {
        if (($datas != intval($datas)) && (!is_array($datas))) {
            throw new ShopException('The given datas are not valid.');
        }

        $this->db = $db;

        if (is_array($datas)) {
            $this->_array_datas = array_merge($this->_array_datas, $datas);
        } else {
            $this->_array_datas[self::$pk_name] = $datas;
        }
	}

    /**
     * Get the list of shop.
     *
     * @param      $db
     * @param      $begin
     * @param      $end
     *
     * @return     array of Shop
     * @throws ShopException
     */
	public static function getAll($db, $begin = 0, $end = 15)
	{
		$sql_get = "SELECT m.* FROM " . self::$table_name . " as m LIMIT " . $begin. ", " . $end;

		$result = $db->fetchAll($sql_get);

		return $result;
	}

    /**
     * Get the list of shop.
     *
     * @param      $db
     * @param      $filter
     *
     * @return     array of Shop
     * @throws ShopException
     */
    public static function getShopsFilteredByName($db, $filter = '')
    {
        $sql_get = "SELECT m.* FROM " . self::$table_name . " as m WHERE nom LIKE :name";

        $params = [
            'name' => "%".htmlspecialchars(strip_tags($filter))."%",
        ];

        $result = $db->fetchAll($sql_get, $params);

        return $result;
    }

    /**
     * Get the list of shop.
     *
     * @param      $db
     * @param      $id
     *
     * @return     Shop
     * @throws ShopException
     */
    public static function getShopById($db, $id)
    {
        $sql_get = "SELECT m.* FROM " . self::$table_name . " as m WHERE id = :id";

        $params = [
            'id' => htmlspecialchars(strip_tags($id)),
        ];

        $result = $db->fetchOne($sql_get, $params);

        return $result;
    }

    /**
     * Create a shop.
     *
     * @return     bool if succeed
     */
    public static function create($db, $name, $city)
    {
        $name = htmlspecialchars(strip_tags($name));
        $city = htmlspecialchars(strip_tags($city));

        $sql_insert = "INSERT INTO " . self::$table_name . " SET nom = :name, adresse = :city";

        $params = [
            'name' => $name,
            'city' => $city
        ];

        return $db->query($sql_insert, $params);
    }

    /**
     * Update a shop.
     *
     * @return     bool if succeed
     */
    public static function update($db, $name, $city, $id)
    {
        $name = htmlspecialchars(strip_tags($name));
        $city = htmlspecialchars(strip_tags($city));
        $id = htmlspecialchars(strip_tags($id));

        $sql_update = "UPDATE " . self::$table_name . " SET nom = :name, adresse = :city WHERE id = :id";

        $params = [
            'name' => $name,
            'city' => $city,
            'id' => $id
        ];

        return $db->query($sql_update, $params);
    }

    /**
     * Delete a shop.
     *
     * @return     bool if succeed
     */
	public function delete() 
	{
		$id = $this->getId();
        
		$sql_delete = "DELETE FROM " . self::$table_name . " WHERE " . self::$pk_name . " = :id";

        $params = 
            [
                'id' => $id,
            ];
        
		return $this->db->query($sql_delete, $params);
	}

    /**
     * Get the primary key
     *
     * @return     int
     */
	public function getId()
	{
		return $this->_array_datas[self::$pk_name];
	}

    /**
     * Access properties.
     *
     * @param      $param
     *
     * @return     string
     */
	public function __get( $param ) {

        $array_datas = $this->_array_datas;

        // Let's check if an ID has been set and if this ID is validd
        if ( !empty( $array_datas[self::$pk_name] ) ) {

        	// If it has been set, then try to return the data
            if ( array_key_exists($param, $array_datas ) ) {
                return $array_datas[$param];
            }

            // Let's dispatch all the values in $_array_datas
            $this->_dispatch();

            $array_datas = $this->_array_datas;

            if ( array_key_exists($param, $array_datas ) ) {

                return $array_datas[$param];

            }
        }

        return false;

    }

}