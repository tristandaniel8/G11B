# Guide de Démarrage et de Contribution au Projet

Bienvenue dans l'équipe ! Ce document a pour but de vous guider dans l'installation de l'environnement de développement et de vous expliquer les règles à suivre pour collaborer efficacement sur le projet.

## Prérequis

Avant de commencer, vous devez avoir installé les deux outils suivants sur votre machine :
1.  **Docker Desktop** : Un outil pour créer et gérer nos conteneurs de développement.
2.  **Git** : Le système de gestion de versions que nous utilisons.

---

### 1. Environnement de Développement avec Docker

Notre projet utilise Docker pour garantir que chaque membre de l'équipe travaille sur un environnement identique et éviter les problèmes de configuration.

#### Comment démarrer le projet ?

1.  **Télécharger et installer Docker Desktop**
    Rendez-vous sur le site officiel de [Docker](https://www.docker.com/products/docker-desktop/) et suivez les instructions d'installation pour votre système d'exploitation (Windows, macOS ou Linux). **Lancez l'application une fois l'installation terminée.**

2.  **Cloner le projet depuis GitHub**
    Ouvrez un terminal (ou Git Bash sur Windows) et clonez le dépôt du projet sur votre machine :
    ```bash
    git clone URL_DU_PROJET
    cd NOM_DU_DOSSIER_DU_PROJET
    ```

3.  **Lancer l'application**
    Toujours dans le terminal, à la racine du projet (là où se trouve le fichier `docker-compose.yml`), lancez la commande suivante :
    ```bash
    docker-compose up --build
    ```
    *   `--build` : Cette option n'est nécessaire que la première fois ou si les fichiers de configuration de Docker (`Dockerfile`, `000-default.conf`) ont été modifiés. Elle construit l'image de notre serveur web.
    *   La commande va télécharger les images nécessaires, construire notre environnement et démarrer le serveur.

4.  **Développer en direct**
    Une fois les conteneurs lancés, vous pouvez accéder au site web en ouvrant votre navigateur à l'adresse : **http://localhost:8080**

    Le dossier `src` de votre machine est synchronisé en temps réel avec le conteneur. Cela signifie que **toute modification que vous effectuez dans les fichiers du dossier `src` sera immédiatement visible** sur le site en rafraîchissant la page. Vous n'avez pas besoin de redémarrer Docker.

5.  **Arrêter l'application**
    Pour arrêter les conteneurs, retournez dans votre terminal et appuyez sur `Ctrl + C`. Pour un arrêt propre qui supprime aussi les conteneurs, vous pouvez utiliser la commande :
    ```bash
    docker-compose down
    ```

---

### 2. Workflow de Développement sur GitHub

Pour travailler de manière organisée, nous utilisons un système de branches. **Personne ne doit travailler directement sur la branche `main`**.

#### Comment développer une nouvelle fonctionnalité ?

1.  **Créer une branche dédiée**
    Avant de commencer à coder, assurez-vous d'être sur la branche `main` et d'avoir la version la plus récente du code (voir section 3). Ensuite, créez une nouvelle branche pour votre fonctionnalité. Le nom de la branche doit être clair et descriptif (en anglais de préférence).
    ```bash
    # Crée une nouvelle branche et bascule dessus
    git checkout -b feature/nom-de-la-fonctionnalite 
    # Exemple : git checkout -b feature/user-authentication
    ```

2.  **Développer votre fonctionnalité**
    Travaillez normalement sur le code dans le dossier `src`. Faites des commits réguliers pour sauvegarder votre progression.
    ```bash
    # Ajoute tous les fichiers modifiés
    git add .

    # Enregistre les modifications avec un message clair
    git commit -m "feat: Ajout de la page de connexion" 
    ```

3.  **Pousser votre branche sur GitHub**
    Lorsque votre fonctionnalité est terminée (ou que vous souhaitez obtenir un avis), poussez votre branche sur le dépôt distant :
    ```bash
    git push origin feature/nom-de-la-fonctionnalite
    ```

4.  **Créer une Pull Request (PR)**
    *   Allez sur la page GitHub du projet.
    *   Un bandeau jaune devrait apparaître, vous proposant de créer une "Pull Request" pour la branche que vous venez de pousser. Cliquez sur le bouton.
    *   Donnez un titre clair à votre Pull Request.
    *   Dans la description, expliquez ce que vous avez fait, les choix que vous avez pris et s'il y a des points particuliers à vérifier.
    *   Dans la section "Reviewers" à droite, **assignez-moi** pour que je reçoive une notification et que je puisse relire votre code.
    *   Cliquez sur "Create Pull Request".

Je me chargerai ensuite de relire votre code, de laisser des commentaires si nécessaire, et de fusionner (`merge`) votre travail dans la branche `main` une fois qu'il sera validé.

---

### 3. Comment Toujours Travailler sur la Dernière Version du Code ?

Il est crucial de toujours partir de la version la plus à jour du projet pour éviter les conflits.

**Avant de créer une nouvelle branche**, ou pour mettre à jour votre branche de travail actuelle, suivez ces étapes :

1.  **Retournez sur la branche principale `main` :**
    ```bash
    git checkout main
    ```

2.  **Récupérez les dernières modifications depuis GitHub :**
    ```bash
    git pull origin main
    ```

3.  **Retournez sur votre branche de fonctionnalité et intégrez les changements de `main` :**
    ```bash
    git checkout feature/nom-de-la-fonctionnalite
    git merge main
    ```

Si des conflits apparaissent lors du `merge`, vous devrez les résoudre avant de pouvoir continuer.

Bon développement à tous !