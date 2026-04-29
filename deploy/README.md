# Deployment configuration

Ce dossier contient les fichiers de production et les scripts d'installation.

## 1. Generer le `.env`

Copier ou generer le `.env` de prod :

```bash
cp deploy/.env.production.example .env
```

ou avec le script interactif :

```bash
bash deploy/scripts/configure-env.sh
```

Le script permet de renseigner :

- `APP_URL`
- domaine principal et domaine admin
- base centrale MySQL
- base template tenant
- SMTP / mail expediteur
- mode cache / queue
- domaines centraux Tenancy

## 2. Completer les variables sensibles

Verifier ensuite :

- `OPENAI_API_KEY`
- `SERPAPI_KEY`
- `OPENWEATHER_API_KEY`
- `REDIS_*` si Redis est utilise
- `APP_KEY` si elle n'a pas encore ete generee

## 3. Lancer le deploiement

```bash
bash deploy/scripts/deploy.sh
```

Le script :

- verifie la presence du `.env`
- installe les dependances
- build les assets si `npm` est present
- genere `APP_KEY` si necessaire
- lance les migrations
- regenere les caches Laravel

## 4. Premiere ouverture du site

Si l'application n'est pas encore configuree metier, la racine du domaine central redirige automatiquement vers :

```text
/onboarding
```

Exemple :

- `https://lj.osmoseconsulting.fr` -> page de configuration si aucune entreprise n'existe encore
- sinon -> dashboard admin
