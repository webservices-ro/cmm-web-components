#!/bin/sh
set -eu

project_root=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
plugin_parent="$project_root/wordpress-plugin"
plugin_name="claudia-maria-wp-react"
output_dir="$project_root/release"
staging_dir=$(mktemp -d)
version=$(cd "$project_root" && node -p "require('./package.json').version")
archive_name="$plugin_name-$version.zip"

cleanup() {
  rm -rf "$staging_dir"
}
trap cleanup EXIT INT TERM

mkdir -p "$output_dir"
cp -R "$plugin_parent/$plugin_name" "$staging_dir/$plugin_name"

case "$version" in
  [0-9]*.[0-9]*.[0-9]*) ;;
  *)
    printf 'Invalid plugin version: %s\n' "$version" >&2
    exit 1
    ;;
esac

for versioned_file in \
  "$staging_dir/$plugin_name/$plugin_name.php" \
  "$staging_dir/$plugin_name/readme.txt"
do
  sed "s/__PLUGIN_VERSION__/$version/g" "$versioned_file" > "$versioned_file.tmp"
  mv "$versioned_file.tmp" "$versioned_file"
done

if grep -R "__PLUGIN_VERSION__" "$staging_dir/$plugin_name" >/dev/null 2>&1; then
  printf 'Unresolved plugin version placeholder found in package.\n' >&2
  exit 1
fi

rm -f "$output_dir/$archive_name"
(
  cd "$staging_dir"
  zip -qr "$output_dir/$archive_name" "$plugin_name"
)

printf 'Created %s\n' "$output_dir/$archive_name"
