<?php
    include_once("mysql.php");
    include_once("easySecure.php");

    function checklen($str,$name,$len){
        if(mb_strlen($str,'utf8')>$len){
            $show=$name."长度不能超过".$len."!";
            echo "<script>alert('$show');</script>";
            header("Refresh:0;url=../hiring.html");
            exit();
        }
    }

    $len1=20;
    $len2=15;

    if (empty($_POST)) {	
        echo "<script>alert('您提交的表单数据超过post_max_size!');</script>";
        header("Refresh:0;url=../register.html");
        exit();
    }

    $username =lib_replace_end_tag($_POST['username']) ;
    checklen($username,"用户名",$len1);

    if ($username == null){
        echo "<script>alert('请输入用户名！');</script>";
        header("Refresh:0;url=../register.html");
        exit();
    }

    $password = lib_replace_end_tag($_POST['password']);
    checklen($password,"密码",$len2);

    $verify = lib_replace_end_tag($_POST['verify']);
    checklen($verify,"确认密码",$len2);

    if ($password == null||$verify == null){
        echo "<script>alert('请输入密码！');</script>";
        header("Refresh:0;url=../register.html");
        exit();
    }

    if ($password != $verify) {
        echo "<script>alert('输入的密码与确认密码不相等！');</script>";
        header("Refresh:0;url=../register.html");
        exit();
    }

    $name = lib_replace_end_tag($_POST['name']);
    checklen($name,"真实姓名",$len2);

    if(is_numeric($_POST['phone'])){
        $phone = lib_replace_end_tag($_POST['phone']);   
    }
    else{
        checklen($phone,"联系电话",$len1);
        echo "<script>alert('联系电话必须为数字!');</script>";
        header("Refresh:0;url=../register.html");
	    exit();

    }

    $sex = lib_replace_end_tag($_POST['sex']);
    checklen($sex,"性别",$len2);


    $userNameSQL = "SELECT * FROM user WHERE username = '$username'";
    getConnect();
    $resultSet = mysql_query($userNameSQL);
    if (mysql_num_rows($resultSet) > 0) {
        echo "<script>alert('用户名已被占用，请更换其他用户名');</script>";
        header("Refresh:0;url=../register.html");
        exit();
	
    }

    $registerSQL1 = "INSERT INTO user VALUES('$username', '$password')";
    
    mysql_query($registerSQL1);
    $err1 = mysql_error();
    if($err1){
        echo "<script>alert('关键注册信息错误！');</script>";
        header("Refresh:0;url=../register.html");
	    exit();
    }


    $registerSQL2 = "INSERT INTO userinfo VALUES('$username', '$name', '$phone', '$sex')";
    mysql_query($registerSQL2);
    $err2 = mysql_error();
    if($err2){        
        $deleteSQL = "DELETE FROM user WHERE username = '$username'";
        mysql_query($deleteSQL);
        echo "<script>alert('其它注册信息错误！');</script>";
        header("Refresh:0;url=../register.html");
	    exit();
    }

    $userSQL1 = "SELECT * FROM user WHERE username = '$username'";
    $userSQL2 = "SELECT * FROM userinfo WHERE username = '$username'";

    $userResult1 = mysql_query($userSQL1);
    $userResult2 = mysql_query($userSQL2);

    if (mysql_num_rows($userResult1) > 0 && mysql_num_rows($userResult2) > 0) {
        echo "<script>alert('用户注册成功');</script>";
        header("Refresh:0;url=../index.html");
    } 
    else {
        echo "<script>alert('用户注册失败');</script>";
        header("Refresh:0;url=../register.html");
    }
    closeConnect();
?>