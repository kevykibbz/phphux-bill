<?php


class unpaid_order
{
    public function getWidget()
    {
        global $ui, $user;
        $unpaid = ORM::for_table('tbl_payment_gateway')
            ->where('username', $user['username'])
            ->where('status', 1)
            ->find_one();

        // check expired payments
        if ($unpaid) {
            try {
                if (!empty($unpaid['expired_date']) && strtotime($unpaid['expired_date']) < time()) {
                    $unpaid->status = 4;
                    $unpaid->save();
                    $unpaid = false;
                }
            } catch (Throwable $e) {
            } catch (Exception $e) {
            }
            try {
                if ($unpaid && !empty($unpaid['created_date'])) {
                    $createdTime = strtotime($unpaid['created_date']);
                    if ($createdTime !== false && ($createdTime + 86400) < time()) { // 86400 = 24 hours
                        $unpaid->status = 4;
                        $unpaid->save();
                        $unpaid = false;
                    }
                }
            } catch (Throwable $e) {
            } catch (Exception $e) {
            }
        }

        $ui->assign('unpaid', $unpaid);
        return $ui->fetch('widget/customers/unpaid_order.tpl');
    }
}
