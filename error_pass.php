


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
    position: absolute;
    top: 23%;
    left: 50%;
    transform: translate(-50%, -50%);


    width:25%;
    margin: 0px auto;
    padding: 0px 0px 0px 20px;
    border: 1px solid #a94442;
    color: #fff;
    background-color: #80383899;
    border-radius: 5px;
    text-align: center;
  }
    /* Style for individual error messages */
    .error p {
        
      margin: 7px; /* Remove margin for paragraph */
    }
    
    .success{
    position: absolute;
    top: 23%;
    left: 50%;
    transform: translate(-50%, -50%);


    width:25%;
    margin: 0px auto;
    padding: 0px 0px 0px 20px;
    border: 1px solid #a94442;
    color: #fff;
    border-radius: 5px;
    text-align: center;
    background-color:  rgb(4 124 2 / 67%);;
    }
</style>








