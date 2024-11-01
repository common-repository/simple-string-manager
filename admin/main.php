<style>
a{ cursor:pointer; }
.hide{ display:none; }
.shdw{-webkit-box-shadow: 0px 0px 5px 0px rgba(50, 50, 50, 0.75); -moz-box-shadow: 0px 0px 5px 0px rgba(50, 50, 50, 0.75); box-shadow:0px 0px 5px 0px rgba(50, 50, 50, 0.75);}
.ic{ background:#CCC; width:20px; height:20px; display:block; text-align:center; line-height:17px; border-radius:50%; }
.close{cursor:pointer; float:right; margin:0px; position:absolute; right:40px; top:-16px; }

.word-listing{ float:left; width:90%; }
.word-listing thead td{ background:#7f8c8d; color:#fff; padding:5px 15px; font-weight:bold; }
.word-listing td{ border-bottom:solid 1px #bdc3c7; }
.edit-form-place{ float:left; background:#0C0; }
.din-form{position:relative;}
.word-listing tbody tr:hover td, .word-listing tbody tr.selected td { background:#bdc3c7;}

.word-edit-form{ position:absolute; background:#ecf0f1; padding:15px; border-top:#CCC solid 4px; opacity:0.95; top:25px; right:45px; }
.word-edit-form textarea{ float:left; width:900px; height:130px; }

.local-result .cell{ display:none; }
.local-result .cell.<?=get_bloginfo('language')?>{display:block;}

.paging{ float:left; width:90%; background:#eaeaea; margin:30px 0px; padding:10px; }
.paging a{ padding:4px 12px; font-size:16px; text-decoration:none; }
.paging a:hover{ background:#72c5e6; color:#FFF;  }
.paging a.current{ background:#0074A2; color:#FFF; }

.searchInput{ width:50%;}

</style>

<?php

$strings = new ssmAdmin();
$strings->cacheStrings();

?>

<div class='word-search'>
	<input type="text" name="word" placeholder="Search word" class="word searchInput" value="<?=chek_val($_POST,'word')?>" />
</div>


<table class='word-listing'>
<thead>
<tr>
	<td>Name</td>
	<td>String</td>
    <td>Action</td>
</tr> 
</thead>

<tbody>
<?php

$mainStrings = \ssm\inc\ssmMain::getStrings('mainfile');


foreach($mainStrings as $k=>$v){

?>


<tr class="<?=$k?>" wordid='<?=$k?>'>
	<td class="mainstring"><?=$v?></td>
	<td class="strings">
        <div class="local-result" id="local-result-<?=$k?>" lan="<?=$strings->getCurrentLan()?>"> <?=$strings->getWord($k)?> </div>

<?php  foreach ($strings->getAllWords($k) as $kk=>$vv) { ?>

	<div class="editableWord translatedstring" title="<?=$kk?>" style="display:none;" lan="<?=$kk?>"><?=$vv?></div>

<?php } ?>

	</td>
	<td><div class="din-form"></div>
        <a class="button doEditWord" toid='edit-form-place' word='<?=$k?>'>Edit</a>
    </td>
</tr>

<?php }?>

</tbody>
</table>


<div class="edit-form-place" id="edit-form-place" style="display:none;"><?=__file_part("file=word_edit&dir=form&plugin=".SSMDIR)?></div>



<script>

	function searchWord(){
		var word = jQuery('.searchInput').val();

		jQuery('.word-listing tbody tr').each(function(){

			if( jQuery('.mainstring',this).html().indexOf(word,0)>=0 || jQuery('.local-result',this).html().indexOf(word,0)>=0 ){

				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}

		});

	}

jQuery(window).load(function(){

	jQuery('body').on('keyup','.searchInput',function(){

		if( jQuery(this).val().length <= 2){
			jQuery('.word-listing tbody tr').show();
			return;
		}

		searchWord();


	});


	jQuery('.doEditWord').on('click', function(){

		var root = jQuery(this).closest('tr');
		var et = jQuery(root).attr('wordid');

		jQuery('.din-form').html( '' );
		jQuery('tr.selected').removeClass('selected');


		jQuery(root).addClass('selected');
		jQuery( '.din-form', root).html( jQuery('.edit-form-place').html() );
		jQuery('.editableWord', root).each(function(){
			jQuery( '.din-form textarea[name='+jQuery(this).attr('title')+']', root).val( jQuery(this).html() );
		});

		jQuery( '.din-form input[name=wordid]', root).val( et );
	});



	jQuery('body').on('click','.word-edit-form .button',function(){

		var root = jQuery(this).closest('.din-form');
		var tr = jQuery(this).closest('tr');


		var formData = new FormData();

		jQuery('textarea', root).each(function(){
			formData.append(jQuery(this).attr('name'), jQuery(this).val() );
			jQuery('.strings [lan='+jQuery(this).attr('name')+']', tr).html( jQuery(this).val() );

		});

		formData.append('wordid', jQuery('input[name=wordid]', root).val() );
		formData.append('action', 'aa');
		formData.append('aa', 'updateForm' );

		jQuery.ajax({
			url: '<?=home_url()?>/wp-admin/admin-ajax.php',
			data: formData,
			processData: false,
			contentType: false,
			type: 'POST',
			success: function(data){

				return true;

			}
		});

		if(jQuery(this).hasClass('save_close')){
			jQuery(root).html('');
			jQuery('tr.selected').removeClass('selected');
		}


	});



	jQuery('body').on('click','.delwin',function(){
		jQuery(this).closest('.din-form').html('');
		jQuery('tr.selected').removeClass('selected');

	});



})


</script>

