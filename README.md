# Plateforme IoT pour Bâtiments Intelligents

![Capture d'écran de la page d'accueil](demo/accueil.png)

Bienvenue sur notre projet de **Plateforme IoT pour Bâtiments Intelligents** !  
Développé avec le framework Symfony, ce site centralise et gère divers services pour améliorer la gestion et l'efficacité énergétique des bâtiments intelligents.

## Table des Matières

1. [Aperçu](#aperçu)
2. [Fonctionnalités](#fonctionnalités)
3. [Installation](#installation)
4. [Utilisation](#utilisation)
5. [Contact](#contact)

## Aperçu

La plateforme offre aux utilisateurs une interface unique pour :
- S'inscrire et se connecter.
- Consulter et modifier les objets connectés d'un bâtiment.
- Gérer et personnaliser leur profil ainsi que l'apparence du site.
- Suivre en temps réel la consommation énergétique et les économies réalisées.

Les administrateurs bénéficient également d’un espace dédié pour :
- Gérer les utilisateurs (ajout, modification, suppression).
- Valider les inscriptions et traiter les signalements.
- Publier des actualités sur le bâtiment.
- Consulter et exporter les statistiques du site.

![Vue d'ensemble du dashboard](demo/home.png)

## Fonctionnalités

- **Inscription & Connexion**  
  Permet la création rapide de compte et l'accès facilité à la plateforme.

- **Gestion des Objets Connectés**  
  Visualisez, modifiez et configurez les dispositifs IoT intégrés dans le bâtiment.

- **Personnalisation**  
  Adaptez l'apparence du site selon vos préférences grâce aux options de personnalisation.

- **Suivi Énergétique**  
  Analysez la consommation énergétique et les économies réalisées via des graphiques et rapports détaillés.

- **Administration**  
  Accessible via un compte admin (exemple : `admin/admin`) pour :
  - Gérer les utilisateurs.
  - Valider les inscriptions et traiter les signalements.
  - Publier des actualités.
  - Consulter et exporter les statistiques détaillées.

## Utilisation

### Expérience Utilisateur

1. **Création d'un Compte**
   - Inscrivez-vous en fournissant vos informations de base (nom, email, mot de passe, etc.).
   - Complétez votre profil avec des informations supplémentaires, comme une photo de profil.

2. **Navigation et Gestion des Objets**
   - Accédez aux modules pour consulter et modifier les objets connectés.
   - Personnalisez l'apparence de l'interface selon vos préférences.
   - Recherchez d'autres utilisateurs.
   - Profitez d'un système de points pour débloquer de nouvelles fonctionnalités.

   ![Recherche d'utilisateurs](demo/Recherche.png)

3. **Suivi Énergétique**
   - Visualisez, via des rapports interactifs, la consommation énergétique du bâtiment et les économies réalisées.

   ![Tableau de bord énergétique](demo/dashboard.png)

### Mode Super-utilisateur

- Gérer les utilisateurs et valider les inscriptions.
- Traiter les signalements et publier des actualités.
- Consulter et exporter des statistiques détaillées.

## Installation

### 1. Télécharger le Projet

- **Accéder au Dépôt**  
  Rendez-vous sur la page GitHub du projet. Cliquez sur le bouton **"Code"** et sélectionnez **"Download ZIP"** pour télécharger une archive du projet.

- **Décompresser le Fichier**  
  Une fois le fichier ZIP téléchargé, faites un clic droit dessus et choisissez **"Extraire tout"** (ou **"Décompresser"**) pour obtenir le dossier complet du projet sur votre ordinateur.

### 2. Installer les Logiciels Nécessaires

Pour faire fonctionner le projet, vous devez installer :

- **PHP**  
  Téléchargez PHP depuis le [site officiel](https://www.php.net/).

- **Composer**  
  Téléchargez Composer depuis [getcomposer.org](https://getcomposer.org).

- **Symfony CLI**  
  Téléchargez Symfony CLI depuis [Symfony](https://symfony.com/download).

### 3. Configurer la Base de Données

- **Créer une Base de Données SQL**  
  Utilisez un outil tel que phpMyAdmin (inclus dans WampServer ou XAMPP) pour créer une nouvelle base de données. Par exemple, nommez-la `iot_plateforme`.
  Notez le nom de la base, ainsi que vos identifiants de connexion (nom d'utilisateur et mot de passe).
  - Modifier le Fichier .env Dans le dossier du projet, trouvez le fichier nommé .env. Ouvrez-le avec un éditeur de texte (comme Notepad ou Visual Studio Code). Cherchez la ligne qui commence par DATABASE_URL=. Modifiez-la pour y insérer vos informations.

- **Modifier le Fichier `.env`**
  Dans le dossier du projet, ouvrez le fichier `.env` avec un éditeur de texte (Notepad, VS Code, etc.) et modifiez la ligne commençant par `DATABASE_URL=` pour y insérer vos informations :
  
  ```dotenv
  DATABASE_URL="mysql://votreIdentifiant:votreMotDePasse@127.0.0.1:3306/iot_plateforme?serverVersion=5.7"


![Texte alternatif](demo/configData.png)

4. **Configurer la Messagerie Automatisée**:
Le projet peut envoyer automatiquement des e-mails (pour la confirmation d'inscription, par exemple).
  - Toujours dans le fichier .env, repérez les lignes concernant la messagerie (qui comportent généralement des paramètres SMTP comme le serveur, l'identifiant et le mot de passe).
  - Remplissez ces champs avec les informations fournies par votre service de messagerie.

    ![Texte alternatif](demo/configMailer.png)
    
5. **Mettre à Jour la Base de Données et Créer l'Utilisateur Administrateur**:
   Pour préparer la base de données et créer automatiquement un compte administrateur (de gestion), procédez ainsi :
   - Créer la Base Dans le même terminal, tapez :
     php bin/console doctrine:database:create
   - Lancer les Migrations Puis, tapez :
     php bin/console doctrine:migrations:migrate

   Ces commandes vont configurer votre base de données et créer les tables nécessaires, y compris un utilisateur administrateur (souvent défini par défaut, par exemple avec l'identifiant admin et le mot de passe admin).

6. **Lancer le Site Web avec le Serveur Symfony**:
   Maintenant que la configuration est terminée, vous pouvez lancer le site sur votre ordinateur.
   - Ouvrir le Terminal (ou Invite de Commandes)
Sur Windows : Cliquez sur le menu Démarrer et tapez "Invite de commandes" ou "PowerShell".
Sur macOS ou Linux : Ouvrez le Terminal depuis vos applications.

- Naviguer Dans le Dossier du Projet Tapez la commande suivante en remplaçant chemin/vers/le/projet par le chemin réel du dossier :
      cd chemin/vers/le/projet

- Démarrer le Serveur Tapez la commande suivante pour lancer le serveur de Symfony :
    symfony serve

- Accéder au Site Ouvrez votre navigateur internet (comme Chrome, Firefox ou Edge) et allez à l'adresse http://127.0.0.1:8000. Vous devriez voir la page d'accueil du projet.

    

