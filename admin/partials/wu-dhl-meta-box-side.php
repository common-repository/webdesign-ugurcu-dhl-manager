<div id="wu-dhl-admin-panel-order">
    <center>
    <?php if(!$wu_dhl_sendungsnummer) { ?>
    
        <form action="" method="post">
            <input type="hidden" name="oid" value="<?php echo $order->ID ?>"/>
            <input type="submit" name="l_erstellen" value="<?php  _e('Create Label', 'wu-dhl') ?>"/>
        </form><?php }else { ?>
        <p><?php  _e('Tracking number', 'wu-dhl') ?>: <?php echo $wu_dhl_sendungsnummer ?></p>
        
        <p><?php  _e('Show label', 'wu-dhl') ?>:</p> <a href="<?php echo plugins_url() ?>/<?php echo WU_DHL_PLUGIN_NAME ?>/labels/<?php echo $order->ID ?>.pdf" target="_blank" ><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) ?>images/pdf_symbol.png" width="25px"/></a>
    <?php } ?>
    </center>
    
    
</div>