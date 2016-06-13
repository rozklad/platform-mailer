<?php namespace Sanatorium\Mailer\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CommonTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $faker = Faker::create();

		$data = $this->data();

		foreach( $data as $input )
		{
			unset($input['id']);
			unset($input['redactor-air']);

			\Sanatorium\Mailer\Models\Mailtransaction::firstOrCreate($input);

		}
	}

	/**
	 * @todo organize better
	 * @return array
	 */
	public function data()
	{
		return [
	['id' => '2', 'event' => 'sanatorium.orders.order.placed', 'subject' => 'Objednávka číslo {{ $object->id }}', 'template' => '<strong style="font-weight:bold;font-size:22px;color:#333333">

{{ trans(\'sanatorium/orders::emails.placed.title\') }}

</strong>

<p>

{{ trans(\'sanatorium/orders::emails.general.meta\', [\'order_id\' => $object->id, \'created_at\' => $object->created_at->format(\'j.n.Y H:i\')]) }}

</p>

<!-- text content -->
<p>

Hello,<br>

thank you for your recent purchase. We\'ve listed your order. You will be informed about the order process.<br>

<table cellspacing="0" cellpadding="0" border="0" width="660" style="margin:10px auto 0px;text-align:left;width:600px;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma">

<tbody>

<tr>

<td style="margin:10px auto 0px;text-align:left;width:50%;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma" valign="top">

<h5 style="color:#333333;font-weight:bold;font-size:12px;font-family:Verdana,Arial,Tahoma;">

{{ trans(\'sanatorium/orders::emails.general.delivery_address\') }}

</h5>

@if ( $address = $object->deliveryaddress )

@if ( $address->name )
{{ $address->name }}<br>
@endif

@if ( $address->street )
{{ $address->street }}<br>
@endif

@if ( $address->city )
{{ $address->city }}<br>
@endif

@if ( $address->postcode )
{{ $address->postcode }}<br>
@endif

@if ( $address->country )
{{ $address->country }}<br>
@endif

@if ( $address->ic )
{{ $address->ic }}<br>
@endif

@if ( $address->dic )
{{ $address->dic }}
@endif

@endif

</td>

<td style="margin:10px auto 0px;text-align:left;width:50%;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma" valign="top">

<h5 style="color:#333333;font-weight:bold;font-size:12px;font-family:Verdana,Arial,Tahoma;">

{{ trans(\'sanatorium/orders::emails.general.billing_address\') }}

</h5>

@if ( $address = $object->billingaddress )

@if ( $address->name )
{{ $address->name }}<br>
@endif

@if ( $address->street )
{{ $address->street }}<br>
@endif

@if ( $address->city )
{{ $address->city }}<br>
@endif

@if ( $address->postcode )
{{ $address->postcode }}<br>
@endif

@if ( $address->country )
{{ $address->country }}<br>
@endif

@if ( $address->ic )
{{ $address->ic }}<br>
@endif

@if ( $address->dic )
{{ $address->dic }}
@endif

@endif

</td>

</tr>

</tbody>

</table>

</p>

<br>

<strong style="font-weight:bold;font-size:18px;color:#333333">

{{ trans(\'sanatorium/orders::emails.general.your_order\') }}

</strong>

{{ Cart::unserialize($object->cart) }}

<!-- Order recap -->
<table cellspacing="0" cellpadding="0" border="0" width="660" style="margin:10px auto 0px;text-align:left;width:600px;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma">

<tbody>

@foreach( Cart::items() as $item )

<?php $product = Product::find($item->get(\'id\')); ?>

<!-- Single product -->
<tr valign="top">

<td class="text-center" style="border-top:1px solid #cccccc;padding:10px 0;width:80px;">

@if ( $product->has_cover_image )

<a href="{{ $product->url }}" target="_blank">

<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60"  height="60">

</a>

@else

{{ $item->get(\'id\') }}

@endif

</td>

<td style="border-top:1px solid #cccccc;padding:10px 0">

<a href="{{ $product->url }}" target="_blank">

{{ $product->product_title }}

</a>

@if ( $item->quantity() > 1 )

<br>
{{ $item->quantity() }}

@endif

</td>

<td align="right" style="border-top:1px solid #cccccc;padding:10px 0">

<strong>{{ $product->getPrice(\'vat\', $item->quantity()) }}</strong>

</td>

</tr>
<!-- /Single product -->

@endforeach

<!-- Delivery -->
@if ( is_object($object->deliverytype) )

<tr>

<td colspan="2" style="border-top:1px solid #cccccc;padding:10px 0">

{{ trans(\'sanatorium/orders::emails.general.delivery\') }}

</td>

<td align="right" style="border-top:1px solid #cccccc;padding:10px 0">

{{ $object->deliverytype->price_vat }}

</td>

</tr>

@endif
<!-- /Delivery -->

<tr>

<td colspan="2" style="border-top:1px solid #cccccc;border-bottom:2px solid #cccccc;padding:20px 0;font-size:18px">

{{ trans(\'sanatorium/orders::emails.general.total\') }}

</td>

<td align="right" style="border-top:1px solid #cccccc;border-bottom:2px solid #cccccc;padding:20px 0;font-size:18px">

<strong>{{ $object->price_vat }}</strong>

</td>
</tr>

<tr>

<td style="padding:10px 0" colspan="3">


{{ trans(\'sanatorium/orders::emails.general.your_order\') }} <strong>{{ trans(\'sanatorium/orders::emails.general.payment_type\', [\'payment_title\' => $object->paymenttype->payment_title]) }}</strong>.

</td>

</tr>

</tbody>

</table>

{{ trans(\'sanatorium/orders::emails.general.thank_you_for_order\') }}', 'receivers' => '{{ $object->order_email }}', 'created_at' => '2016-01-03 16:40:14', 'updated_at' => '2016-01-03 16:40:14', 'redactor-air' => ''],
	['id' => '3', 'event' => 'platform.page.updated', 'subject' => 'Byla upravena stránka {{ $object->name }}', 'template' => 'Na webu {{ config(\'platform.app.title\') }} byla upravena stránka {{ $object->name }}', 'receivers' => 'jan.rozklad@gmail.com', 'created_at' => '2016-01-03 16:41:51', 'updated_at' => '2016-01-03 16:50:54', 'redactor-air' => ''],
	['id' => '4', 'event' => 'platform.user.reminder.trigger', 'subject' => 'Vaše nové heslo', 'template' => '<p>Hello {{{ $object->user->first_name }}},</p>

<p>You are receiving this notification because you have (or someone pretending to be you has) requested a password reset on your account on "{{{ config(\'platform.app.title\') }}}". If you did not request this notification then please ignore it, if you keep receiving it please contact the administrator.</p>

<p>Please visit the following link in order to reset your password:</p>

<p><a href="{{ $object->reminderLink  }}">{{ $object->reminderLink }}</a></p>

<p>{{{ config(\'platform.app.title\') }}}</p>

', 'receivers' => '{{ $object->user->email }}', 'created_at' => '2016-01-03 16:42:31', 'updated_at' => '2016-01-03 16:42:31', 'redactor-air' => ''],
	['id' => '5', 'event' => 'platform.user.registered.email', 'subject' => 'Vítejte na {{ config(\'platform.app.title\') }}', 'template' => '<p>Hello {{{ $object->user->first_name }}},</p>

<p>Please keep this e-mail for your records.</p>

<p>Your account information is as follows:</p>

<p>----------------------------</p>

<p>Email: {{{ $object->user->email }}}</p>

<p>Website URL: {{ url(\'/\') }}</p>

<p>----------------------------</p>

<p>Please visit the following link in order to activate your account:</p>

<p><a href="{{ $object->activationLink }}">{{ $object->activationLink }}</a></p>

<p>Your password has been securely stored in our database and cannot be retrieved. In the event that it is forgotten, you will be able to reset it using the email address associated with your account.</p>

<p>Thank you for registering.</p>

<p>{{{ config(\'platform.app.title\') }}}</p>
', 'receivers' => '{{ $object->user->email }}', 'created_at' => '2016-01-03 16:43:09', 'updated_at' => '2016-01-03 16:43:09', 'redactor-air' => ''],
	['id' => '6', 'event' => 'platform.user.registered.admin.touser', 'subject' => 'Vítejte na {{ config(\'platform.app.title\') }}', 'template' => '<p>Hello {{{ $object->user->first_name }}},</p>

<p>Please keep this e-mail for your records.</p>

<p>Your account information is as follows:</p>

<p>----------------------------</p>

<p>Email: {{{ $object->user->email }}}</p>

<p>Website URL: {{ url(\'/\') }}</p>

<p>----------------------------</p>

<p>Your account is currently inactive and will need to be approved by an administrator before you can log in. Another email will be sent when this has occurred.</p>

<p>Your password has been securely stored in our database and cannot be retrieved. In the event that it is forgotten, you will be able to reset it using the email address associated with your account.</p>

<p>Thank you for registering.</p>

<p>{{{ config(\'platform.app.title\') }}}</p>', 'receivers' => '{{ $object->user->email }}', 'created_at' => '2016-01-03 16:44:22', 'updated_at' => '2016-01-03 16:44:22', 'redactor-air' => ''],
	['id' => '7', 'event' => 'platform.user.registered.admin.toadmin', 'subject' => 'Nový uživatel byl zaregistrován na {{ config(\'platform.app.title\') }}', 'template' => '<p>Hello,</p>

<p>The account owned by "{{{ $object->user->first_name }}}" has been deactivated or newly created, you should check the details of this user (if required) and handle it appropriately.</p>

<p>Use this link to view the user\'s profile:</p>

<p>----------------------------</p>

<p>Name: {{{ $object->user->first_name }}} {{{ $object->user->last_name }}}</p>

<p>Email: {{{ $object->user->email }}}</p>

<p>----------------------------</p>

<p>Use this link to activate the account:</p>

<p><a href="{{ $object->activationLink }}">{{ $object->activationLink }}</a></p>

<p>{{{ config(\'platform.app.title\') }}}</p>', 'receivers' => '{{ config(\'mail.from.address\') }}
jan.rozklad@gmail.com', 'created_at' => '2016-01-03 16:45:04', 'updated_at' => '2016-01-03 16:45:04', 'redactor-air' => ''],
	['id' => '8', 'event' => 'platform.user.registered.default', 'subject' => 'Vítejte na {{ config(\'platform.app.title\') }}', 'template' => '<p>Hello {{{ $object->user->first_name }}},</p>

<p>Please keep this e-mail for your records.</p>

<p>Your account information is as follows:</p>

<p>----------------------------</p>

<p>Email: {{{ $object->user->email }}}</p>

<p>Website URL: {{ url(\'/\') }}</p>

<p>----------------------------</p>

<p>Your password has been securely stored in our database and cannot be retrieved. In the event that it is forgotten, you will be able to reset it using the email address associated with your account.</p>

<p>Thank you for registering.</p>

<p>{{{ config(\'platform.app.title\') }}}</p>

', 'receivers' => '{{ $object->user->email }}', 'created_at' => '2016-01-03 16:45:46', 'updated_at' => '2016-01-03 16:45:46', 'redactor-air' => ''],
	['id' => '9', 'event' => 'sanatorium.orders.order.placed', 'subject' => 'Nová objednávka na {{ config(\'platform.app.title\') }}', 'template' => '<strong style="font-weight:bold;font-size:22px;color:#333333">

{{ trans(\'sanatorium/orders::emails.placed.title\') }}

</strong>

<p>

{{ trans(\'sanatorium/orders::emails.general.meta\', [\'order_id\' => $object->id, \'created_at\' => $object->created_at->format(\'j.n.Y H:i\')]) }}

</p>

<!-- text content -->
<p>

Hello admin!<br>

new order was made on {{ config(\'platform.app.title\') }}, see below:<br>

<table cellspacing="0" cellpadding="0" border="0" width="660" style="margin:10px auto 0px;text-align:left;width:600px;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma">

<tbody>

<tr>

<td style="margin:10px auto 0px;text-align:left;width:50%;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma" valign="top">

<h5 style="color:#333333;font-weight:bold;font-size:12px;font-family:Verdana,Arial,Tahoma;">

{{ trans(\'sanatorium/orders::emails.general.delivery_address\') }}

</h5>

@if ( $address = $object->deliveryaddress )

@if ( $address->name )
{{ $address->name }}<br>
@endif

@if ( $address->street )
{{ $address->street }}<br>
@endif

@if ( $address->city )
{{ $address->city }}<br>
@endif

@if ( $address->postcode )
{{ $address->postcode }}<br>
@endif

@if ( $address->country )
{{ $address->country }}<br>
@endif

@if ( $address->ic )
{{ $address->ic }}<br>
@endif

@if ( $address->dic )
{{ $address->dic }}
@endif

@endif

</td>

<td style="margin:10px auto 0px;text-align:left;width:50%;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma" valign="top">

<h5 style="color:#333333;font-weight:bold;font-size:12px;font-family:Verdana,Arial,Tahoma;">

{{ trans(\'sanatorium/orders::emails.general.billing_address\') }}

</h5>

@if ( $address = $object->billingaddress )

@if ( $address->name )
{{ $address->name }}<br>
@endif

@if ( $address->street )
{{ $address->street }}<br>
@endif

@if ( $address->city )
{{ $address->city }}<br>
@endif

@if ( $address->postcode )
{{ $address->postcode }}<br>
@endif

@if ( $address->country )
{{ $address->country }}<br>
@endif

@if ( $address->ic )
{{ $address->ic }}<br>
@endif

@if ( $address->dic )
{{ $address->dic }}
@endif

@endif

</td>

</tr>

</tbody>

</table>

</p>

<br>

<strong style="font-weight:bold;font-size:18px;color:#333333">

{{ trans(\'sanatorium/orders::emails.general.order\') }}

</strong>

{{ Cart::unserialize($object->cart) }}

<!-- Order recap -->
<table cellspacing="0" cellpadding="0" border="0" width="660" style="margin:10px auto 0px;text-align:left;width:600px;background:#ffffff;color:#333333;font-weight:normal;font-size:12px;font-family:Verdana,Arial,Tahoma">

<tbody>

@foreach( Cart::items() as $item )

<?php $product = Product::find($item->get(\'id\')); ?>

<!-- Single product -->
<tr valign="top">

<td class="text-center" style="border-top:1px solid #cccccc;padding:10px 0;width:80px;">

@if ( $product->has_cover_image )

<a href="{{ $product->url }}" target="_blank">

<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60"  height="60">

</a>

@else

{{ $item->get(\'id\') }}

@endif

</td>

<td style="border-top:1px solid #cccccc;padding:10px 0">

<a href="{{ $product->url }}" target="_blank">

{{ $product->product_title }}

</a>

@if ( $item->quantity() > 1 )

<br>
{{ $item->quantity() }}

@endif

</td>

<td align="right" style="border-top:1px solid #cccccc;padding:10px 0">

<strong>{{ $product->getPrice(\'vat\', $item->quantity()) }}</strong>

</td>

</tr>
<!-- /Single product -->

@endforeach

<!-- Delivery -->
@if ( is_object($object->deliverytype) )

<tr>

<td colspan="2" style="border-top:1px solid #cccccc;padding:10px 0">

{{ trans(\'sanatorium/orders::emails.general.delivery\') }}

</td>

<td align="right" style="border-top:1px solid #cccccc;padding:10px 0">

{{ $object->deliverytype->price_vat }}

</td>

</tr>

@endif
<!-- /Delivery -->

<tr>

<td colspan="2" style="border-top:1px solid #cccccc;border-bottom:2px solid #cccccc;padding:20px 0;font-size:18px">

{{ trans(\'sanatorium/orders::emails.general.total\') }}

</td>

<td align="right" style="border-top:1px solid #cccccc;border-bottom:2px solid #cccccc;padding:20px 0;font-size:18px">

<strong>{{ $object->price_vat }}</strong>

</td>
</tr>

<tr>

<td style="padding:10px 0" colspan="3">


{{ trans(\'sanatorium/orders::emails.general.order\') }} <strong>{{ trans(\'sanatorium/orders::emails.general.payment_type\', [\'payment_title\' => $object->paymenttype->payment_title]) }}</strong>.

</td>

</tr>

</tbody>

</table>', 'receivers' => '{{ config(\'mail.from.address\') }}
jan.rozklad@gmail.com', 'created_at' => '2016-01-03 16:46:36', 'updated_at' => '2016-01-03 16:46:36', 'redactor-air' => ''],
	['id' => '10', 'event' => 'sanatorium.orders.status.changed.2.POS', 'subject' => 'Vaše objednávka #{{ $object->id }} je {{ $object->status->name }}', 'template' => '<strong style="font-weight:bold;font-size:22px;color:#333333">

Your order #{{ $object->id }} is {{ $object->status->name }}

</strong>
<br>
<!-- text content -->
<p>

Hello,<br>

thank you for your recent purchase. Your order is now <strong>{{ $object->status->name }}</strong>.<br>

</p>

<p>

It will arrive within 5-14 days through regular postal service.

</p>', 'receivers' => '{{ $object->order_email }}', 'created_at' => '2016-01-03 16:47:21', 'updated_at' => '2016-01-03 16:47:21', 'redactor-air' => ''],
	['id' => '11', 'event' => 'sanatorium.orders.status.changed.3.POS', 'subject' => 'Vaše objednávka #{{ $object->id }} je {{ $object->status->name }}', 'template' => '<strong style="font-weight:bold;font-size:22px;color:#333333">

Your order #{{ $object->id }} is {{ $object->status->name }}

</strong>
<br>
<!-- text content -->
<p>

Hello,<br>

Your order is now <strong>{{ $object->status->name }}</strong>.<br>

</p>
<p>

Let us know how do you like the product.

</p>
<p>

<strong>Thank you for your purchase.</strong>

</p>', 'receivers' => '{{ $object->order_email }}', 'created_at' => '2016-01-03 16:48:03', 'updated_at' => '2016-01-03 16:48:03', 'redactor-air' => ''],
	['id' => '12', 'event' => 'email.contact.us', 'subject' => '{{ $object[\'field_subject\'] }}', 'template' => '<p>
New e-mail came through Contact us form
</p>

<table border="0" style="border:0;">
<tr>

<td style="font-weight:normal;font-size:12px;color:#333333;font-family:Verdana,Arial,Tahoma">
<strong style="font-weight:bold;font-size:12px;color:#333333;font-family:Verdana,Arial,Tahoma">{{ $object[\'field_name\'] }}</strong> wrote
</td>

</tr>

<tr>

<td height="20">
 
</td>

</tr>

<tr>

<td style="font-weight:normal;font-size:12px;color:#333333;font-family:Verdana,Arial,Tahoma">

{{ $object[\'field_message\'] }}

</td>

</tr>

@if ( isset($object[\'field_orderid\']) )

@if ( $object[\'field_orderid\'] )

<tr>

<td height="20">
 
</td>

</tr>

<tr>

<td style="font-weight:normal;font-size:12px;color:#333333;font-family:Verdana,Arial,Tahoma">

Order no.: {{ $object[\'field_orderid\'] }}

</td>

</tr>

@endif

@endif

</table>', 'receivers' => 'jan.rozklad@gmail.com', 'created_at' => '2016-01-03 16:48:43', 'updated_at' => '2016-01-03 16:48:43', 'redactor-air' => ''],
	['id' => '13', 'event' => 'email.contact.us.confirm', 'subject' => 'Zpráva "{{ $object[\'field_subject\'] }}" byla úspěšně odeslána', 'template' => '<p>
This is automatic confirmation. We\'ve received your message and we will reply as soon as possible.
</p>
', 'receivers' => '{{ $object[\'field_email\'] }}', 'created_at' => '2016-01-03 16:49:41', 'updated_at' => '2016-01-03 16:49:41', 'redactor-air' => ''],
	['id' => '14', 'event' => 'product.form.submitted', 'subject' => 'Otázka k produktu', 'template' => 'Přišla otázka k produktu.

Tady jsou údaje z formuláře

@foreach( $object as $key => $value )

{{ $key }} => {{ $value }} <br>

@endforeach

', 'receivers' => 'michal.polak@fishcat.cz
{{ config(\'mail.from.address\') }}
jan.rozklad@gmail.com', 'created_at' => '2016-01-11 23:25:28', 'updated_at' => '2016-01-11 23:25:28', 'redactor-air' => ''],
];
	}

}
