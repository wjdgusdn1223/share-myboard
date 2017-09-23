<?php
    session_start();
    
    include("view.php");
    include("model.php");
    
    function enter_list($isSearch,$mode,$page){
        $list_res = select('list',$isSearch,$mode,$page);
        $page_info = select('page',$isSearch,$mode);
        
        viewer('list',$list_res,ceil($page_info[0][0]/10));
    }
    
    function see($id){
        $con_res = select('see',$id);
        $reply_res = select('reply',$id);
        
        viewer('seeContents', $con_res, $reply_res);
    }
    
    function search($condition){
        $_SESSION['isSearch'] = 'yes';
        $_SESSION['search'] = $_POST['keyword'];
        $_SESSION['mode'] = $condition;
        
        $search_res = select('list','yes',$condition,1);
        $page_info = select('page','yes',$condition);
        
        viewer('list',$search_res,ceil($page_info[0][0]/10));
    }
    
    function login(){
        $islogin = select('login',$_POST['user_id'],$_POST['user_passwd']);
        
        if($islogin[0]['count'] == 1){
            $_SESSION['isLogin'] = 'yes';
            $_SESSION['userId'] = $_POST['user_id'];
            $_SESSION['isSearch'] = 'no';
            $_SESSION['search'] = '';
            $_SESSION['mode'] = 0;
            
            enter_list('no',0,1);
        } else{
            viewer('loginFailed');
        }
    }
    
    if(isset($_POST['user_id']) && isset($_POST['user_passwd'])){
        login();
    } else if(isset($_GET['mode'])){
        switch($_GET['mode']){
            case 'logout' :
                if($_GET['logout'] == 'yes'){
                    session_destroy();
                    echo "<script>location.href = 'controller.php?mode=logout&logout=no';</script>";
                }
                enter_list('no',0,1);
                break;
            case 'list' :
                if(isset($_GET['remember']) && $_GET['remember'] == 'yes'){
                    enter_list($_SESSION['isSearch'],$_SESSION['mode'],$_SESSION['page']);
                }
                else
                    enter_list('no',0,1);
                break;
            case 'paging' :
                enter_list($_SESSION['isSearch'],$_SESSION['mode'],$_GET['page']);
                break;
            case 'see' :
                if(!isset($_COOKIE["id".$_GET['id']]) && ($_SESSION['userId'] != $_COOKIE["id".$_GET['id']])){
                    update('seeCounts',$_GET['id']);
                }
                see($_GET['id']);
                break;
            case 'write' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes'){
                    viewer('write');
                } else{
                    echo "<script>alert('permission denied');</script>";
                    enter_list('no',0,1);
                }
                break;
            case 'insert_post' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && isset($_POST['title'])){
                    insert('post',$_POST['title'],$_POST['contents']);
                    unset($_POST);
                    header("Location:controller.php?mode=list");
                } else{
                    echo "<script>alert('Abnormal access detected');</script>";
                    enter_list('no',0,1);
                }
                break;
            case 'insert_reply' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && (isset($_POST['pid']) || (isset($_POST['change'])))){
                    if(!(isset($_POST['change']))){
                        insert('reply',$_POST['pid'],$_POST['reply_c']);
                        $id = $_POST['id'];
                        
                        unset($_POST);
                        header("Location:controller.php?mode=see&id=$id");
                    } else{
                        $id = $_POST['change'];
                        
                        update('reply',$id,$_POST['change_c']);
                        $pid = $_POST['id_post'];
                        
                        unset($_POST);
                        header("Location:controller.php?mode=see&id=$pid");
                    }
                } else{
                    echo "<script>alert('Abnormal access detected');</script>";
                    enter_list('no',0,1);
                }
                break;
            case 'search' :
                $condition = 0;
                if(isset($_POST['option_t'])){
                    $condition += 4;
                }
                if(isset($_POST['option_c'])){
                    $condition += 2;
                }
                if(isset($_POST['option_w'])){
                    $condition += 1;
                }
                if($condition == 0){
                    $condition += 4;
                }
                search($condition);
                
                break;
            case 'update_post' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && (isset($_GET['id']) || isset($_POST['id']))){
                    if(isset($_GET['id'])){
                        $id = $_GET['id'];
                    } else{
                        $id = $_POST['id'];
                    }
                    
                    $post_info = select('see',$id);
                    
                    if($_SESSION['userId'] != $post_info[0][1]){
                        echo "<script>alert('Abnormal access detected_2');</script>";
                        see($id);
                    } else{
                        viewer('update_post',$post_info);
                        if(isset($_GET['form'])){
                            update('post',$id,$_POST['title'],$_POST['contents']);
                            
                            unset($_POST);
                            header("Location:controller.php?mode=see&id=$id");//겟방식일때 유저아이디랑 작성자 체크
                        }
                    }
                } else{
                    echo "<script>alert('Abnormal access detected');</script>";
                    enter_list('no',0,1);
                }
                break;
            case 'delete_post' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && isset($_GET['id'])){
                    delete($_GET['id']);//겟방식일때 유저아이디랑 작성자 체크
                    $id = $_GET['id'];
                    
                    unset($_POST);
                    header("Location:controller.php?mode=list&remember=yes");
                } else{
                    echo "<script>alert('Abnormal access detected');</script>";
                    enter_list('no',0,1);
                }
                break;
            case 'delete_reply' :
                if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && isset($_GET['id'])){
                    update('reply_del',$_GET['id']);
                    
                    unset($_POST);
                    header("Location:controller.php?mode=see&id=".$_GET['pid']);
                } else{
                    echo "<script>alert('Abnormal access detected');</script>";
                    enter_list('no',0,1);
                }
                break;
        }
    }
?>
