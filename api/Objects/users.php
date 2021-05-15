<?php
class Users
{

    // connection and table
    private $conn;
    private $table_name = "users";

    //  properties
    public $id;
    public $name;
    public $email;
    public $birthday;
    public $gender;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read()
    {

        // select all
        $query = "SELECT
                    u.name as name, u.email as email, u.birthday as birthday, g.name as gender
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        genders g
                            ON g.id = u.gender
                ORDER BY
                    p.name DESC";

        // query
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function create()
    {

        // query to insert
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, email=:email, birthday=:birthday, gender=:gender";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->birthday = htmlspecialchars(strip_tags($this->birthday));
        $this->gender = htmlspecialchars(strip_tags($this->gender));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":birthday", $this->birthday);
        $stmt->bindParam(":gender", $this->gender);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function readOne()
    {

        // query to read single record
        $query = "SELECT
                    u.name as name, u.email as email, u.birthday as birthday, g.name as gender
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        genders g
                            ON g.id = u.gender
            WHERE
                u.id = ?
            LIMIT
                0,1";


        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        // get  row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->birthday = $row['birthday'];
        $this->gender = $row['gender'];
    }

    // update the product
    function update()
    {

        // update query
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                email = :email,
                birthday = :birthday,
                gender = :gender
            WHERE
                id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->birthday = htmlspecialchars(strip_tags($this->birthday));
        $this->gender = htmlspecialchars(strip_tags($this->gender));

        // bind new values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':birthday', $this->birthday);
        $stmt->bindParam(':gender', $this->gender);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function delete()
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}

?>