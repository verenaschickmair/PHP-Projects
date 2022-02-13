<?php


class Database
{
    public static $oMysqli;

    public static function loadConfig(string $sConfigFile)
    {
        require_once ($sConfigFile);
    }

    public static function deleteQuery(string $sQuery):bool
    {
        if (Database::connect())
        {
            $mResult = Database::$oMysqli->query($sQuery);
            Database::disconnect();
            return $mResult; // result only tells us if the SQL statement could be processed and not if something was actually deleted
        }
        else
        {
            echo "Could not connect in deleteQuery!";
            return false;
        }
    }

    public static function insertQuery(string $sQuery):int
    {
        if (Database::connect())
        {
            $mResult = Database::$oMysqli->query($sQuery);
            $iID = Database::$oMysqli->insert_id;
            Database::disconnect();
            return $iID; // We return the id of the newly inserted row
        }
        else
        {
            echo "Could not connect in insertQuery!";
            return 0;
        }
    }

    public static function selectQuery(string $sQuery): ?mysqli_result
    {
        if (Database::connect())
        {
            $mResult = Database::$oMysqli->query($sQuery);
            Database::disconnect();
            return $mResult; // a mysqli result object -> later fetch_assoc
        }
        else
        {
            echo "Could not connect in selectQuery!";
            return null;
        }
    }

    public static function updateQuery(string $sQuery):bool
    {
        if (Database::connect())
        {
            $mResult = Database::$oMysqli->query($sQuery);
            Database::disconnect();
            return $mResult; // result only tells us if the SQL statement could be processed and not if something was actually deleted
        }
        else
        {
            echo "Could not connect in deleteQuery!";
            return false;
        }
    }

    public static function realEscape(string $val1, string $val2 = ""):array
    {
        if (Database::connect())
        {
            $aResult = array("val1" => Database::$oMysqli->real_escape_string($val1),
                "val2" => Database::$oMysqli->real_escape_string($val2));
            Database::disconnect();
            return $aResult;
        }
        else
        {
            echo "Could not connect in realEscape!";
            return [];
        }
    }

    private static function connect():bool
    {
        Database::$oMysqli = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if (Database::$oMysqli->connect_error)
        {
            return false;
        }
        return true;
    }

    private static function disconnect()
    {
        if (Database::$oMysqli != null)
        {
            Database::$oMysqli->close();
        }
    }




}