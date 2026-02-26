<?php


class voucher_activation
{
    public function getWidget()
    {
        global $ui;
        $ui->assign('code', $_GET['code'] ?? '');
        return $ui->fetch('widget/customers/voucher_activation.tpl');
    }
}
