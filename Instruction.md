# SAE Maintenance - Système de Connexion avec Profil

## Description
Cette partie du projet ajoute un système de connexion avec profils d'utilisateur à l'application. Il permet aux utilisateurs de s'inscrire, se connecter et suivre leurs statistiques d'exercices réalisés. De plus, nous avons modifié la structure du projet pour que la page de connexion s'affiche automatiquement si l'utilisateur n'est pas connecté.

## Fonctionnalités
- **Inscription des utilisateurs**
- **Connexion pour la gestion de session**
- **Redirection automatique vers la page de connexion si l'utilisateur n'est pas authentifié**
- **Enregistrement des exercices**
- **Affichage des statistiques**
- **Déconnexion**

## Installation
### Prérequis
- Une base de données **MySQL**

### Configuration
1. **Créer la base de données** dans MySQL :
```sql
CREATE DATABASE sae_maintenance;
USE sae_maintenance;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_name VARCHAR(100) NOT NULL,
    score INT DEFAULT 0,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

2. **Modifier `config.php`** avec vos informations de connexion MySQL :
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sae_maintenance";
```

3. **Redirection automatique vers la connexion** :
   - Nous avons modifié `index.php` pour que les utilisateurs soient redirigés vers `login.php` s'ils ne sont pas connectés.
   - Cela évite d'accéder directement à l'accueil sans authentification.
   
4. **Lancer le projet** en ouvrant un serveur local (ex: MAMP, WAMP) et accéder aux fichiers via le navigateur.

## Utilisation
1. **S'inscrire** via le fichier `register.php`.
2. **Se connecter** via le fichier `login.php`.
3. **Accéder à son profil** via le fichier `profile.php`.
4. **Se déconnecter** via le fichier `logout.php`.

## Modifications rajouter au code existant 
- Afon de renforcer la sécurité du site j'ai inclus la connexion obligatoire avant d'accéder au site pour cela j'ai changé index.html en index.php pour inclure la page de connexion avant. 
- Renommage des fichiers pour plus de cohérence (`register.php` -> `enregister.php`, `login.php` -> `connexion.php`, `profile.php` -> `profil.php`, `logout.php` -> `deconnexion.php`).


**Auteur :** Alexis Payen  
**Licence :** Libre

