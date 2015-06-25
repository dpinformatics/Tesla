<?php

    /**
     * @author  David Heremans
     *
     * The root of all objects.  All business classes shoudl inherit from this class
     */
    Class RootObject
    {
        // lists of predefined column names that are to be treated as metadata
        protected $metaattributes = array("seqid", "objid", "created", "createdby", "modified", "modifiedby", "isactive");
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

        public function save() {
            if($this->isPersistent) return true;

            //TODO: add validation

            // Begin database transaction
            DB::BeginTransaction();
            $success = true;
            if($this->att("objid")) {
                // we are in UPDATE modus
                if(!$this->deactive()) {
                    $success = false;
                }
            }
            // insert new version of the object...
            $sql = "INSERT INTO " . $this->tableName() . "(objid, isActive, created, createdby, modified, modifiedby, ";
            foreach($this->attributes as $att) {
                if(!in_array($att->name, $this->metaattributes)) {
                    $sql .= ", " . $att->name;
                }
            }
            $sql .= ") VALUES (";

            // objid
            if(!$this->att("objid")) {
                // generate new object --> stukje voor max(objid) + 1??
                // TODO: code voor object + 1
            } else {
                $sql .= DB::qstr($this->att("objid"));
            }
            // isActive
            $sql .= ", 1";
            // created
            if(!$this->att("objid")) {
                $sql .= ", NOW()";
            } else {
                $sql .= ', FROM_UNIXTIME(' . $this->att('created') . ')';
            }
            // createdby
            if(!$this->att("objid")) {
                $sql .= ", 1"; //TODO: fill in the actual user id
            } else {
                $sql .= ', ' . DB::qstr($this->att('createdby'));
            }
            // modified
            $sql .= ", NOW()";
            // modifiedBy
            $sql .= ", 1"; //TODO: fill in the actual user id

            // loop over the attributes here...
            foreach($this->attributes as $att) {
                if(!in_array($att->name, $this->metaattributes)) {
                    switch($att->datatype) {
                        //case "datetime":


                        default:
                            throw Exception("Datatype " . $att->datatype . " not supported for " . get_class($this) . "." . $att->name);
                    }
                }
            }

            try {
                DB::Execute($sql);
            } catch (Exception $e) {
                // catching exception and returning false...
                $success = false;
            }




            DB::Commit($success);
            return $success;
        }

        protected function _deactivate() {
            $sql = "UPDATE " . $this->tableName() . " SET isActive = 0";
            DB::Execute($sql);

            return true;

        }


        protected function isValidAttribute($att)
        {
            return in_array(strtolower($att), array_keys($this->attributes));
        }

        protected function isValidValue($att, $value)
        {
            $att = strtolower($att);
            if (!$this->isValidAttribute($att)) {
                throw new Exception($att . " is an invalid attribute for class " . get_class($this));
            }

            if (is_numeric($this->attributes[$att]->max_length)) {
                if (strlen($value) > $this->attributes[$att]->max_length) {
                    throw new Exception("value " . $value . " is longer than maximum length of " . $this->attributes[$att]->max_length . " for " . get_class($this) . "." . $att);
                }
            }

        }


    }