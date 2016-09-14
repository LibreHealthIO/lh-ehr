Welcome to LibreEHR!!!

LibeEHR is a Free and Open Source electronic health records and
medical practice management application. 

We are based on the LibreEHR project

**Plugin System**

Plugins are bits of code that add custom behavior to the LibreEHR application.

To register a plugin:
1- Create a directory in interface/plugins for your plugin. For example:
`interface/plugins/provider_appointments
`
2- Add a start.php file to the directory you just created. Your start.php
file will automatically be called on EVERY REQUEST made to the EHR.

If you create a plugin outside of the interface/plugins directory, you will
have to manually register the plugin by adding the path to the plugin's start.php
file to the root's composer.json file in the plugins array.

**Action Hooks**

LibreEHR comes with a hook and filter system similar to that of Wordpress.

Action hooks are basically markers that trigger events in when certain actions
occur. There are action hooks in certain places in throughout
code where a developer might want to add some custom functionality.

For example, here is an actual action hook. This action hook is executed after an appointment is listed
on a patient's demographics page:

`do_action( 'demographics_after_appointment', $row );
`

This hook gives the developer an oppertunity to render some custom HTML after 
each appointment on the patient's demographics screen.

If a developer wants to add a custom bit of code at this point, they would
register a listener for that action inside a plugin's start.php file like this:

`// In interface/plugins/provider_appointments/start.php

function custom_after_appointment( $row )
{
    $providerName = get_provider_name_by_id( $row['pc_aid'] );
    echo 'Your Dr. for this appointment is' . $providerName;
}
add_action( 'demographics_after_appointment', 'custom_after_appointment' );`

In your start.php file, you call add_action() with the name of the action hook
and the name of the function you would like to call when that action occurs. This
bit of code will add the name of the provider after each appointment.

**Tags/Filters Plugin**

To see an example of a plugin, see interface/tags_filters. This plugin is not in the
interface/plugins directory, so you'll see that there is an entry for it
in the composer.json file in the plugins array.
