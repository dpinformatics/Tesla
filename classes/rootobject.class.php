<?php

    /**
     * @author  David Heremans
     *
     * The root of all objects.  All business classes shoudl inherit from this class
     */
    Class RootObject
    {
        // lists of predefined column names that are to be treated as metadata
        protected $metaattributes = array("seqid", "objid", "created", "createdBy", "modified", "modifiedBy", "isActive", "IPv4");
        // list of all attributes (retrieved from the database
        protected $attributes;
        // values of the object
        protected $attributevalues;
        // is the object dirty? (used for avoiding unnecessary saves...
        protected $isPersistent;


        public function __construct()
        {
            //echo "constructing class";

            $this->attributes = array_change_key_case(DB::MetaColumns($this->tableName()), CASE_LOWER);
            foreach (array_keys($this->attributes) as $att) {
                $this->attributevalues[$att] = array(
                    "value"          => NULL,
                    "persistedvalue" => NULL
                );
            }

            foreach($this->attributes as $att) {
                if($att->has_default) {
                    $this->att($att->name, $att->default_value);
                }
            }


        }


        public function tableName()
        {
            return strtolower(get_class($this));
        }


        /**
         * @param      $attribute   Attribute we want to se get/set
         * @param null $value       Value of the attribute we want to set (do not pass along when getting attrribute value
         */
        public function att($attribute, $value = NULL)
        {
            $attribute = strtolower($attribute);

            if (!$this->isValidAttribute($attribute)) {
                throw new Exception($attribute . " is an invalid attribute for class " . get_class($this));
            }

            if (func_num_args() > 1) {
                // we're SETTING the value
                if ($this->attributevalues[$attribute] != $value) {
                    // we have a new value...  Let's validate...
                    $this->isValidValue($attribute, $value); // will throw exception if false
                    $this->attributevalues[$attribute]["value"] = $value;
                    $this->isPersistent = false;
                }

            }
            else {
                // we're GETTING the value
                return $this->attributevalues[$attribute]["value"];
            }
        }

        public function retrieve($objid)
        {
            $sql = "SELECT seqid, objid, unix_timestamp(created) as created, createdby, unix_timestamp(modified) as modified, modifiedby, isActive, IPv4";

            foreach ($this->attributes as $att) {
                if (!in_array($att->name, $this->metaattributes)) {
                    switch ($att->type) {
                        case "datetime":
                        case "date":
                        case "time":
                            // timestamps are in unixtimestamp in php
                            $sql .= ", UNIX_TIMESTAMP(" . $att->name . ") as " . $att->name;
                            break;

                        case "decimal":
                        case "varchar":
                        case "bigint":
                        case "int":
                        case "tinyint":
                        case "text":
                            $sql .= ", " . $att->name;
                            break;

                        default:
                            throw new Exception("Datatype " . $att->type . " not supported for " . get_class($this) . "." . $att->name);
                    }
                }
            }

            $sql .= " FROM " . $this->tableName() . " WHERE isActive = 1 AND objID = " . DB::qstr($objid);
            $rs = DB::Execute($sql);

            if (!$rs->EOF) {
                foreach (array_keys($rs->fields) as $att) {
                    $this->att($att, $rs->fields[$att]);
                }
                $this->isPersistent = true;

                return true;
            }
            else {
                return false;
            }
        }

        public function save() {
            if($this->isPersistent) return true;

            //TODO: add validation

            // Begin database transaction
            DB::BeginTransaction();
            $success = true;
            if($this->att("objid")) {
                // we are in UPDATE modus
                if (!$this->_deactivate()) {
                    $success = false;
                }
            }
            // insert new version of the object...
            $sql = "INSERT INTO " . $this->tableName() . "(objID, isActive, created, createdby, modified, modifiedby, IPv4";
            foreach($this->attributes as $att) {
                if(!in_array($att->name, $this->metaattributes)) {

                        $sql .= ", " . $att->name;


                }
            }
            $sql .= ") VALUES (";

            // objid
            if(!$this->att("objid")) {

                $minimumID = date("Ymd") * 100000 + rand(0, 50000);
                $minimumID = 1;

                $sql .= "ifnull((SELECT newID FROM (SELECT MAX(objID) + 1 as 'newID' FROM " . $this->tableName() . " WHERE objID >= " . $minimumID . ") as x), " . $minimumID . ")";
                $this->att("created", time());
                $this->att("createdby", 1); // TODO: effectieve gebruiker invullen!
                $this->att("modified", $this->att("created"));
                $this->att("modifiedBy", 1); // TODO: effectieve gebruiker invullen!
            } else {
                $sql .= DB::qstr($this->att("objid"));
                $this->att("modified", time());
                $this->att("modifiedBy", 1); // TODO: effectieve gebruiker invullen!
                if (!$this->att("created")) $this->att("created", time());
            }
            // isActive
            $sql .= ", 1";
            // created
            $sql .= ', FROM_UNIXTIME(' . $this->att('created') . ')';

            // createdby
            $sql .= ', ' . DB::qstr($this->att('createdby'));

            // modified
            $sql .= ", NOW()";
            // modifiedBy
            $sql .= ", " . DB::qstr($this->att("modifiedby"));
            // IPvq adres van de modification
            $sql .= ", " . DB::qstr($_SERVER["REMOTE_ADDR"]);

            // loop over the attributes here...
            foreach($this->attributes as $att) {
                if(!in_array($att->name, $this->metaattributes)) {

                        switch ($att->type) {
                            case "datetime":
                            case "date":
                            case "time":
                                if ($this->att($att->name) == null){
                                    $sql .= ", NULL";
                                    break;
                                }
                                else{
                                    // timestamps are in unixtimestamp in php
                                    $sql .= ", FROM_UNIXTIME(" . $this->att($att->name) . ")";
                                    break;
                                }

                            case "varchar":
                            case "bigint":
                            case "int":
                            case "tinyint":
                            case "text":
                            case "decimal":
                                $sql .= ", " . DB::qstr($this->att($att->name));
                                break;

                            default:
                                throw new Exception("Datatype " . $att->type . " not supported for " . get_class($this) . "." . $att->name);
                        }

                }
            }
            $sql .= ")"; // end of values

            try {
                DB::Execute($sql);
            } catch (Exception $e) {
                // catching exception and returning false...
                echo $e->getMessage();
                $success = false;
            }

            if ($success && !$this->att("objID")) {
                // we have a new object id...
                // let's get it from the database
                $sql = "SELECT objId as id FROM " . $this->tableName() . " WHERE seqid = " . DB::Insert_ID();
                $rs = DB::Execute($sql);
                $this->att("objID", $rs->fields["id"]);
            }







            DB::Commit($success);
            return $success;
        }

        protected function _deactivate() {
            $sql = "UPDATE " . $this->tableName() . " SET isActive = 0 WHERE objid = " . $this->att("objid");
            DB::Execute($sql);

            return true;

        }


        protected function isValidAttribute($att)
        {
            return in_array(strtolower($att), array_keys($this->attributes));
        }

        protected function isValidValue($att, $value)
        {
           // echo "<pre>";
           // var_dump($this->attributes[$att]);
            $att = strtolower($att);
            if (!$this->isValidAttribute($att)) {
                throw new Exception($att . " is an invalid attribute for class " . get_class($this));
            }

            if (is_numeric($this->attributes[$att]->max_length) && $this->attributes[$att]->max_length > 0) {
                if (strlen($value) > $this->attributes[$att]->max_length) {
                    throw new Exception("value " . $value . " is longer than maximum length of " . $this->attributes[$att]->max_length . " for " . get_class($this) . "." . $att);
                }
            }

            if($this->attributes[$att]->not_null && is_null($value)) {
                throw new Exception("value NULL is not allowed for " . get_class($this) . "." . $att);
            }

        }


    }
