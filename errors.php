


<?php  
    if (count($errors) > 0) : 
?>

<div class="error">
    <?php foreach ($errors as $error) : ?>
      <p><?php echo $error ?></p>
    <?php endforeach ?>
</div>


<?php  
    endif 
?>


<style>
     
  .error {
    width:auto;
    margin: 0px auto;
    padding: 0px 0px 0px 20px;
    border: 1px solid #a94442;
    color: #fff;
    background-color: #80383899;
    border-radius: 5px;
    text-align: left;
  }
    /* Style for individual error messages */
    .error p {
      margin: 7px; /* Remove margin for paragraph */
    }
    
</style>








