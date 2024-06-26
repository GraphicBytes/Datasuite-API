<?php

if (is_malicious()) {

    $status = 0;
    $result['feedback'] = "PERMISSION DENIED!";
} else {

    $validForm = 0;

    $sql = "SELECT * FROM ppat_forms WHERE id=? AND public_quotas=1 ORDER BY id DESC";
    $res = $db->sql($sql, 'i', $typea);
    while ($row = $res->fetch_assoc()) {

        $validForm = 1;
        

    }

    if ($validForm == 1) {

        $publicFieldNames = array();

        $quotaTotals = array();

        $sql = "SELECT * FROM ppat_form_fields WHERE form_id=? AND public=1 ORDER BY id DESC";
        $res = $db->sql($sql, 'i', $typea);
        while ($row = $res->fetch_assoc()) {

            $publicFieldNames[$row['field_name']] = $row['field_name'];   
        }


        $sql = "SELECT * FROM ppat_submissions WHERE form_id=? ORDER BY id DESC";
        $res = $db->sql($sql, 'i', $typea);
        while ($row = $res->fetch_assoc()) {

            $formData = unserialize($row['form_data']);

            foreach ($publicFieldNames as $key => $value) {

                //$result[$key] = $value;
                //$result["test"] = $publicFieldNames;

                if (isset($formData[$value])) {
                
                    if ($formData[$value] != "" AND $formData[$value] !== null) {



                        if (isset($quotaTotals[$value][$formData[$value]])) {
                            $quotaTotals[$value][$formData[$value]] = $quotaTotals[$value][$formData[$value]] + 1;
                        } else {
                            $quotaTotals[$value][$formData[$value]] = 1;
                        }
                    }

                }
                
            }           
        }


        $result['quota_data'] = $quotaTotals;

    }
}
