<?php
session_start();

    function select(){
        $args = func_get_args();
        switch($args[0]){
            case 'login':
                $query = "select count(*) as count from customer where id='$args[1]' and password='$args[2]'";
                break;
            case 'list':
                if($args[1] == 'yes'){
                    switch($args[2]){
                        case 1 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and user_id LIKE '%". $_SESSION['search'] ."%' " . 
                                    "order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 2 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and contents LIKE '%". $_SESSION['search'] ."%' " . 
                                    "order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 3 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and (contents LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " user_id LIKE '%". $_SESSION['search'] . "%') order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 4 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and title LIKE '%". $_SESSION['search'] ."%' " . 
                                    "order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 5 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " user_id LIKE '%". $_SESSION['search'] . "%') order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 6 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " contents LIKE '%". $_SESSION['search'] . "%') order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                        case 7 :
                            $query="SELECT board_id, title, user_id, hits, reg_date " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " contents LIKE '%". $_SESSION['search'] . "%' or user_id LIKE '%". $_SESSION['search'] . "%') " . 
                                    " order by board_id desc Limit ".(($args[3]-1)*10).",10";
                            break;
                    }
                    $_SESSION['page'] = $args[3];
                }else{
                    $query = "select board_id, title, user_id, hits, reg_date " .
                             "from myboard where board_pid=0 order by board_id desc Limit ".(($args[3]-1)*10).",10";
                    
                    $_SESSION['page'] = $args[3];
                    $_SESSION['isSearch'] = 'no';
                    $_SESSION['search'] = '';
                    $_SESSION['mode'] = 0;
                }
                break;
            case 'page':
                if($args[1] == 'yes'){
                    switch($args[2]){
                        case 1 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and user_id LIKE '%". $_SESSION['search'] ."%' ";
                            break;
                        case 2 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and contents LIKE '%". $_SESSION['search'] ."%' ";
                            break;
                        case 3 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and (contents LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " user_id LIKE '%". $_SESSION['search'] . "%') ";
                            break;
                        case 4 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and title LIKE '%". $_SESSION['search'] ."%' ";
                            break;
                        case 5 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " user_id LIKE '%". $_SESSION['search'] . "%')";
                            break;
                        case 6 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " contents LIKE '%". $_SESSION['search'] . "%')";
                            break;
                        case 7 :
                            $query="SELECT count(*) " . 
                                    " FROM myboard WHERE board_pid=0 and (title LIKE '%". $_SESSION['search'] ."%' or " . 
                                    " contents LIKE '%". $_SESSION['search'] . "%' or user_id LIKE '%". $_SESSION['search'] . "%')";
                            break;
                    }
                }else{
                    $query = "select count(*) from myboard where board_pid=0";
                }
                break;
            case 'see':
                $query = "select title, user_id, contents, hits, reg_date, board_id from myboard" . 
                         " where board_id=".$args[1];
                break;
            case 'reply' :
                $result_array = select_reply($args[1],1);
                return $result_array;
                break;
            default:
                echo "<script>alert('select parameter error!')</script>";
        }
        if(!($handler = connectDB())){
            echo "<script>alert('select db_board connect failed')</script>";
        }
        if(!($sel_res = mysqli_query($handler,$query))){
            echo "<script>alert('select query failed ".$query."')</script>";
        }
        if(!(mysqli_close($handler))){
            echo "<script>alert('select db logout failed')</script>";
        }
        
        $sql_num_rows = mysqli_num_rows($sel_res);
        
        for($tmp=0; $tmp < $sql_num_rows; $tmp++){
            $result_array[] = mysqli_fetch_array($sel_res);
        }
        
        return $result_array;
    }
    
    $reply_array;
    function select_reply($id,$level){
        $query = "select board_id, user_id, contents, reg_date from myboard where board_pid=$id" . 
                         " order by board_id";
                         
        if(!($handler = connectDB())){
            echo "<script>alert('select db_board connect failed')</script>";
        }
        
        if(!($sel_res = mysqli_query($handler,$query))){
            echo "<script>alert('select query failed')</script>";
        }
        
        $sql_num_rows = mysqli_num_rows($sel_res);
        if( $sql_num_rows == 0 ){
            return;
        }
        
        for($tmp=0; $tmp < $sql_num_rows; $tmp++){
            $result_array[$tmp] = mysqli_fetch_array($sel_res);
            $result_array[$tmp][4] = $level;
            
            $GLOBALS['reply_array'][] = $result_array[$tmp];
            select_reply($result_array[$tmp][0],$level+1);
        }
        
        if(!(mysqli_close($handler))){
            echo "<script>alert('select db logout failed')</script>";
        }
        
        return $GLOBALS['reply_array'];
    }
    
    function insert(){
        $args = func_get_args();
        switch($args[0]){
            case 'post' :
                $user_id = $_SESSION['userId'];
                $query = "insert into myboard(user_id, title, contents, reg_date) " 
                         . "values('$user_id','$args[1]','$args[2]', now())";
                break;
            case 'reply':
                $user_id = $_SESSION['userId'];
                $query = "insert into myboard(board_pid, user_id, contents, reg_date) " 
                         . "values('$args[1]','$user_id','$args[2]', now())";
                break;
            default:
                echo "<script>alert('insert parameter error!')</script>";
        }
        
        if(!($handler = connectDB())){
            echo "<script>alert('insert db_board connect failed')</script>";
        }
        
        if(!($res = mysqli_query($handler,$query))){
            echo "<script>alert('insert select query failed')</script>";
        }
        
        if(!(mysqli_close($handler))){
            echo "<script>alert('insert db logout failed')</script>";
        }
    }
    
    function update(){
        $args = func_get_args();
        
        switch($args[0]){
            case 'post' :
                $query = "update myboard set title='$args[2]', contents='$args[3]' where board_id=$args[1]";
                break;
            case 'reply' :
                $query = "update myboard set contents='$args[2]' where board_id=$args[1]";
                break;
            case 'reply_del' :
                $query = "update myboard set user_id='---', contents='삭제된 댓글 입니다.' where board_id=$args[1]";
                break;
            case 'seeCounts' :
                $query = "update myboard set hits=hits+1 where board_id=".$args[1];
                break;
        }
        if(!($handler = connectDB())){
            echo "<script>alert('update db_board connect failed')</script>";
        }
        
        if(!($res = mysqli_query($handler,$query))){
            echo "<script>alert('update select query failed')</script>";
        }
        
        if(!(mysqli_close($handler))){
            echo "<script>alert('update db logout failed')</script>";
        }
        
        return;
    }
    
    function delete(){
        $args = func_get_args();
        
        $query = "delete from myboard where board_id='$args[0]'";
        $query2 = "delete from myboard where board_pid='$args[0]'";
        
        if(!($handler = connectDB())){
            echo "<script>alert('update db_board connect failed')</script>";
        }
        
        if(!($res = mysqli_query($handler,$query))){
            echo "<script>alert('update select query failed')</script>";
        }
        if(!($res = mysqli_query($handler,$query2))){
            echo "<script>alert('update select query failed')</script>";
        }
        
        if(!(mysqli_close($handler))){
            echo "<script>alert('update db logout failed')</script>";
        }
        
        return;
    }
    
    function connectDB(){
        $db_con = mysqli_connect('localhost','root','','db_board');
        
        if(!$db_con){
            echo "<script>alert('db connect failed')</script>";
            exit;
        }
        
        mysqli_query($db_con,"set session character_set_connection=utf8;");
        mysqli_query($db_con,"set session character_set_results=utf8;");
        mysqli_query($db_con,"set session character_set_client=utf8;");
        
        return $db_con;
    }
?>