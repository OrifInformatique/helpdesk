# Helpdesk site

## What is it?

The helpdesk site is a project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians. All modifications can be made easily via a web interface.

## Tools and softwares

The helpdesk site is developed using :

- [CodeIgniter](https://codeigniter.com/), a PHP framework using MVC architecture.
- HTML
- CSS ([Bootstrap](https://getbootstrap.com/))
- JavaScript
---
- [Visual Studio Code](https://code.visualstudio.com/), text editor, with GitHub and PHP extensions.
- [Laragon](https://laragon.org/), Apache and MySQL server (PHPMyAdmin, PHP 7.4.X).
- [GitHub Desktop](https://desktop.github.com/), for data synchronization. Optional.

# Cloning the project

1. Clone the repository into your server.
2. Open the project in your text editor.
3. Copy-paste the `env` file and edit the `database.default.` fields with correct informations according to your server.
4. Rename the new file `.env`.
5. On your server, create manually a new database.
    - The name has to be the same as defined in .env file, in the `database.default.database` field.
    - Use utf8_general_ci or utf8mb4_general_ci collation.
6. On a new terminal, on project root, execute `php spark migrate --all` to migrate all tables and default values.

# You're all set!
The helpdesk part of the project is listed under `orif\helpdesk`.
