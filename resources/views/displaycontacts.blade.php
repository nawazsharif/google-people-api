<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Invite Your Gmail Friends to Join us </h4>
</div>

<?php  if(!empty($contacts)){
    echo "I  Have contacts";
    print_r($contacts);

}else{?>
<li><a href="#"  onclick="gLoginBox('google','400','600')">Invite Friends</a></li>
<?php echo 'I dont Have contacts';?>
<?php }?>



$( document ).ready(function() {

});
function gLoginBox(title, w, h) {
url = '<?php echo  $authUrl; ?>';
wLeft = window.screenLeft ? window.screenLeft : window.screenX;
wTop = window.screenTop ? window.screenTop : window.screenY;

var left = wLeft + (window.innerWidth / 2) - (w / 2);
var top = wTop + (window.innerHeight / 2) - (h / 2);
console.log(url);
console.log(title);
return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

</script>
