<div class="word-edit-form window shdw">
<div class="delwin close ic shdw">X</div>

<form>
<?php

$langs = get_option( SSMNAME."_languages");
foreach($langs as $k=>$v){
?>
<div class="lan_title"><?=$v?></div>
<textarea name="<?=$v?>" title="<?=$v?>"></textarea>

<?php }?>
<input type="hidden" name="wordid" value="" />
<div class="button save">Save</div>
<div class="button save_close">Save & Close</div>
</form>
</div>