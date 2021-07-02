<?php

require ('top.php');
if (isset($_GET['type']) && $_GET['type']!='') {
   $type=get_safe_value($con,$_GET['type']);

   
   if($type=='delete'){
      
      $id=get_safe_value($con,$_GET['id']);
      
      $delete_sql="delete from users  where id='$id'";
      mysqli_query($con,$delete_sql);
   }
}
$sql="select * from users order by id desc";
$res=mysqli_query($con,$sql);
?>
<div class="content pb-0">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h3 class="">Users Us </h3>
                           
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <table class="table ">
                                 <thead>
                                    <tr>
                                       <th class="serial">#</th>
                                       
                                       
                                       <th>ID</th>
                                       <th>Name</th>
                                       <th>Password</th>
                                       <th>Email</th>
                                       <th>Mobile</th>
                                       
                                       <th>Added_On</th>
                                       <th>action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $i=1;
                                       while ($row=mysqli_fetch_assoc($res)) {
                                          

                                    ?>
                                    <tr>
                                       <td class="serial"><?php echo $i  ?></td>
                                       
                                       <td><?php echo $row['id'] ?></td>
                                       <td><?php echo $row['name'] ?> </td>
                                       <td><?php echo $row['password'] ?> </td>
                                       <td><?php echo $row['email'] ?> </td>
                                       <td><?php echo $row['mobile'] ?> </td>
                                       
                                       <td><?php echo $row['added_on'] ?> </td>
                                       <td>
                                          <?php 
                                             
                                              echo "<span class='badge badge-delete'><a href='?type=delete&id=".$row['id']."'>Delete</a></span>&nbsp;";
                                             
                                          ?>
                                       </td>
                                       
                                       
                                    </tr>
                                    <?php
                                    $i++;
                                 }
                                    ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
		  </div>
         <div class="clearfix"></div>
<?php

require ('footer.php');

?>
