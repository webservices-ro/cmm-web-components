#!/bin/sh
set -eu

project_root=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
cd "$project_root"

plugin_slug=$(node -e "process.stdout.write(JSON.parse(require('fs').readFileSync('module.config.json', 'utf8')).slug)")
case "$plugin_slug" in
  ''|*[!a-z0-9-]*)
    printf 'Invalid plugin slug: %s\n' "$plugin_slug" >&2
    exit 1
    ;;
esac

plugin_dir="$project_root/wordpress-plugin/$plugin_slug"
output_dir="$project_root/release"
staging_dir=$(mktemp -d)
version=$(node -p "require('./package.json').version")
archive_name="$plugin_slug-$version.zip"

cleanup() {
  rm -rf "$staging_dir"
}
trap cleanup EXIT INT TERM

if [ ! -f "$plugin_dir/$plugin_slug.php" ]; then
  printf 'Plugin entry file not found: %s\n' "$plugin_dir/$plugin_slug.php" >&2
  exit 1
fi

case "$version" in
  [0-9]*.[0-9]*.[0-9]*) ;;
  *)
    printf 'Invalid plugin version: %s\n' "$version" >&2
    exit 1
    ;;
esac

mkdir -p "$output_dir"
cp -R "$plugin_dir" "$staging_dir/$plugin_slug"

for versioned_file in \
  "$staging_dir/$plugin_slug/$plugin_slug.php" \
  "$staging_dir/$plugin_slug/readme.txt"
do
  sed "s/__PLUGIN_VERSION__/$version/g" "$versioned_file" > "$versioned_file.tmp"
  mv "$versioned_file.tmp" "$versioned_file"
done

if grep -R "__PLUGIN_VERSION__" "$staging_dir/$plugin_slug" >/dev/null 2>&1; then
  printf 'Unresolved plugin version placeholder found in package.\n' >&2
  exit 1
fi

rm -f "$output_dir/$archive_name"
(
  cd "$staging_dir"
  zip -qr "$output_dir/$archive_name" "$plugin_slug"
)

printf 'Created %s\n' "$output_dir/$archive_name"
