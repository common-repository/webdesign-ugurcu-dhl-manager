<div id="wu-dhl-manager">
 
    <h1><?php echo __('Webdesign-Ugurcu DHL Manager', 'wu-dhl') ?></h1>
    <p>Status: <?php echo ($data->mode == 0) ? "<span style='color:green'>Sandbox</span>" 
    : "<span style='color:red'> Live</span>" ?></p>
    <form action="" method="post">
        <select name="mode">
            <option <?php echo ($data->mode == 0) ? "selected" : "" ?> value="0">Sandbox</option>
            <option <?php echo ($data->mode == 1) ? "selected" : "" ?> value="1">Live</option>
        </select>
        
        <input type="submit" name="modeChange" value="<?php echo _e('Mode change', 'wu-dhl') ?>" />
    </form>
    <p><?php  _e('API Data for Live Operation', 'wu-dhl') ?></p>
    <form action="" method="post">
        <label>DHL User</label><input type="text" name="dhl_user" value="<?php echo $data->user ?>" /><br>
        <label>Signature</label><input type="text" name="signature" value="<?php echo $data->signature ?>" /><br>
        <label>Ekp</label><input type="text" name="ekp" value="<?php echo $data->ekp ?>" />* <?php echo _e('Your DHL customer number', 'wu-dhl') ?> <br> 
        <input type="hidden" name="page" value="wu_dhl_plugin" />
        <label></label><input type="submit" name="api_send" value="<?php  _e('API Data save', 'wu-dhl') ?>" name="api_data" /><br>
    </form>
    <p><?php echo _e('Sender Label Information', 'wu-dhl') ?></p>
    <form action="" method="post">
        <label><?php  _e('Company Name', 'wu-dhl') ?></label><input type="text" name="c_name" value="<?php echo $data->company_name ?>" /><br>
        <label><?php  _e('Last Name', 'wu-dhl') ?></label><input type="text" name="name" value="<?php echo $data->name ?>" /><br>
        <label><?php  _e('First Name', 'wu-dhl') ?></label><input type="text" name="vorname" value="<?php echo $data->vorname ?>" /><br>
        <label><?php  _e('Contact', 'wu-dhl') ?></label><input type="text" name="contact" value="<?php echo $data->contact_person ?>" />* <?php  _e('The contact of your company', 'wu-dhl') ?><br>
        <label><?php  _e('Street', 'wu-dhl') ?></label><input type="text" name="str" value="<?php echo $data->str ?>" /><br>
        <label><?php  _e('Number.', 'wu-dhl') ?></label><input type="text" name="nr" value="<?php echo $data->nr ?>" /><br>
        <label><?php  _e('ZIP', 'wu-dhl') ?></label><input type="text" name="plz" value="<?php echo $data->plz ?>" /><br>
        <label><?php  _e('City', 'wu-dhl') ?></label><input type="text" name="ort" value="<?php echo $data->ort ?>" /><br>
        <label><?php  echo _('Homepage') ?></label><input type="text" name="hp" value="<?php echo $data->homepage ?>" /><br>
        <label><?php  _e('Phone', 'wu-dhl') ?></label><input type="text" name="tel" value="<?php echo $data->tel ?>" /><br>
        <label><?php  echo _('E-Mail') ?></label><input type="text" name="email" value="<?php echo $data->email ?>" /><br>
        <label></label><input type="submit" name="l_submit" value="<?php echo _e('Label Data save', 'wu-dhl') ?>" name="api_data" /><br>
    </form>
    <br>
    <h2><?php  _e('Here you can create a sample label for testing purposes', 'wu-dhl') ?>.</h2>
    <form action="" method="post">
        <input type="submit" name="testrun" value="<?php  _e('Sample Print', 'wu-dhl') ?>" />
        
    </form>
    
    
</div>
