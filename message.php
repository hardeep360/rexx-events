<?php if( '' != $msg ) { ?>
    <div class="<?php echo ( $isError == true ? 'error' : 'success'); ?>">
        <?php echo $msg; ?>
    </div>
<?php } ?>