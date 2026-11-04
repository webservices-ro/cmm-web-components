# Plugin releases

`module.config.json` is the source of truth for the plugin identity, WordPress
requirements, PHP prefix, and release repository. `package.json` remains the
source of truth for the plugin version. Packaging injects the version into the
plugin entry and readme before creating the ZIP.

## Create and publish a release

Run `npm run setup` when creating a module from this template. Commit the
generated identity and all feature changes before deploying. For each release:

```sh
npm run build
npm run package
git add .
git commit -m "Describe module changes"
npm run deploy
```

`npm run deploy` selects the exact release version, commits the version
change, creates a matching `v` tag, and pushes the current branch and tag.
Choose the minor, major, or custom option in the prompt when needed. GitHub
Actions builds the plugin and publishes `<slug>.zip` and `update.json` to
`module.config.json`'s `releaseRepository`.

The release workflow can publish to the same repository without a secret. If
the release repository is separate, configure `RELEASE_REPO_TOKEN` in the
source repository's Actions secrets with release-write permission on the
release repository.

This module publishes its WordPress update assets to
`alexbran8/cmm-web-components-releases`. The token must be available in this
source repository as the Actions secret `RELEASE_REPO_TOKEN` and must grant
`Contents: Read and write` access to that destination repository.
