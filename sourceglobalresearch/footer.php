<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div id="myclose" class="modal-content">
    <span class="close">&times;</span>
    <?php echo do_shortcode('[gravityform id="4" title="false" description="false"]');?>
    <?php echo do_shortcode('[gravityform id="5" title="false" description="false"]');?>
    <?php echo do_shortcode('[gravityform id="7" title="false" description="false"]');?>
  </div>

</div>

<script>
// Get the modal
var pid= '<?php echo get_the_ID()?>';  

var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn1 = document.getElementById("mypopup");
if(pid == '14247'){
  
var btn2 = document.getElementById("mypopup2");
}
// When the user clicks the button, open the modal 
function showModal() {
  modal.style.display = "block";
}

btn1.onclick = showModal;
if(pid == '14247'){
btn2.onclick = showModal;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal

span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<?php wp_footer(); ?>
</body>
</html>