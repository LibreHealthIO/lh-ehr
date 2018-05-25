Email2Libre

Purpose:  Allow email communications sent to/from a restricted validation email address to be piped
(forwarded) to a PHP script, parsed, then processed into the database as messages.

TODO:
Write prototype use cases:
1.  Upon receipt, messages, alerts or dated reminders will be created for designated users/patients, all active local users table records, whatever.
2.  Allow email message to run a specific program, such as an update script that runs on all sites directories, demo reset for specified sites, etc..
3.  Allow in-application messages to be sent to support.
4.  Use mail() to send receipt confirmation (and execution) to sender.

Write configuration documentation.
Security review.