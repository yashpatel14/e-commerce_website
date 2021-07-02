    <?php
    include('vendor/autoload.php');
    require('connection.inc.php');
    require('function.inc.php');

    if(!$_SESSION['ADMIN_LOGIN']){
        if(!isset($_SESSION['USER_ID'])){
            die();
        }
    }

     $order_id=get_safe_value($con,$_GET['id']);
     $coupon_details=mysqli_fetch_assoc(mysqli_query($con,"select coupon_value from `order` where id='$order_id'"));
      $coupon_value=$coupon_details['coupon_value'];
    // $css=file_get_contents('css/bootstrap.min.css');
    $css.=file_get_contents('style1.css');

    $html='<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 2</title>
    
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="logo.png">
      </div>
      <div id="company">
        <h2 class="name">Company Name</h2>
        <div>455 Foggy Heights, AZ 85004, US</div>
        <div>(602) 519-0450</div>
        <div><a href="mailto:company@example.com">company@example.com</a></div>
      </div>
      </div>
    </header>';
    $html.='<main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">INVOICE TO:</div>';
            
              $user_order=mysqli_fetch_assoc(mysqli_query($con,"select `order`.*, users.name,users.email  from `order`,users where users.id=`order`.user_id and `order`.id='$order_id'"));
    
              $uid=$_SESSION['USER_ID'];
              $order_data=mysqli_fetch_assoc(mysqli_query($con,"select distinct(order_detail.id) ,order_detail.*,product.name,product.image,`order`.* from order_detail,product ,`order` where order_detail.order_id='$order_id' and `order`.user_id='$uid' and order_detail.product_id=product.id  and `order`.id='$order_id'"));

          $html.='<h2 class="name">'.$user_order['name'].'</h2>
          <div class="address">'.$order_data['address'].' , '.$order_data['city'].'<br>'.$order_data['pincode'].'</div>
          <div class="email"><a href="#">'.$user_order['email'].'</a></div>
        </div>
        <div id="invoice">
          <h1>INVOICE 3-2-1</h1>
          <div class="date">Date of Invoice: '. $order_data['added_on'].'</div>
          <div class="email"><a href="#">'.$user_order['email'].'</a></div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">DESCRIPTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="qty">QUANTITY</th>
            <th class="total">TOTAL</th>
          </tr>
        </thead>
        <tbody>';
        if(isset($_SESSION['ADMIN_LOGIN'])){
          $res=mysqli_query($con,"select distinct(order_detail.id) ,order_detail.*,product.name,product.image from order_detail,product ,`order` where order_detail.order_id='$order_id' and `order`.user_id='$uid' and order_detail.product_id=product.id");
        }else{
          $uid=$_SESSION['USER_ID'];
                      $res=mysqli_query($con,"select distinct(order_detail.id) ,order_detail.*,product.name,product.image from order_detail,product ,`order` where order_detail.order_id='$order_id' and `order`.user_id='$uid' and order_detail.product_id=product.id");
        }
                     // $uid=$_SESSION['USER_ID'];
                      
                      $total_price=0;
                      if(mysqli_num_rows($res)==0){
                         die();
                      }
                      while($row=mysqli_fetch_assoc($res)){
                      $total_price=$total_price+($row['qty']*$row['price']);
                      $pp=$row['qty']*$row['price'];
          $html.='<tr>
            <td class="no">'.$row['name'].'</td>
            <td class="desc"><h3><img src="'.PRODUCT_IMAGE_SITE_PATH.$row['image'].'" class="p_image"></td>
            <td class="unit">'.$row['qty'].'</td>
            <td class="qty">'.$row['price'].'</td>
            <td class="total">'.$pp.'</td>
          </tr>';
         }
         if($coupon_value!=''){               
      $html.='<tr>
        <td colspan="2"></td>
        <td colspan="2">Coupon Value</td>
        <td>'.$coupon_value.'</td>
        
      </tr>';
    }
    $total_price=$total_price-$coupon_value;
        $html.='</tbody>
        <tfoot>
         
          <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>'.$total_price.'</td>
          </tr>
        </tfoot>
      </table>
      <div id="thanks">Thank you!</div>
      
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>';
    $mpdf=new \Mpdf\Mpdf();
    $mpdf->WriteHTML($css,1);
    $mpdf->WriteHTML($html,2);
    $file=time().'.pdf';
    $mpdf->Output($file,'D');
    ?>
