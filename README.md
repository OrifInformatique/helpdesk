# Site Helpdesk

## What is it?

The helpdesk site is a project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians. All modifications can be made easily via a web interface.

## Tools and software

The helpdesk site is developed using :

- [CodeIgniter](https://codeigniter.com/), a PHP framework using MVC architecture.
- [Bootstrap](https://getbootstrap.com/), CSS library.
- [Visual Studio Code](https://code.visualstudio.com/), text editor, with GitHub and PHP extensions.
- [Laragon](https://laragon.org/), Apache and MySQL server (PHPMyAdmin, PHP 7.4.X).
- [GitHub Desktop](https://desktop.github.com/), for data synchronization. Optional

# Reproduction of the environment

## Software installation

### Visual Studio Code

1. Download and install VSC (Visual Studio Code).
2. Connect to GitHub via VSC.
3. Download extensions for GitHub.
    - [Remote Repositories](https://marketplace.visualstudio.com/items?itemName=ms-vscode.remote-repositories)
    - [GitHub Pull Requests and Issues](https://marketplace.visualstudio.com/items?itemName=GitHub.vscode-pull-request-github)
    - [PHP Extension Pack](https://marketplace.visualstudio.com/items?itemName=xdebug.php-pack)
    - [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)

### GitHub Desktop

1. Download and install GitHub Desktop.
2. Connect to GitHub via GitHub Desktop.

### Laragon

1. Download and install Laragon.
2. Start the Apache and MySQL servers.

_Projects and web files are located under `laragon\www`._

## Cloning the project

### With GitHub

1. In GitHub Desktop, `File` > `Clone Repository`.
   Shortcut: `Ctrl` + `Shift` + `O`.
2. Choose the URL option.
3. Paste the link leading to the desired GitHub repostiory.
4. Below, enter the destination, in the folder `laragon\www\<your_folder>`.

### Without GitHub

1. In VSC, open a remote directory via the `Explorer` tab.
2. Open the Helpdesk directory.

## Configuration

1. Download the `.env` file from the Teams channel of the Helpdesk site.
2. Place it at the root of the project.
3. On a new terminal, on project root, execute `php spark migrate --all` to migrate all tables and default values.

### For local databases
1. Copy-paste the `env` file and edit it with correct info according to the Apache server used. Rename the new file `.env`.
   _Be sure to define a default database value._
2. On your MySQL server, create a new database (named "helpdesk"). Use utf8_general_ci or utf8mb4_general_ci collation.
3. On a new terminal, on project root, execute `php spark migrate --all` to migrate all tables and default values.


# You're all set!
The project is listed under `orif\helpdesk`.

Go to the local website : http://localhost/helpdesk/public.
