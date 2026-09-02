# Publish This Project to GitHub (Manual Steps)

Run these commands one by one in the terminal, from the project folder.

## 1. Create the repository on GitHub

```bash
gh repo create Johnlloyd17/DICT_SDN_ILCDB --public --source=. --remote=origin
```

If the repo already exists, just add the remote instead:

```bash
git remote add origin https://github.com/Johnlloyd17/DICT_SDN_ILCDB.git
```

## 2. Stage and commit everything

```bash
git add -A
git commit -m "Initial commit: DICT SDN ILCDB Laravel application"
```

## 3. Push to GitHub

```bash
git push -u origin master
```

## Useful commands

- Check status: `git status`
- Check remote: `git remote -v`
- See recent commits: `git log --oneline`
- Push later changes: `git add -A; git commit -m "your message"; git push`
