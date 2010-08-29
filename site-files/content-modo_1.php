<div id="contenido">

<div id="selector_up">
	<div id="tab_container"<? if (isset($lang)) { echo ' class="'.$lang.'"'; } ?>>
    	<div class="btn_local"><a class="local" id="linklocal" style="display: none;"></a></div>
        <div class="btn_remoto"><a class="remota" id="linkremota"></a></div>
    </div>
<? if ($config['cut_url_enabled'] && $config['cut_url_allow']) { ?><div id="preferencias"<? if (isset($lang)) { echo ' class="'.$lang.'"'; } ?>><a id="pclosed"><span><?php echo TXT_PREFERENCES;?></span></a><a id="popen" style="display:none;"><span><?php echo TXT_PREFERENCES;?></span></a></div><? } ?> 
</div> <!-- selector -->
	
	<form id="form_up" enctype="multipart/form-data" action="<?php echo PATH_SCRIPT;?>" method="post">
	<!-- what tab are we on? -->
	<input type="hidden" value="local" name="tab" id="tabIdentifier" />
	
	<? if ($config['cut_url_enabled'] && $config['cut_url_allow']) { ?>
	<div id="pref-panel" style="display: none;"><div id="cajon-pref"><p id="prefurl"><input name="" type="checkbox" id="cortarurl" value="" <? if(isset($_COOKIE['prefurl'])) { ?>checked="checked"<? } ?> /><label for="cortarurl"> <?php echo TXT_TINYURL;?></label></p><div id="save"><a id="savepref" /><?php echo TXT_CLOSE_PREF;?></a></div></div></div>  
    <? } ?>

    <div id="contenedorupload">
        <div id="subir_local">
            <h2 id="chooselocal"><?php echo TXT_LOCAL;?></h2>
            <div class="inputs"><input name="fileup" type="file" size="60" id="localUP" onclick="javascript:document.getElementById('remotaUP').value = '';"/></div>
        </div>
        <div id="subir_remota" style="display: none;">
            <h2 id="chooseremota"><?php echo TXT_REMOTE;?></h2>
            <div class="inputs"><input name="remota" size="60" id="remotaUP" onclick="javascript:document.getElementById('localUP').value = '';"/></div>
        </div>
    </div>
	    
    <div id="redimensionar">
    	<div id="redimensionar_cajatitulo">
            <div id="redimensionar_titulo">
                <div id="boton_redimensionar"<? if (isset($lang)) { echo ' class="'.$lang.'"'; } ?>><span><a id="rclosed"></a><a id="ropen" style="display: none;"></a></span></div>
                <div id="red_mensaje"><span id="red1"><?php echo RESIZE_DSC;?></span><span id="red2" style="display: none;"></span></div>
            </div>
        </div>        
        <div id="redimensionar-borde" style="display: none;">
        	<div id="cajonred">
                <div id="ancho_deseado"><?php echo RESIZE_WIDTH;?> <span><?php echo RESIZE_PIXELS;?></span></div>
                <input name="resize" id="resize"/>
                <div id="kepp"><?php echo RESIZE_KEEP;?></div>
            </div>
        </div>
    </div>
    
    <div id="boton_subir">
    	<input type="image" src="<?php echo PATH_SCRIPT;?>site-img/btn_subir<? if (isset($lang)) { echo '_'.$lang; } ?>.gif" id="subir"/>
    	<div id="enviando" style="display: none;"><span id="momentito"><?php echo TXT_UPLOADING;?></span></div>
    </div>
    
	</form>

