# 🔴 PAYSTACK LIVE MODE - QUICK REFERENCE

## 🌐 Webhook URL
```
https://phpnuxbill-webhooks.vercel.app/webhook/paystack
```

## 🔑 Keys
- **Live Secret:** `[REDACTED_PAYSTACK_KEY]`
- **Live Public:** `[REDACTED_PAYSTACK_PUBLIC_KEY]`

## 💳 Test Card
```
Card: 4084 0840 8408 4081
CVV:  408
PIN:  0000
OTP:  123456
```

## 🔍 Quick Commands

### Test Webhook
```powershell
.\test_webhook.ps1
```

### Monitor Logs
```bash
vercel logs --follow
```

### Check Payments
```bash
mysql -u root phpnuxbill -e "SELECT * FROM tbl_hotspot_payments ORDER BY id DESC LIMIT 5"
```

### Check Vouchers
```bash
mysql -u root phpnuxbill -e "SELECT * FROM tbl_hotspot_vouchers ORDER BY id DESC LIMIT 5"
```

## 🔗 Important Links

- **Paystack Dashboard:** https://dashboard.paystack.com
- **Paystack Transactions:** https://dashboard.paystack.com/transactions
- **Paystack Events:** https://dashboard.paystack.com/events
- **Paystack Settings:** https://dashboard.paystack.com/settings/developer
- **Vercel Dashboard:** https://vercel.com/dashboard
- **PHPNuxBill:** http://localhost/phpnuxbill

## ⚠️ Safety Checklist

Before EVERY test:
- [ ] Amount is ₦100-₦500 (small!)
- [ ] Webhook URL configured in Paystack
- [ ] Logs monitoring active (`vercel logs --follow`)
- [ ] Database backup recent
- [ ] Ready to rollback if needed

## 🆘 Emergency Rollback

Switch back to TEST mode:
```sql
UPDATE tbl_appconfig 
SET value = 'sk_test_b105fc06aba0a5078a21431ca02f51c950228b6b' 
WHERE setting = 'hotspot_paystack_secret_key';
```

## 📞 Support

- Paystack: support@paystack.com
- Vercel: https://vercel.com/support

---

**LIVE MODE ACTIVE - REAL MONEY!**
