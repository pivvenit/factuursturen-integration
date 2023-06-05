<?php
$wcOrder = wc_get_order($this->post->ID);
$factuursturenId = get_post_meta($this->post->ID, '_fsi_wc_id', true);
if (empty($factuursturenId)) {
    $adminAjaxUrl = admin_url('admin-ajax.php');
    echo '<p>Deze factuur is nog niet verzonden naar Factuursturen.</p>';
    echo '<a id="fsi-create-invoice" href="'.$adminAjaxUrl.'" class="button invoice button-primary" data-order-id="'.$this->post->ID.'" alt="Factuursturen Factuur">Factuur verzenden</a>';
} else {
    $adminAjaxUrl = admin_url('admin-ajax.php?action=fsi_view_invoice&order_id='.$this->post->ID);
    echo '<p>Deze order heeft een Factuursturen factuur met ID:'.$factuursturenId.'</p>';
    echo '<a href="'.$adminAjaxUrl.'" class="button invoice" target="_blank" alt="Factuursturen Factuur">Factuur bekijken</a>';
    $adminAjaxUrl = admin_url('admin-ajax.php');
}
