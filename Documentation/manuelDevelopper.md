# Manuel Développeur - Sae Maintenance Payen

---

## 📌 Introduction

Le projet **Sae Maintenance Payen** est une application web développée en **PHP**, avec une base de données **MySQL** gérée via **PHPMyAdmin**. L'application utilise également **HTML**, **CSS** et **JavaScript** pour la partie front-end.

Ce manuel est destiné aux développeurs qui souhaitent comprendre, maintenir et étendre l'application.

---

## 📂 Arborescence du Projet

```
📁 /PayenAlexis62730
│── 📁 /addition               # Test pour l'addition
│── 📁 /conjugaison_phrase     # Test Conjugaison des phrases
│── 📁 /conjugaison_verbe      # Test en Conjugaison des verbes
│── 📁 /dictee                 # Test en dictée
│── 📁 /images                 # Images utilisées dans le projet
│── 📁 /multiplication         # Test pour la multiplication
│── 📁 /soustraction           # Test pour la soustraction
│── 📁 /Connexion              # Dossier d'amélioration du site (profil/role/inscription, etc.)
│   ├── 📝 config.php          # Fichier de connexion à la base de données
│   ├── 📝 exercice_config.php # Formulaire de création d'exercice pour enseignant
│   ├── 📝 login.php           # Formulaire de connexion au site
│   ├── 📝 logout.php          # Fonctionnalité de déconnexion du profil connecté au site
│   ├── 📝 profile.php         # Fichier de création et d'affichage du profil selon les rôles
│   ├── 📝 save_exercice.php   # Fonctionnalité de sauvegarde des exercices faits par les enfants
│   ├── 📝 signup.php          # Formulaire d'inscription
│── 📁 /documentation          # Documentation complémentaire (Développeur/Utilisateur)
│── 📄 LICENSE                 # Licence du projet
│── 📄 README.md               # Bilan de ce qui a été fait
│── 🏠 /index.php              # Page d'accueil du projet
│── 🎨 /style.css              # Fichier CSS principal
```

---

## 🛠️ Technologies Utilisées

- **🖥️ PHP** : Langage côté serveur pour la logique de traitement.
- **🗄️ MySQL** : Base de données utilisée pour stocker les informations.
- **🌐 PHPMyAdmin** : Interface web pour gérer la base de données.
- **📄 HTML** : Langage de balisage pour la structure des pages.
- **🎨 CSS** : Feuilles de style pour la mise en page des pages web.
- **⚡ JavaScript** : Langage de programmation pour l’interactivité côté client.

---

## 🚀 Installation

### 🔧 Prérequis

- Serveur web (**Apache/Nginx**).
- **PHP 7.4** ou supérieur.
- **MySQL** ou **MariaDB**.
- **PHPMyAdmin** pour la gestion de la base de données.

### 📥 Étapes d'installation

1. **Cloner le projet depuis GitHub** :
   ```bash
   git clone https://github.com/PayenAlexis62730/Sae-Maintenance-Payen.git
   ```

2. **Configurer la base de données** :
   - Voir le fichier **Guide_installation_BDD.md**
   - Modifier les identifiants dans `database.php` si nécessaire.

---

## 📑 Fichiers Principaux

- **🏠 index.php** : Page d'accueil du projet.
- **🎨 style.css** : Fichier de style CSS principal pour la mise en page.
- **🔌 Connexion/config.php** : Le fichier de connexion à la base de données.
- **👤 profile.php** : 80% des fonctionnalités passent par le profil.

---

## 💾 Exemple de Code : Connexion à la Base de Données

📂 **Fichier : database.php**

```php
<?php
// Paramètres de connexion à la base de données
$host = 'localhost:3308'; // Le nom d'hôte (localhost) et le port (3308). 
$dbname = 'exercice_app'; // Le nom de la base de données
$username = 'root'; // L'utilisateur MySQL
$password = ''; // Le mot de passe (laisser vide si aucun mot de passe)

// Tentative de connexion à la base de données avec PDO
try {
    // Création de l'instance PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Affichage de l'erreur si la connexion échoue
    die("❌ Erreur de connexion : " . $e->getMessage());
}
?>
```

---

## 📌 Conclusion

Ce projet est une base pour une application web simple, mais il peut être étendu avec des fonctionnalités supplémentaires. Ce manuel vous fournit les informations essentielles pour commencer à travailler sur le projet, comprendre la structure du code et effectuer des tests.

📩 **Besoin d'aide ?** Consultez la documentation ou contactez un administrateur du projet sur alexispayen2004@gmail.com! 🚀

