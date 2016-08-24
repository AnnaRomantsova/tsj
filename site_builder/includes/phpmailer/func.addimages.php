<?

/**
 * @package PHPMailer
 */

/**
 * �������� ��������� ����� IMG �� ��������� � ����������� �������� �������,<br>
 * ����� ��������� �������� ��������� ���������� �������� � ��������� ������<br>
 * �������� ������ ���� ����������� � ������ �������
 * 
 * @param PHPMailer $objPHPMailer ������ � �������������������� $objPHPMailer->Body
 *
 */
function addImages(&$objPHPMailer)
{
  // ��������� ���� ������
  $tagImgs = spliti("<img",$objPHPMailer->Body);
  $countImg = count($tagImgs);
  for ($i = 1; $i < $countImg; $i++)
    {
       $tmp1 = explode(">",$tagImgs[$i]);
       $tmp2 = spliti("src",$tmp1[0]);
       if ($tmp2[1])
       {
            $tmp3 = split("['\"]",$tmp2[1]);
            if ($tmp3[1])
            {
                // ���������� ���� � ��������
                $path = trim($tmp3[1]);

                //echo "<br>path: ".$path;

                // �������� ���������� �������� � �� ���������� �� ���
                $exp = strrchr($path,".");
                $exp = strtolower(substr($exp,1,strlen($exp)-1));
                switch ($exp)
                {       case "jpg": $type = "jpeg"; break;
                        case "jpeg": $type = "jpeg"; break;
                        case "gif": $type = "gif"; break;
                        case "png": $type = "png";  break;
                    default: $type = "";

                };
                if ($type) $type = "image/".$type;

                //echo "<br>type: ".$type;

                // �������� ��� ����� ��������
                $filename = strrchr($path,"/");
                $filename = substr($filename,1,strlen($filename)-1);
                if (!$filename) $filename = $path;

                // echo "<br>filename: ".$filename;

                // ���� ��� �������� - ��������� � ������
                if ( $path && $exp && $filename)
                {   if (strpos($path,'://')<1)
                     { if ($path[0]=='/')
                          $path=$GLOBALS['document_root'].$path;
                       else
                          $path=$GLOBALS['document_root'].'/'.$path;
                     }
                    $objPHPMailer->AddEmbeddedImage($path, "cid_".$i, $filename, "base64", $type);
                    $tmp3[1] = "cid:".$filename;
                }
            }
            $tmp2[1] = implode("\"",$tmp3);
       }
       $tmp1[0] = implode("src",$tmp2);
       $tagImgs[$i] = implode(">",$tmp1);
    }

  // ������������ ����� ����;
  $objPHPMailer->Body = implode("<img",$tagImgs);
}

?>