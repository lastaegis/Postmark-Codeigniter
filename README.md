# README #
Postmark library Codeigniter 3.1.0

### What is this library for? ###

* Send email from postmark
* Version 0.0.2

### How do I get set up? ###
Load library in autoload or in spesific controller

```
#!php
$this->load->library('postmark');

```

Setting configuration

```
#!php

$paramPostmark = array(
     'senderSignature'   => '{your signature email}',
     'serverToken'       => '{your server token}',
     'trackingEmail'     => true/false //For tracking purpose
);
$this->postmark->config($paramPostmark);
```

Set value before send

```
#!php
//Email destination
$this->postmark->emailReciver('email destination address');

//Reply to another email
$this->postmark->replyTo('email for reply purpose');

//Email Subject
$this->postmark->emailSubject('email subject');

//Email Body
$this->postmark->emailBody('email body');
```

Set Attachment

```
#!php
//Embed Attachment
//Allowed attachment type:
//1. PDF
//2. JPG
//3. JPEG
//4. PNG
$this->postmark->embedAttachment('attachment location','attachment name','attachment type');
```

Send Email

```
#!php

$this->postmark->sendEmail();
```
