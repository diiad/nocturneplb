# Nocturne PLB

Web app for a private event/nightclub organization ("PLB"): season management, member payments tracking, user accounts and admin dashboard with stats. Built during an internship.

## Stack

- **PHP** (procedural, PDO for MySQL)
- **MySQL**

## Features

- Sign up / login (password hashing with `password_hash`)
- Season management (admin)
- Payment tracking per member/season, missing payments view
- User profile edition, admin user management
- Stats dashboard

## Setup

```bash
cp .env.example .env   # fill in your own DB credentials
```

Import your schema into MySQL, then serve the project with any PHP-capable web server (e.g. Apache/MAMP) pointed at the project root.

## Structure

```
includes/       ← shared includes (db connection, nav, env loader, access guards)
traitements/    ← form-handling scripts (login, signup, payments, users...)
css/, img/      ← assets
```

---

## Version française

Application web pour la gestion d'une organisation d'événements privés ("PLB") : gestion des saisons, suivi des paiements des membres, comptes utilisateurs et dashboard admin avec statistiques. Réalisé pendant un stage.

### Mise en place

```bash
cp .env.example .env   # à compléter avec tes propres identifiants DB
```

Importer le schéma dans MySQL, puis servir le projet avec un serveur PHP (Apache/MAMP) pointé sur la racine du projet.
