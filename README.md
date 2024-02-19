# Helpdesk site

## What is it?

The helpdesk site is a project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians. All modifications can be made easily via a web interface.

## Tools and software

The helpdesk site is developed using :

- [CodeIgniter](https://codeigniter.com/), a PHP framework using MVC architecture.
- [Bootstrap](https://getbootstrap.com/), CSS library.
- [Visual Studio Code](https://code.visualstudio.com/), text editor, with GitHub and PHP extensions.
- [Laragon](https://laragon.org/), Apache and MySQL server (PHPMyAdmin, PHP 7.4.X).
- [GitHub Desktop](https://desktop.github.com/), for data synchronization. Optional

# Cloning the project

## With GitHub Desktop

1. In GitHub Desktop, `File` > `Clone Repository`.
   Shortcut: `Ctrl` + `Shift` + `O`.
2. Choose the URL option.
3. Paste the link leading to this repostiory.
4. Below, enter the destination, in the folder `laragon\www\<your_folder>`.

## Without GitHub Desktop

1. In VSC, open a remote directory via the `Explorer` tab.
2. Open the Helpdesk directory.

## Environment
1. Copy-paste the `env` file and edit it with correct informations according to your server. Rename the new file `.env`.
2. On your server, create manually a new database.
    - The name has to be the same as defined in .env file, in the `database.default.database` field.
    - Use utf8_general_ci or utf8mb4_general_ci collation.
3. On a new terminal, on project root, execute `php spark migrate --all` to migrate all tables and default values.

# You're all set!
The helpdesk part of the project is listed under `orif\helpdesk`.
