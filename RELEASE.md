# Release Instructions

1. Visit the [Duster Releases page](https://github.com/tighten/duster/releases); figure out what your next tag will be (increase the third number if it's a patch or fix; increase the second number if it's adding features)
2. On your local machine, pull down the latest changes on the `main` branch (`git checkout main && git pull`)
3. Update the version in [`config/app.php`](./config/app.php)
4. Remove dev dependencies `composer install --no-dev`
5. Compile the binary with

```zsh
./duster app:build
```

6. Run the build once to make sure it works (`./builds/duster`)
7. Commit all changes
8. Push all commits to GitHub
9. [Draft a new release](https://github.com/tighten/duster/releases/new) with both the tag version and title of your tag (e.g. `v1.5.1`)
10. Use the "Generate release notes" button to generate release notes from the merged PRs.
