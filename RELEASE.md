# Release Instructions

1. Pull down the latest changes on the `main` branch
2. Create a new tag (used in [`config/app.php`](./config/app.php))
3. Compile the binary with

```zsh
./duster app:build
```

4. Commit all changes
5. Push all commits to GitHub
6. Create a new GitHub release with the release notes
