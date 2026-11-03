#!/bin/sh
set -eu

project_root=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
cd "$project_root"

current_version=$(node -p "require('./package.json').version")
base_version=${current_version%%-*}
old_ifs=$IFS
IFS=.
set -- $base_version
IFS=$old_ifs

if [ "$#" -ne 3 ]; then
  printf 'Cannot calculate release choices from version %s.\n' "$current_version" >&2
  exit 1
fi

current_major=$1
current_minor=$2
current_patch=$3
patch_version="$current_major.$current_minor.$((current_patch + 1))"
minor_version="$current_major.$((current_minor + 1)).0"
major_version="$((current_major + 1)).0.0"
next_prerelease_version=$(node -e '
  const version = require("./package.json").version
  const match = version.match(/^(\d+)\.(\d+)\.(\d+)-([a-z])$/)
  if (match && match[4] !== "z") {
    process.stdout.write(`${match[1]}.${match[2]}.${match[3]}-${String.fromCharCode(match[4].charCodeAt(0) + 1)}`)
  } else {
    const [major, minor, patch] = version.split("-")[0].split(".").map(Number)
    process.stdout.write(`${major}.${minor}.${patch + 1}-a`)
  }
')

printf 'Select the exact release version:\n'
release_choice=$(node scripts/select-release.mjs \
  "v$patch_version (patch)" \
  "v$minor_version (minor)" \
  "v$major_version (major)" \
  "v$next_prerelease_version (next prerelease)" \
  "Enter another exact release tag")

case "$release_choice" in
  1)
    version_bump=$patch_version
    ;;
  2)
    version_bump=$minor_version
    ;;
  3)
    version_bump=$major_version
    ;;
  4)
    version_bump=$next_prerelease_version
    ;;
  5)
    printf 'Exact release version: '
    IFS= read -r version_bump
    ;;
  *)
    printf 'Invalid choice: %s. Choose a number from 1 to 5.\n' "$release_choice" >&2
    exit 1
    ;;
esac

printf 'Next tag to deploy: v%s\n' "$version_bump"

if [ -n "$(git status --porcelain)" ]; then
  printf 'The working tree has uncommitted changes. Commit them before deploying.\n' >&2
  exit 1
fi

npm version "$version_bump" --no-git-tag-version
release_version=$(node -p "require('./package.json').version")
release_tag="v$release_version"

if git rev-parse --verify --quiet "refs/tags/$release_tag" >/dev/null; then
  printf 'Tag %s already exists.\n' "$release_tag" >&2
  exit 1
fi

git add package.json package-lock.json
git commit -m "Release $release_tag"
git tag "$release_tag"
git push origin main "$release_tag"
