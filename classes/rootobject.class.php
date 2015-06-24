<?php

    /**
     * @author  David Heremans
     *
     * The root of all objects.  All business classes shoudl inherit from this class
     */
    Class RootObject
    {
        protected $attributes;
        protected $attributevalues;
        protected $isPersistent;


        public function __construct()
        {
            //echo "constructing class";

            $this->attributes = array_change_key_case(DB::MetaColumns($this->dbName()), CASE_LOWER);
            foreach (array_keys($this->attributes) as $att) {
                $this->attributevalues[$att] = array(
                    "value"          => NULL,
                    "persistedvalue" => NULL
                );
            }


        }


        public function dbName()
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
                $this->attributevalues[$attribute]["value"]
            }
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