<?php
if (isset($v)) { 
		$quehace = TXT_SEEING;
	} else {
		if ($red==1) {
			$retxt = ' '.TXT_AND_RESIZE;
		}
		$quehace = TXT_DID.$retxt;
	}
	$mensaje = $quehace.' <a href="'.PATH_SCRIPT.DIR_IM.$folhost.$name.'">'.$name.'</a> ('.$tamano_kb.' KB - '.$ancho.'x'.$alto.'px)'.$colita;

?>
<div id="subiste-viendo"><h1><?php echo $mensaje?></h1></div>

<div id="contenido">
	<div id="tools"><div id="fullsize"<? if ($ancho<=900) { echo ' style="display: none; "'; }?>><a href="<?php echo PATH_SCRIPT.DIR_IM.$folhost.$name?>" title="<?php echo $ancho?>x<?php echo $alto?>"><?php echo FULL_SIZE;?></a></div><div id="sharethis"><a id="sharing"><?php echo SHARE;?></a><a id="sharing-close" style="display: none;"><?php echo SHARE;?></a></div></div>
    <div id="imagen"><a href="<?php echo PATH_SCRIPT.DIR_IM.$folhost.$name?>"><img src="<?php echo PATH_SCRIPT.DIR_IM.$folhost.$name?>" alt="" <? if ($ancho>=900) { echo 'width="900" '; } ?>/></a></div>
    
    <div id="share" <? if (isset($v) && $v!=='rec.php') { ?>style="display: none;"<? } ?>>
    
        <div id="mostrar_mas_enlaceview">
            <h2 id="mev"><?php echo SHARE_THUMB_VIEWER;?></h2>
            <div class="ctninput">
                <div class="codex">HTML:</div><div class="inputshare">
                <input tabindex="1" value="&lt;a href=&quot;<?php echo $URLvim?>&quot;&gt;&lt;img src=&quot;<?php echo $URLthm?>&quot; border=&quot;0&quot;&gt;&lt;/a&gt;" onclick="this.focus();this.select();" />
                </div>
            </div>
            <div class="ctninput">
                <div class="codex"><?php echo SHARE_FORUMS;?>:</div><div class="inputshare">
                <input tabindex="2" value="[url=<?php echo $URLvim?>][img]<?php echo $URLthm?>[/img][/url]" onclick="this.focus();this.select();" />
                </div>
            </div>
        </div>
        
        <div id="mostrar_directa">
            <h2 id="md"><?php echo SHARE_DIRECT;?></h2>
            <div class="ctninput">
                <div class="codex"><a href="<?php echo $URLvim?>" target="_blank"><?php echo SHARE_VIEWER;?>:</a></div>
                <div class="inputshare"><input tabindex="3" value="<?php echo $URLvim?>" onclick="this.focus();this.select();" /></div>
            </div>
            <div class="ctninput">
                <div class="codex"><?php echo SHARE_FORUMS;?>:</div>
                <div class="inputshare"><input tabindex="4" value="[img]<?php echo $URLimg?>[/img]" onclick="this.focus();this.select();" /></div>
            </div>
            <div class="ctninput">
                <div class="codex"><a href="<?php echo $URLimg?>" target="_blank">URL:</a></div>
                <div class="inputshare"><input tabindex="5"value="<?php echo $URLimg?>" onclick="this.focus();this.select();" /></div>
            </div>
            <? if (isset($ShortURL) && !empty($ShortURL)) { ?>
            <div class="ctninput">
                <div class="codex"><a href="<?php echo $ShortURL?>" target="_blank"><?php echo $tiny_service;?>:</a></div>
                <div class="inputshare"><input id="tinyurl" tabindex="6" value="<?php echo $ShortURL?>" onclick="this.focus();this.select();" /></div>
            </div>
            <? } ?>
        </div>
        
		<div id="mostrar_social">
        <h2 id="mes"><?php echo SHARE_SOCIAL;?></h2>
        <div class="ctninput-social">
        	<div class="codex"><?php echo SHARE_NETWORKS;?>:</div>
            <a href="http://del.icio.us/post?url=<?php echo $URLshr?>" id="delicious" target="_blank"></a>
			<a href="http://www.facebook.com/share.php?u=<?php echo $URLshr?>" id="facebook" target="_blank"></a>
            <a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?php echo $URLshr?>" id="google" target="_blank"></a>
            <a href="http://www.tumblr.com/share?v=3&amp;u=<?php echo $eu_img?>" id="tumblr" target="_blank"></a>
            <a href="http://twitter.com/home?status=viendo%20<? echo cortar_url($URLshr); ?>" id="twitter" target="_blank"></a>
            <a href="http://vi.sualize.us/post/?popup=1&amp;address=<?php echo $eu_img?>&amp;referenceURL=<?php echo URL_SCRIPT;?>" id="visualizeus" target="_blank"></a>
            <div id="quecosa">
                <span id="d-delicious" style="display: none;">del.icio.us</span>
                <span id="d-facebook" style="display: none;">facebook</span>
                <span id="d-google" style="display: none;">Google Bookmarks</span>
                <span id="d-tumblr" style="display: none;">tumblr</span>
                <span id="d-twitter" style="display: none;"><?php echo SHARE_TWITTER;?></span>
                <span id="d-visualizeus" style="display: none;">vi.sualize.us</span>
            </div>
        </div>
        </div>
    </div>
