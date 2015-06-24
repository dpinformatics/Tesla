<?php
    include_once("vendor/ADOdb/adodb-exceptions.inc.php");
    include_once("vendor/ADOdb/adodb.inc.php");

    Class DB
    {
        private static $adodb;
        private static $openTransactions = 0;

        function __construct($args, &$reply = NULL)
        {

        }

        static function BeginTransaction()
        {
            DB::$openTransactions++;

            return DB::$adodb->StartTrans();
        }

        static function Commit($success = true)
        {
            DB::$openTransactions--;

            return DB::$adodb->CompleteTrans($success);
        }

        static function qstr($value)
        {
            return DB::$adodb->qstr($value);
        }

        static function connect($type, $host, $user, $pass, $database)
        {
            DB::$adodb = ADONewConnection($type);
            DB::$adodb->Connect($host, $user, $pass, $database);
            DB::$adodb->SetFetchMode(ADODB_FETCH_ASSOC);
            DB::$adodb->Execute("SET NAMES utf8");

            return (DB::$adodb ? true : false);
        }

        static function AffectedRows()
        {
            return DB::$adodb->Affected_Rows();
        }

        static function SelectLimit($sql, $numrows = -1, $offset = -1, $inputarr = false)
        {
            return DB::$adodb->SelectLimit($sql, $numrows, $offset, $inputarr);
        }

        /**
         * Executes the query using adodb; using the retries parameter, we can have the system automatically
         * retry for a number of times, which can be especially useful within a batch framework where locking conflicts
         * may occur.
         *
         * @param      $sql
         * @param int  $retries
         * @param null $meta
         *
         * @return bool
         * @throws Exception
         */
        static function Execute($sql, $retries = 1, &$meta = NULL)
        {
            $result = false;
            $retry = 0;
            while (!$result && $retry < $retries) {
                $retry++;
                try {
                    $rs = DB::$adodb->Execute($sql);
                    $result = $rs;
                    $meta["affected_rows"] = DB::$adodb->Affected_Rows();
                } catch (Exception $e) {
                    if ($retry < $retries) {
                        // we can still give it another shot...
                        sleep(1); // let's wait a second to give it another try...
                    }
                    else {
                        // this was our last attempt... Let's just bubble up the exception...
                        throw $e;
                    }
                }
            }
            $meta["retries"] = $retry;

            return $result;
        }

        static function MetaTables($args, &$reply = NULL)
        {
            return DB::$adodb->MetaTables();
        }

        static function MetaColumns($table)
        {
            return DB::$adodb->MetaColumns($table);
        }

        static function Insert_ID()
        {
            return DB::$adodb->Insert_ID();
        }
    }
