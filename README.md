# Site Helpdesk

## C'est quoi ?

Le site helpdesk est un projet de la section infromatique de Pomy, visant à automatiser le processus d'assignation des rôles et d'affichage des techniciens d'astreinte. Toutes les modifications seront facilement réalisables grâce à une interface web.

## Outils et logiciels

Le site helpdesk est développé en utilisant :

- [CodeIgniter](https://codeigniter.com/), framework PHP utilisant l'architechture MVC.
- [Bootstrap](https://getbootstrap.com/), librairie CSS.

- [Visual Studio Code](https://code.visualstudio.com/), éditeur de texte, avec les extensions GitHub et PHP.
- [Laragon](https://laragon.org/), serveur Apache et MySQL (PHPMyAdmin, PHP 7.4.X).
- [GitHub Desktop](https://desktop.github.com/), pour synchroniser les données. Facultatif

# Reproduction de l'environnement

## Installation des logiciels

### Visual Studio Code

1. Téléchargez et installez VSC (Visual Studio Code).
2. Connectez-vous à GitHub via VSC.
3. Téléchargez les extensions pour GitHub.
    - [Remote Repositories](https://marketplace.visualstudio.com/items?itemName=ms-vscode.remote-repositories)
    - [GitHub Pull Requests and Issues](https://marketplace.visualstudio.com/items?itemName=GitHub.vscode-pull-request-github)
    - [PHP Extension Pack](https://marketplace.visualstudio.com/items?itemName=xdebug.php-pack)
    - [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)

### GitHub Desktop

1. Téléchargez et installez GitHub Desktop.
2. Connectez-vous à GitHub via GitHub Desktop.

### Laragon

1. Téléchargez et installez Laragon.
2. Démarrez les serveurs Apache et MySQL.

_Les projets et fichiers web se situent sous `laragon\www`._

## Clonage du projet

### Avec GitHub

1. Dans GitHub Desktop, `File` > `Clone Repository`
   Raccourci : `Ctrl` + `Shift` + `O`
2. Choisissez l'option URL.
3. Coller le lien menant au repostiory GitHub souhaité.
4. En dessous, renseigner la destination, dans le dossier `laragon\www\<dossier>`.

### Sans GitHub

1. Dans VSC, ouvrir un répertoire distant.
2. Ouvrir le répertoire souhaité.

## Configuration

1. Télécharger le fichier `.env` depuis le canal Teams du site Helpdesk.
2. Placez-le à la racine du projet.
3. Connectez-vous à la base de données partagée. Les informations figurent sur Teams.

_Si vous travaillez avec une base de données locale, il vous faudra importer la base de données partagée dessus. Des versions existent sur Teams._

# Vous êtes parés !
Le projet figure sous `orif\helpdesk`.

Rendez-vous sur le site : http://localhost/helpdesk/public.
