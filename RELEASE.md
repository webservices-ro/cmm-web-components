# Plugin releases

`package.json` is the single source of truth for the plugin version. Packaging
injects that version into the WordPress plugin header, asset cache version, and
readme before creating the ZIP. Source files use `__PLUGIN_VERSION__` tokens and
should not be distributed directly.

## Create a release

Choose the appropriate semantic-version increment:

```sh
npm run version:patch
npm run version:minor
npm run version:major
```

Then package the current version:

```sh
npm run package
```

The result is written to `release/claudia-maria-wp-react-<version>.zip`. The
folder inside the archive remains `claudia-maria-wp-react`, allowing WordPress
to update the existing plugin rather than install a separate copy.

Commit `package.json` and `package-lock.json` together after changing a version.
Create a matching Git tag (for example, `v1.0.1`) when the release is approved.

## Publish to the release repository

GitHub Actions publishes release assets to
`alexbran8/claudia-maria-web-components-releases`. Add a fine-grained personal
access token to this source repository as an Actions secret named
`RELEASE_REPO_TOKEN`. The token needs read/write access to the Contents
permission of the release repository.

Make sure `claudia-maria-web-components-releases` has a `main` branch with at
least one initial commit. It can contain only a README; application source code
is not needed.

To publish a patch release from a clean working tree, run:

```sh
npm run deploy
```

The deploy script increments the patch version, commits `package.json` and
`package-lock.json`, creates the matching `v` tag, and explicitly pushes both
`main` and the tag. Commit all feature changes before running it.

The workflow verifies that the Git tag and package version match, builds the
plugin, and publishes these two assets:

- `claudia-maria-wp-react.zip`
- `update.json`

The stable manifest URL is:

`https://github.com/alexbran8/claudia-maria-web-components-releases/releases/latest/download/update.json`
