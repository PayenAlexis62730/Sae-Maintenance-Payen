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
CREATE DATABASE exercice_app;

USE exercice_app;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des exercices réalisés
CREATE TABLE exercices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    exercice_name VARCHAR(100),
    score INT,
    date_completed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

```

2. **Modifier `config.php`** avec vos informations de connexion MySQL :
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sae_maintenance";
```

**Auteur :** Alexis Payen  
**Licence :** Libre

