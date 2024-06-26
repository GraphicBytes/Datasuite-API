<?php
class siteOptions
{

    //var $db;
    var $options_data = array();

    function __construct()
    {

        global $db;

        $options_data = array();
        $options_res = $db->sql("SELECT * FROM ppat_options ORDER BY id ASC");
        while ($options_row = $options_res->fetch_assoc()) {

            $options_data[$options_row['meta_key']] = $options_row['meta_value'];
        }

        $this->options_data = $options_data;
    }



    function get($key)
    {

        $data = $this->options_data;

        if (isset($data[$key])) {
            $result = $data[$key];
        } else {
            $result = "ERROR: OPTIONS KEY PAIR NOT SET";
        }

        return $result;
    }
}
