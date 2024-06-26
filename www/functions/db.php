<?php

class db {

  var $dbconn;

  function __construct() {
    global $dbhost;
    global $dbuser;
    global $dbpw;
    global $dbname;

    $dbconn = new mysqli($dbhost, $dbuser, $dbpw, $dbname);
    $dbconn ->set_charset("utf8mb4");
    $this->dbconn = $dbconn;
  }


  function sql( $prepare, $types=null , $val=null , $va2=null , $va3=null , $va4=null , $va5=null , $va6=null , $va7=null , $va8=null , $va9=null , $va10=null , $va11=null , $va12=null , $va13=null , $va14=null , $va15=null ){

    $dbconn = $this->dbconn;

    $stmt = $dbconn->prepare($prepare);

    if ($types!=null) {
           if ( $val !== null && $va2===null ){$stmt->bind_param($types, $val);}
      else if ( $va2 !== null && $va3===null  ){$stmt->bind_param($types, $val, $va2);}
      else if ( $va3 !== null && $va4===null  ){$stmt->bind_param($types, $val, $va2, $va3);}
      else if ( $va4 !== null && $va5===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4);}
      else if ( $va5 !== null && $va6===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5);}
      else if ( $va6 !== null && $va7===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6);}
      else if ( $va7 !== null && $va8===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7);}
      else if ( $va8 !== null && $va9===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8);}
      else if ( $va9 !== null && $va10===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9);}
      else if ( $va10 !== null && $va11===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10);}
      else if ( $va11 !== null && $va12===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10, $va11);}
      else if ( $va12 !== null && $va13===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10, $va11, $va12);}
      else if ( $va13 !== null && $va14===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10, $va11, $va12, $va13);}
      else if ( $va14 !== null && $va15===null  ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10, $va11, $va12, $va13, $va14);}
      else if ( $va15 !== null ){$stmt->bind_param($types, $val, $va2, $va3, $va4, $va5, $va6, $va7, $va8, $va9, $va10, $va11, $va12, $va13, $va14, $va15);}
    }
    $stmt->execute();
    $return = $stmt->get_result();
    return $return;
  }

  function clean($data){
      global $dbconn;
      $data =  htmlspecialchars($data);
      return $data;
  }

}
