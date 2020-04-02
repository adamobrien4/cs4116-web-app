<?php     
        function get_user_name_bio($db_conn){

                $r = array();
                $query = "SELECT user_id, firstname, lastname FROM users";
                $res = mysqli_query($db_conn, $query);
                if($res){
                        if(mysqli_num_rows($res) > 0){
                                
                        while($row =  mysqli_fetch_assoc($res)){
                                $r[$row['user_id']] = array('firstname' => $row['firstname'], 'lastname' => $row['lastname'] );
                        }
                        return $r;
                        }
                        else{
                                return false;
                        }
                }
        }        
?>