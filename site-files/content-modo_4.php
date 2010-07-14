<div id="subiste-viendo"><h1><?php echo TXT_TEMP_SAVE;?></h1></div>

	<form enctype="multipart/form-data" action="<?php echo PATH_SCRIPT;?>" method="post">
    <input name="remota" type="hidden" value="<?php echo $URLrdn?>" />
	<div id="redimensionar_cajatitulo" class="denuevo">
        <div id="redimensionar_titulo"><div id="boton_redimensionar"<? if (isset($lang)) { echo ' class="'.$lang.'"'; } ?>><span><a id="ropen"></a></span></div>
        <div id="red_mensaje"><span id="red1"><?php echo RESIZE_DSC;?></span><span id="red2" style="display: none;"></span></div>
        </div>
    </div>
    <div id="redimensionar">
        <div id="redimensionar-borde">
            <div id="cajonred">
                <div id="ancho_deseado"><?php echo RESIZE_WIDTH;?> <span><?php echo RESIZE_PIXELS;?></span></div>
                <input name="resize" id="resize"/>
                <div id="kepp"><?php echo RESIZE_KEEP;?></div>
            </div> 
		    <div id="boton_redo">
                <input type="image" src="site-img/btn_reintentar<? if (isset($lang)) { echo '_'.$lang; } ?>.gif" id="redo" />
                <div id="enviando" style="display: none;"><span id="momentito"><?php echo TXT_REZ_AGAIN;?></span></div>
            </div>
        </div>
    </div>
    </form>
    
    <div id="share">   
     	<h2><?php echo TXT_TEMP_PLACE;?></h2>
        <div class="ctninput"><div class="codex">URL:</div><div class="inputshare"><input id="CTEMP" value="<?php echo $URLrdn;?>" /></div></div>
    </div>
