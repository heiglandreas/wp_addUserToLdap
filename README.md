# Add user to LDAP

A Wordpress-Plugin to add users on creation to LDAP

This library is the unlucky attempt to add a user to an LDAP-Server after it
has been added to Wordpress. Sadly it doesn't work without patching the
Wordpress-Core as there is no un-hacky way to get the password in an unhashed way

The issue is as follows: The point you can hook into is when the user-data of a
newly created (or an updated) user is stored to the database. That's possible by
using the ```user_register``` or ```profile_update```-hook that wordpress
provides. Sadly the only parameter given there is the users ID in the database.
No Password. So you can only retrieve the prehashed password from the database
that doesn't make sense at all as you would need it unhashed to hash it
differently for usage as password in the LDAP. And as one of the first things
that happens in the [```wp_insert_user```]
(https://codex.wordpress.org/Function_Reference/wp_insert_user)-function is
prehashing the password, so that there's no way of getting to it without having
to patch the WP-core-files (especially the ```user.php```-file) which in turn
could introduce a security-hole in your wordpress-installations. And I'm pretty
sure **you** wouldn't want to be responsible for **that**.

So what else could there be done?

You can always create an app that asks for user-information and adds them
directly to the LDAP and add the link to that URL using the [```register_url```]
(https://codex.wordpress.org/Plugin_API/Filter_Reference/register_url)-filter
like this:

```
add_filter('register_url', function($url){
    return 'http://example.com/link/to/my/registration.php';
});
```

After registration you can then redirect the user back to the WP-instance they
were coming from where they can then login using their credentials they just
entered. 