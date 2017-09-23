<?php
session_start();

    function viewer(){
        $args2 = func_get_args();
        
        $params = $args2[0];
        $table_info = $args2[1];
        $page_info = $args2[2];
        
        if( $params === 'list' ): ?>
            <!DOCTYPE html>
            <html lang="UTF-8">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
            </head>
            <style>
                #search_t{
                    margin-top:20px;
                }
               .login_submenu{
                   text-align:right;
               }
               .list_submenu{
                   text-align:right;
               }
               .air{
                   height:50px;
               }
               .container{
                   width:1000px;
                   text-align:center;
               }
               a:link{color:black;}
               a:visited{color:green;}
               a:hover{color:blue;}
               a:active{color:red;}
            </style>
            <body>
                <table class='table'>
                    <tr class='info'>
                        <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 'yes') : ?>
                        <td>
                            <?php echo $_SESSION['userId'] . '님 환영합니다.' ?>
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='회원정보'>
                            <input type='button' class='btn btn-primary' value='로그아웃' onClick="location.href='controller.php?mode=logout&logout=yes';">
                        </td>
                        <?php else : ?>
                        <td>
                            로그인 되어 있지 않습니다.
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='로그인' onClick="location.href='index.html';">
                        </td>
                        <?php endif; ?>
                    </tr>
                </table>
                <div class='air'></div>
                <div class='container'>
                    <table class='table table-striped table-hover'>
                        <tr class="success">
                            <td>#</td>
                            <td>제목</td>
                            <td>글쓴이</td>
                            <td>조회수</td>
                            <td>시간</td>
                        </tr>
                        <?php
                            for($tmp=0; $tmp < count($table_info); $tmp++):
                        ?>
                        <tr>
                            <td><?php echo $table_info[$tmp][0] ?></td>
                            <td><a href='controller.php?mode=see&id=<?php echo $table_info[$tmp][0] ?>'><?php echo $table_info[$tmp][1] ?></a></td>
                            <td><?php echo $table_info[$tmp][2] ?></td>
                            <td><?php echo $table_info[$tmp][3] ?></td>
                            <td><?php echo $table_info[$tmp][4] ?></td>
                        </tr>
                        <?php
                            endfor;
                        ?>
                    </table>
                    <div class='list_submenu'>
                    <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 'yes') : ?>
                        <input type='button' class='btn btn-primary' value='글쓰기' onClick="location.href='controller.php?mode=write';">
                    <?php endif; ?>
                    <input type='button' class='btn btn-primary' value='목록으로' onClick="location.href='controller.php?mode=list';">
                    </div>
                    
                    <form action="controller.php?mode=search" method="post">
                        <table class='table' id="search_t">
                            <tr class="warning">
                                <td>
                                    <label class="checkbox-inline"><input type="checkbox" name='option_t' value="1">제목</label>
                                    <label class="checkbox-inline"><input type="checkbox" name='option_c' value="1">내용</label>
                                    <label class="checkbox-inline"><input type="checkbox" name='option_w' value="1">작성자</label>
                                </td>
                                <td>
                                    <input type="키워드" class="form-control" name='keyword' placeholder="key word" value='<?php echo $_SESSION['search'] ?>'>
                                </td>
                                <td>
                                    <input type='submit' class='btn btn-primary' value='검색'>
                                </td>
                        </table>
                    </form>
                    
                    <ul class="pagination">
                        <?php for($tmp=0; $tmp < $page_info; $tmp++) : ?>
                        <li <?php 
                                if($tmp+1 == $_SESSION['page'])
                                    echo "class='active'";
                            ?>><a href="controller.php?mode=paging&page=<?php echo $tmp+1 ?>">
                        <?php echo $tmp+1 ?></a></li>
                        <?php endfor; ?>
                    </ul>
                    
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php elseif( $params === 'loginFailed' ): ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
                <style>
                    .container{
                        width: 500px;
                        height: 160px;
                    }
                    #loginT td{
                        text-align:center;
                    }
                </style>
            </head>
            <body>
                
                <div class='container'>
                <table class='table table-sm' id='loginT'>
                    <tr>
                        <td colspan='2'>
                            로그인에 실패했습니다
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='button' class='btn btn-primary' value='돌아가기' onClick="location.href='index.html';">
                        </td>
                        <td>
                            <input type='button' class='btn btn-primary' value='목록으로' onClick="location.href='controller.php?mode=list';">
                        </td>
                    </tr>
                </table>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php elseif( $params === 'write' ): ?>
            <!DOCTYPE html>
            <html lang="UTF-8">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
            </head>
            <style>
               .login_submenu{
                   text-align:right;
               }
               .right{
                   text-align:right;
               }
               .center{
                   text-align:center;
               }
               .air{
                   height:50px;
               }
               .container{
                   width:1000px;
                   text-align:center;
               }
               a:link{color:black;}
               a:visited{color:green;}
               a:hover{color:blue;}
               a:active{color:red;}
            </style>
            <body>
                <table class='table'>
                    <tr class='info'>
                        <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 'yes') : ?>
                        <td>
                            <?php echo $_SESSION['userId'] . '님 환영합니다.' ?>
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='회원정보'>
                            <input type='button' class='btn btn-primary' value='로그아웃' onClick="location.href='controller.php?mode=logout&logout=yes';">
                        </td>
                        <?php else : ?>
                        <?php 
                            echo "<script>alert('permission denied');</script>";
                            enter_list('no',0,1); 
                        ?>
                        <?php endif; ?>
                    </tr>
                </table>
                <div class='air'></div>
                <div class='container'>
                    <form action="controller.php?mode=insert_post" method="post">
                    <table class='table'>
                        <thead class='thead-inverse'>
                        <tr>
                            <td class='right' colspan='2'>
                                <input type='button' class='btn btn-primary' value='돌아가기' onClick="location.href='controller.php?mode=list&remember=yes';">
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="success">
                            <td class='center'>
                                <label for="inputTitle" class="control-label">제목</label>
                            </td>
                            <td>
                                <input type="제목" name='title' class="form-control" id='inputTitle' placeholder="title">
                            </td>
                        </tr>
                        </tbody>
                        <tfoot class='table table-bordered'>
                        <tr>
                            <td colspan='2'>
                                <textarea class="form-control" name='contents' rows="15"></textarea>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <input type='submit' class='btn btn-primary' value='확인'>
                    </form>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php elseif( $params === 'seeContents' ): ?> 
            <!DOCTYPE html>
            <html lang="UTF-8">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
            </head>
            <style>
                #comment_ta{
                    width:800px;
                }
               .login_submenu{
                   text-align:right;
               }
               .right{
                   text-align:right;
               }
               .left{
                   text-align:left;
               }
               .air{
                   height:50px;
               }
               .container{
                   width:1000px;
                   text-align:center;
               }
               .btn{
                   margin-left:10px;
               }
               a:link{color:black;}
               a:visited{color:green;}
               a:hover{color:blue;}
               a:active{color:red;}
            </style>
            <body>
                <?php
                    setcookie("id".$table_info[0][5],$_SESSION['userId'],time()+86400);
                ?>
                <script>
                var change_reply_num = 0;
                var tmp_id = 0;
                var tmp_contents = '';
                    function reply_reply(bid,event,close_c){
                        //존재하는 코멘트 창 삭제
                        var old_comment = document.getElementById('comment_tr');
                        old_comment.parentNode.removeChild(old_comment);
                        //코멘트 창 생성
                        var tmp_tr = document.createElement('tr');
                        tmp_tr.setAttribute("class", "warning");
                        tmp_tr.setAttribute("id", "comment_tr");
                        
                        var tmp_ip = document.createElement('input');
                        tmp_ip.setAttribute("type","hidden");
                        tmp_ip.setAttribute("name","pid");
                        tmp_ip.setAttribute("value","" + bid);
                        
                        var tmp_ip3 = document.createElement('input');
                        tmp_ip3.setAttribute("type","hidden");
                        tmp_ip3.setAttribute("name","id");
                        tmp_ip3.setAttribute("value","<?php echo $table_info[0][5] ?>");
                        
                        var tmp_td = document.createElement('td');
                        tmp_td.setAttribute("colspan","20");
                        tmp_td.setAttribute("class","form-inline");
                        
                        var tmp_ta = document.createElement('textarea');
                        tmp_ta.setAttribute("class","form-control");
                        tmp_ta.setAttribute("id","comment_ta");
                        tmp_ta.setAttribute("name","reply_c");
                        tmp_ta.setAttribute("rows","3");
                        
                        var tmp_ip2 = document.createElement('input');
                        tmp_ip2.setAttribute("type","submit");
                        tmp_ip2.setAttribute("class","btn btn-primary");
                        tmp_ip2.setAttribute("value","글쓰기");
                        
                        if(close_c == 0){
                            var tmp_ip4 = document.createElement('input');
                            tmp_ip4.setAttribute("type","button");
                            tmp_ip4.setAttribute("class","btn btn-primary");
                            tmp_ip4.setAttribute("onClick","reply_reply('<?php echo $table_info[0][5] ?>',this,1)");
                            tmp_ip4.setAttribute("value","X");
                        }
                        
                        tmp_td.appendChild(tmp_ta);
                        tmp_td.appendChild(tmp_ip2);
                        if(close_c == 0)
                            tmp_td.appendChild(tmp_ip4);
                        tmp_tr.appendChild(tmp_ip);
                        tmp_tr.appendChild(tmp_ip3);
                        tmp_tr.appendChild(tmp_td);
                        
                        if(close_c == 0){
                            var target = event.parentNode.parentNode.nextSibling.nextSibling;
                            
                            target.after(tmp_tr);
                        } else{
                            var target = event.parentNode.parentNode;
                            var home = document.getElementById('comment_tbody');
                            
                            home.appendChild(tmp_tr);
                        }
                    }
                    function reply_change(id,contents){
                        if(change_reply_num == 1){
                            rollback(1,id,contents);   
                        }else{
                            tmp_id = id;
                            tmp_contents = contents;
                            //원래 있던 코멘트 삭제
                            var comment = document.getElementById(id);
                            comment.removeChild(comment.firstChild);
                            //수정 코멘트란 생성
                            
                            var tmp_ip5 = document.createElement('input');
                            tmp_ip5.setAttribute("type","hidden");
                            tmp_ip5.setAttribute("name","id_post");
                            tmp_ip5.setAttribute("value","<?php echo $table_info[0][5] ?>");
                            
                            var tmp_ip1 = document.createElement('input');
                            tmp_ip1.setAttribute("type","hidden");
                            tmp_ip1.setAttribute("name","change");
                            tmp_ip1.setAttribute("value","" + id);
                            
                            var tmp_ta = document.createElement('textarea');
                            tmp_ta.setAttribute("class","form-control");
                            tmp_ta.setAttribute("id","comment_ta");
                            tmp_ta.setAttribute("name","change_c");
                            tmp_ta.setAttribute("rows","3");
                            
                            var tmp_ip2 = document.createElement('input');
                            tmp_ip2.setAttribute("type","submit");
                            tmp_ip2.setAttribute("class","btn btn-primary");
                            tmp_ip2.setAttribute("value","글쓰기");
                            
                            var tmp_ip3 = document.createElement('input');
                            tmp_ip3.setAttribute("type","button");
                            tmp_ip3.setAttribute("class","btn btn-primary");
                            tmp_ip3.setAttribute("onClick","rollback(0,'','')");
                            tmp_ip3.setAttribute("value","X");
                            
                            var con = document.createTextNode(contents);
                            tmp_ta.appendChild(con);
                            comment.appendChild(tmp_ip5);
                            comment.appendChild(tmp_ip1);
                            comment.appendChild(tmp_ta);
                            comment.appendChild(tmp_ip2);
                            comment.appendChild(tmp_ip3);
                            change_reply_num=1;
                        }
                    }
                    function rollback(delete_old,id,contents){
                        change_reply_num=0;
                        var comment = document.getElementById(tmp_id);
                        while ( comment.hasChildNodes() ) {
                            comment.removeChild( comment.firstChild ); 
                        }
                        var con = document.createTextNode(tmp_contents);
                        comment.appendChild(con);
                        if(delete_old == 1){
                            reply_change(id,contents);
                        }
                    }
                </script>
                
                <table class='table'>
                    <tr class='info'>
                        <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 'yes') : ?>
                        <td>
                            <?php echo $_SESSION['userId'] . '님 환영합니다.' ?>
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='회원정보'>
                            <input type='button' class='btn btn-primary' value='로그아웃' onClick="location.href='controller.php?mode=logout&logout=yes';">
                        </td>
                        <?php else : ?>
                        <td>
                            로그인 되어 있지 않습니다.
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='로그인' onClick="location.href='index.html';">
                        </td>
                        <?php endif; ?>
                    </tr>
                </table>
                <div class='air'></div>
                <div class='container'>
                    <table class='table'>
                        <thead class='thead-inverse'>
                        <tr>
                            <td class='right' colspan='2'>
                                <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes' && $_SESSION['userId'] == $table_info[0][1]): ?>
                                <input type='button' class='btn btn-primary' value='수정' onClick="location.href='controller.php?mode=update_post&id=<?php echo $table_info[0][5] ?>';">
                                <input type='button' class='btn btn-primary' value='삭제' onClick="location.href='controller.php?mode=delete_post&id=<?php echo $table_info[0][5] ?>';">
                                <?php endif; ?>
                                <input type='button' class='btn btn-primary' value='목록으로' onClick="location.href='controller.php?mode=list&remember=yes';">
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="success" id='title_id'>
                            <td class='left'>제목 : <?php echo $table_info[0][0] ?></td>
                            <td class='right'>작성자 : <?php echo $table_info[0][1] ?></td>
                        </tr>
                        <tr class='warning'>
                            <td class='right'>조회수 : <?php echo $table_info[0][3] ?></td>
                            <td class='right'>시간 : <?php echo $table_info[0][4] ?></td>
                        </tr>
                        </tbody>
                        <tfoot class='table table-bordered'>
                        <tr>
                            <td colspan='2' class='left' id='contents_id'>
                                <?php echo nl2br(str_replace(' ', '&nbsp;', htmlentities($table_info[0][2]))); ?>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <form action="controller.php?mode=insert_reply" method="post">
                    <table class='table table-striped'>
                    <?php
                            for($tmp=0; $tmp < count($args2[2]); $tmp++):
                    ?>
                        <tr>
                            <?php for($tmp2=0; $tmp2 < $args2[2][$tmp][4]-1; $tmp2++): ?>
                            <td rowspan='2' colspan="1" class='info'></td>
                            <?php endfor; ?>
                            <td class='left' colspan='<?php echo (21-$args2[2][$tmp][4]) ?>'>
                            <?php echo $args2[2][$tmp][0] ?>
                             | <?php echo $args2[2][$tmp][1] ?>
                             | <?php echo $args2[2][$tmp][3] ?>
                            <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes'): ?>
                                 | <a onClick="reply_reply('<?php echo $args2[2][$tmp][0] ?>',this,0)">답글</a>
                                <?php if($args2[2][$tmp][1] == $_SESSION['userId']): ?>
                                     | <a onClick="reply_change(<?php echo $args2[2][$tmp][0] ?>,'<?php echo $args2[2][$tmp][2] ?>')">수정</a>
                                     | <a onClick="location.href='controller.php?mode=delete_reply&id=<?php echo $args2[2][$tmp][0] ?>&pid=<?php echo $table_info[0][5] ?>'">삭제</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class='left' colspan='<?php echo (21-$args2[2][$tmp][4]) ?>' id='<?php echo $args2[2][$tmp][0] ?>'>
                                <?php echo $args2[2][$tmp][2] ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                    </table>
                    </form>
                    <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 'yes'): ?>
                    <table class='table'>
                        <form action="controller.php?mode=insert_reply" method="post">
                            <tbody id='comment_tbody'>
                                <tr class='warning' id='comment_tr'>
                                    <input type='hidden' name='id' value='<?php echo $table_info[0][5] ?>'>
                                    <input type='hidden' name='pid' value='<?php echo $table_info[0][5] ?>'>
                                    <td class='form-inline'>
                                        <textarea class="form-control" id='comment_ta' name='reply_c' rows="3"></textarea>
                                        <input type='submit' class='btn btn-primary' value='글쓰기'>
                                    </td>
                                </tr>
                            </tbody>
                        </form>
                    </table>
                    <?php endif; ?>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php elseif( $params === 'update_post' ): ?>
            <!DOCTYPE html>
            <html lang="UTF-8">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
            </head>
            <style>
               .login_submenu{
                   text-align:right;
               }
               .right{
                   text-align:right;
               }
               .center{
                   text-align:center;
               }
               .air{
                   height:50px;
               }
               .container{
                   width:1000px;
                   text-align:center;
               }
               a:link{color:black;}
               a:visited{color:green;}
               a:hover{color:blue;}
               a:active{color:red;}
            </style>
            <body>
                <table class='table'>
                    <tr class='info'>
                        <?php if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === 'yes') : ?>
                        <td>
                            <?php echo $_SESSION['userId'] . '님 환영합니다.' ?>
                        </td>
                        <td class='login_submenu'>
                            <input type='button' class='btn btn-primary' value='회원정보'>
                            <input type='button' class='btn btn-primary' value='로그아웃' onClick="location.href='controller.php?mode=logout&logout=yes';">
                        </td>
                        <?php else : ?>
                        <?php 
                            echo "<script>alert('permission denied');</script>";
                            enter_list('no',0,1); 
                        ?>
                        <?php endif; ?>
                    </tr>
                </table>
                <div class='air'></div>
                <div class='container'>
                    <form action="controller.php?mode=update_post&form=yes" method="post">
                        <input type='hidden' name='id' value='<?php echo $args2[1][0][5] ?>'>
                    <table class='table'>
                        <thead class='thead-inverse'>
                        <tr>
                            <td class='right' colspan='2'>
                                <input type='button' class='btn btn-primary' value='돌아가기' onClick="location.href='controller.php?mode=see&id=<?php echo $args2[1][0][5] ?>';">
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="success">
                            <td class='center'>
                                <label for="inputTitle" class="control-label">제목</label>
                            </td>
                            <td>
                                <input type="제목" name='title' class="form-control" id='inputTitle' placeholder="title" value='<?php echo $args2[1][0][0] ?>'>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot class='table table-bordered'>
                        <tr>
                            <td colspan='2'>
                                <textarea class="form-control" name='contents' rows="15"><?php echo $args2[1][0][2] ?></textarea>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <input type='submit' class='btn btn-primary' value='확인'>
                    </form>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php elseif( $params === 'information' ): ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Title</title>
                <link rel="stylesheet" href="css/bootstrap.css">
            </head>
            <style>
               
            </style>
            <body>
                
            <script type="text/javascript" src="js/bootstrap.js"></script>
            </body>
            </html>
        <?php endif; 
    } ?>
