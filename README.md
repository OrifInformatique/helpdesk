# Helpdesk site

## What is it?

The helpdesk site is a project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians. All modifications can be made easily via a web interface.
Wtih this tool, plannings can be generated manually, and, if configured, automatically.

## Tools and softwares

The helpdesk site is developed using :

- [CodeIgniter](https://codeigniter.com/), a PHP framework using MVC architecture.
- HTML
- CSS ([Bootstrap](https://getbootstrap.com/))
- JavaScript
---
- [Visual Studio Code](https://code.visualstudio.com/), text editor, with GitHub and PHP extensions.
- [Laragon](https://laragon.org/), Apache and MySQL server.
- [GitHub Desktop](https://desktop.github.com/), for data synchronization. Optional.

# Cloning the project

1. Clone the repository into your server.
2. Open the project in your text editor.
3. Copy-paste the `env` file and edit it :
    - Make sure that the `CI_ENVIRONMENT` variable is set to the correct value.
        - _`development` when working on the project, `production` when publishing the application._
    - `app.baseURL` must contain the URL to the root of your website, as a string.
        - For example : `app.baseURL = 'https://orif.ch/'`.
    - Modify the `database.default.` fields with the informations matching your server.
4. Rename this new file you just edited `.env`.
5. On your server, create manually a new database.
    - The name has to be the same as defined in the `.env` file, in the `database.default.database` field.
    - Use utf8_general_ci or utf8mb4_general_ci collation.
6. On a new terminal, on project root, execute `php spark migrate --all` to migrate all tables and default values.

# You're all set!
We recommand to disable default accounts for security purposes.

The helpdesk part of the project is under `orif\helpdesk`.