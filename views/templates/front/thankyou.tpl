{* breadcrumb *}
{capture name=path}{l mod='paymentconfirmation' s='Payment Confirmation'}{/capture}

<p class="alert alert-success">
	{l mod='paymentconfirmation' s='Thank you for your payment, we will process this confirmation as soon as possible.'}</p>
</p>

<ul class="footer_links clearfix">
	<li>
		<a class="btn btn-defaul button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<span><i class="icon-chevron-left"></i> {l s='Back to my account'}</span>
		</a>
	</li>
</ul>
