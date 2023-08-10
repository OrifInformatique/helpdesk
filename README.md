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
3. Connect to the shared database. Information on Teams.

If you're working with a local database, you'll need to import the shared database into it. Versions are available on Teams.

# You're all set!
The project is listed under `orif\helpdesk`.

Go to the local website : http://localhost/helpdesk/public.
