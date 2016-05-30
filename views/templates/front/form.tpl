{* breadcrumb *}
{capture name=path}{l mod='paymentconfirmation' s='Payment Confirmation'}{/capture}

<div class="box">
	<h1 class="page-subheading">{l mod='paymentconfirmation' s='Payment Confirmation'}</h1>
	<p class="info-title">{l mod='paymentconfirmation' s='Please fill out these form bellow to make a confirmation of your payment.'}</p>
	
	{include file="$tpl_dir./errors.tpl"}
	<p class="required"><sup>*</sup>{l mod='paymentconfirmation' s='Required field'}</p>
	<form action="{$link->getModuleLink('paymentconfirmation','confirmation')|escape:'html':'UTF-8'}" method="post" class="std" id="add_address">
		
		<div class="required form-group" id="email">
			<label for="email">{l mod='paymentconfirmation' s='email'} <sup>*</sup></label>
			<input type="text" id="email" class="is_required validate form-control" data-validate="" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{else}{if isset($customer->email)}{$customer->email|escape:'html':'UTF-8'}{/if}{/if}" placeholder="exp: ryan@kaosbikers.dev" />
		</div>
		
		<div class="required form-group" id="order_id">
			<label for="order_id">{l mod='paymentconfirmation' s='No. Order'} <sup>*</sup></label>
			<input type="text" id="order_id" class="is_required validate form-control" data-validate="" name="order_id" value="{if isset($smarty.post.order_id)}{$smarty.post.order_id}{/if}" placeholder="exp: 123123" />
		</div>
		
		<div class="required form-group" id="rekening">
			<label for="rekening">{l mod='paymentconfirmation' s='Rekening'} <sup>*</sup></label>
			<input type="text" id="rekening" class="is_required validate form-control" data-validate="" name="rekening" value="{if isset($smarty.post.rekening)}{$smarty.post.rekening}{/if}" placeholder="exp: 000012312312" />
		</div>
		
		<div class="required form-group" id="bank">
			<label for="bank">{l mod='paymentconfirmation' s='Bank'} <sup>*</sup></label>
			<input type="text" id="bank" class="is_required validate form-control" data-validate="" name="bank" value="{if isset($smarty.post.bank)}{$smarty.post.bank}{/if}" placeholder="exp: BCA" />
		</div>
		
		<div class="required form-group" id="total">
			<label for="total">{l mod='paymentconfirmation' s='total bayar (dalam rupiah)'} <sup>*</sup></label>
			<input type="text" id="total" class="is_required validate form-control" data-validate="" name="total" value="{if isset($smarty.post.total)}{$smarty.post.total}{/if}" placeholder="exp: 1000000" />
		</div>
		
		<div class="required form-group" id="date">
			<label for="date">{l mod='paymentconfirmation' s='Date'} <sup>*</sup></label>
			{html_select_date all_extra ='class ="is_required"' time=$customDate|default:$currentDate}
		</div>
		
		
		<div class="required form-group" id="catatan">
			<label for="catatan">{l mod='paymentconfirmation' s='Catatan'}</label>
			<textarea id="catatan" class="form-control" data-validate="" name="catatan" value=""></textarea>
		</div>
		
		<p class="submit2">
			<input type="hidden" name="token" value="{$token}" />		
			<button type="submit" name="submitPayment" id="submitPayment" class="btn btn-default button button-medium">
				<span>
					{l mod='paymentconfirmation' s='Submit'}
					<i class="icon-chevron-right right"></i>
				</span>
			</button>
		</p>
	</form>
</div>
<ul class="footer_links clearfix">
	<li>
		<a class="btn btn-defaul button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<span><i class="icon-chevron-left"></i> {l s='Back to my account'}</span>
		</a>
	</li>
</ul>
