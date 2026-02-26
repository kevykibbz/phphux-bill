{include file="sections/header.tpl"}

{if isset($notify)}
<div class="alert alert-{if $notify_t == 's'}success{else}danger{/if}">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">×</span>
    </button>
    <div>{$notify}</div>
</div>
{/if}

<form class="form-horizontal" method="post" autocomplete="off" role="form" action="">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-primary panel-hovered panel-stacked mb30">
                <div class="panel-heading">Paystack Payment Gateway Settings</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Paystack Secret Key</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="paystack_secret_key"
                                name="paystack_secret_key" value="{if isset($_c['paystack_secret_key'])}{$_c['paystack_secret_key']}{/if}" 
                                placeholder="sk_test_xxxxxxxxxxxxx">
                            <span class="help-block">
                                <a href="https://dashboard.paystack.co/#/settings/developer" target="_blank">
                                    Get your API keys from Paystack Dashboard
                                </a>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Callback URL</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="paystack_callback_url"
                                value="{if isset($_c['paystack_callback_url']) && !empty($_c['paystack_callback_url'])}{$_c['paystack_callback_url']}{else}https://phpnuxbill-webhooks.vercel.app/webhook/paystack{/if}">
                            <span class="help-block">Customer redirects here after payment (default provided)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Webhook URL</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="paystack_webhook_url"
                                value="{if isset($_c['paystack_webhook_url']) && !empty($_c['paystack_webhook_url'])}{$_c['paystack_webhook_url']}{else}https://tevinly.vercel.app/api/webhooks/paystack{/if}">
                            <span class="help-block">Add this webhook URL to your Paystack Dashboard (default provided)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button class="btn btn-primary waves-effect waves-light" name="save" value="save"
                                type="submit">{Lang::T('Save Changes')}</button>
                        </div>
                    </div>

                    <div class="bs-callout bs-callout-info">
                        <h4>Setup Instructions</h4>
                        <ol>
                            <li>Create a Paystack account at <a href="https://paystack.com" target="_blank">https://paystack.com</a></li>
                            <li>Go to Settings → API Keys & Webhooks</li>
                            <li>Copy your <strong>Secret Key</strong> (starts with sk_test_ for test mode)</li>
                            <li>Paste it in the field above and click Save</li>
                            <li>Add the Webhook URL to your Paystack Dashboard</li>
                        </ol>
                        
                        <p><strong>Note:</strong> Default callback and webhook URLs are provided. You can customize them if you have your own handlers.</p>
                        
                        <h4 class="mt-3">Mikrotik Configuration</h4>
                        <p>Add these to your Mikrotik Hotspot Walled Garden:</p>
                        <pre>/ip hotspot walled-garden
add dst-host=*.paystack.com
add dst-host=*.paystack.co</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{include file="sections/footer.tpl"}
