# sanatorium/mailer

Mailing extension for Cartalyst Platform

## Installation

### Composer

Add repository to your composer.json

    "repositories": [
      {
        "type": "composer",
        "url": "http://repo.sanatorium.ninja"
      }
    ]

Download the package

    composer require sanatorium/mailer

### Download

Download repository and copy it's contents to /extensions/sanatorium/mailer

## Documentation

Mailer is built to dispatch transactional emails when Event is triggered.

### Getting started

Let's imagine you want to send emails to customer and admin when order is placed.

#### Preparing template

After installation, open **Mailtransactions** menu and choose **+** icon to **Create new template**

The template for notification to admin would looks something like this:

    event: order.placed
    subject: New order was placed
    template: Hello admin!<br>New order was placed on your {{ config('platform.app.title') }} site<br>
    receivers: {{ config('platform.mail.from.address') }}

Let's assume our $order object contains customer's email in $order->customer_email, therefore we create new template with notification for customer like this:

    event: order.placed
    subject: Your order on {{ config('platform.app.title') }}
    template: Hello!<br>Thanks for your order!<br><br>Best regards,<br>{{ config('platform.app.title') }} site<br>
    receivers: {{ $order->customer_email }}

And then we trigger the event and pass data anywhere in the application like this:

    Event::fire('order.placed', ['order' => $order])

## Changelog

1.1.0 - Added simple documentation

## Support

Support not available.