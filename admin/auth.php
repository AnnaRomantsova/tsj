<?       

/**
 * ���������. �����������
 * @package BACK
 */
        
    session_start();

    include_once($_SERVER['DOCUMENT_ROOT'].'/setup.php');
     
    if (isset($_POST['password'])&&($_POST['password'] == "$adminpass" )) {
           
            $_SESSION['valid_user'] = "admin";
            
            header("Location: /admin/admin.php");
            exit;
    }
    else {
    

           ?>   <html>
                <head>
                <title></title>
                <LINK rel='stylesheet' type='text/css' href='../_css/back.css'>
                <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=windows-1251'>
                </head>
                <body>
                <br><div align="center"><b style='font-size:12px; color:red;'>��������� ������ �� �����!</b><br><br>
                ���� ����� ��������� ������� �� ���������� �������������� �������� ������� <a href='index.php'>����</a></div>
                <SCRIPT language='JavaScript'>setTimeout('location.href=\"index.php\"',1000);</SCRIPT>
                </body>
                </html>
            <?
            //header("Location: index.php");
            exit;
    }

?>