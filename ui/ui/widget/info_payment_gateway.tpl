<div class="panel panel-success panel-hovered mb20 activities">
    <div class="panel-heading">{Lang::T('Payment Gateway')}: {if isset($_c['payment_gateway']) && $_c['payment_gateway']}{str_replace(',',', ',$_c['payment_gateway'])}{else}None{/if}
    </div>
</div>