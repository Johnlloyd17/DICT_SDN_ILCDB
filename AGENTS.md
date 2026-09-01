# AGENTS.md — Project Conventions for DICT SDN ILCDB

## Git Workflow Commands

When the user says any of the following, execute the corresponding git commands automatically.

### `publish` / `commit` / `push`
Stage, commit, and push all changes:
```bash
git add -A
git commit -m "<user-provided message or auto-generated summary>"
git push origin master
```

### `pull` / `update`
Fetch and pull the latest changes from remote:
```bash
git fetch origin
git pull origin master
```

### `status`
Show the current git status:
```bash
git status
```

### `log`
Show recent commits:
```bash
git log --oneline -10
```

## Branch Convention

- **Branch:** `master` (tracks `origin/master`)
- **Remote:** `origin` -> `https://github.com/Johnlloyd17/DICT_SDN_ILCDB.git`
- **Working directory:** `C:\xampp\htdocs\DICT_SDN_ILCDB`

## Commit Message Style

Use concise, descriptive messages:
- `feat: add new module X`
- `fix: resolve Y issue`
- `update: refresh Z component`
- `docs: update documentation`

## Project Notes

- **Stack:** Laravel 12 + MySQL + Blade + Alpine.js + Tailwind CSS
- **App runs at:** `http://127.0.0.1:8000`
- **Always run `npm run build` after CSS/JS changes before publishing**
