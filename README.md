# Helpdesk site

A project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians on a terminal. All modifications can be made easily via a web interface.
Wtih this tool, plannings can be generated manually, and, if configured, automatically.

This app uses [CodeIgniter](https://codeigniter.com/), along with HTML, CSS (with [Bootstrap](https://getbootstrap.com/)) and JavaScript.

## Software requirements

To make this app work, you'll need to have both [PHP](https://www.php.net/) and [Composer](https://getcomposer.org/) installed.

Installation processes are not detailled here.

# Cloning the project

1. Clone the repository into your server root.
2. Open the project in your text editor.
3. Copy-paste the `env_dist` file.
4. Rename the new file `.env` and edit it :
    - Make sure that the `CI_ENVIRONMENT` variable is set to the correct value.
        - _`development` when working on the project, `production` when publishing the application._
    - `app.baseURL` must contain the URL to the root of your website.
        - Example : `app.baseURL = 'https://orif.ch/'`
    - Modify the `database.default.` fields with the informations matching your server.
    - Don't forget to uncomment the code you edited.
5. On your server, create manually a new database.
    - Its name has to be the same as defined in the `.env` file, in the `database.default.database` field.
    - Use utf8_general_ci or utf8mb4_general_ci collation.
6. On a new terminal, on project root, execute `php spark migrate --all`. This inserts all tables and default values in the database.

# You're all set!

The helpdesk part of the project is under `orif\helpdesk`.